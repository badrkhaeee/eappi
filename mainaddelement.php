<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	SafeStartSession();
	CheckAuthorisation();
	$sObjectGUID = SafeGetInternalArrayParameter($_POST, 'guid');
	$sParentName = SafeGetInternalArrayParameter($_POST, 'parentname');
	$aDiagramTechs	= array();
	$aDiagramTypes	= array();
	include('./data_api/get_shape_element.php');
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	echo '<div class="default-dialog" style="max-height:510px;">';
	$sHeaderTitle = '';
	$sHeaderTitle .= '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName('images/element16/add.png') . '" onload="OnLoad_SetupSpecialCtrls(\'add-element-notes-field\',\'\')"> ';
	$sHeaderTitle .= _glt('Add Object');
	echo WriteDialogHeader($sHeaderTitle);
	echo '<div class="dialog-body">';
	echo '<form class="add-element-form" role="form" onsubmit="OnFormRunAddElement(event)">';
	echo '<table id="add-element-table" style="width: 100%;">';
	echo '<tbody>';
	echo '<tr class="add-element-line">';
	echo '<td class="add-element-label"><span class="noWrapCell">' . _glt('Element type') . '<span class="field-label-required">&nbsp;*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>';
	echo '<td class="add-element-field">';
	$aComboOptions = array();
	$sPrefix = substr($sObjectGUID,0,2);
	if ( (IsSessionSettingTrue('add_objecttype_package')) && ( $sPrefix !== 'el' ) )
	array_push($aComboOptions, array('label'=>'Package','value'=>'package'));
	if ( (IsSessionSettingTrue('add_diagrams') && IsSessionSettingTrue('login_perm_creatediagram')) && ( $sPrefix !== 'el' ) )
	array_push($aComboOptions, array('label'=>'Diagram','value'=>'diagram'));
	if ( IsSessionSettingTrue('add_objecttype_review') )
	array_push($aComboOptions, array('label'=>'Review','value'=>'review'));
	if ( IsSessionSettingTrue('add_objecttype_actor') )
	array_push($aComboOptions, array('label'=>'Actor','value'=>'actor'));
	if ( IsSessionSettingTrue('add_objecttype_change') )
	array_push($aComboOptions, array('label'=>'Change','value'=>'change'));
	if ( IsSessionSettingTrue('add_objecttype_component') )
	array_push($aComboOptions, array('label'=>'Component','value'=>'component'));
	if ( IsSessionSettingTrue('add_objecttype_feature') )
	array_push($aComboOptions, array('label'=>'Feature','value'=>'feature'));
	if ( IsSessionSettingTrue('add_objecttype_issue') )
	array_push($aComboOptions, array('label'=>'Issue','value'=>'issue'));
	if ( IsSessionSettingTrue('add_objecttype_node') )
	array_push($aComboOptions, array('label'=>'Node','value'=>'node'));
	if ( IsSessionSettingTrue('add_objecttype_requirement') )
	array_push($aComboOptions, array('label'=>'Requirement','value'=>'requirement'));
	if ( IsSessionSettingTrue('add_objecttype_task') )
	array_push($aComboOptions, array('label'=>'Task','value'=>'task'));
	if ( IsSessionSettingTrue('add_objecttype_usecase') )
	array_push($aComboOptions, array('label'=>'UseCase','value'=>'usecase'));
	$aAttribs = array();
	$aAttribs['id']	= 'add-element-elementtype';
	$aAttribs['name']	= 'elementtype';
	$aAttribs['class']	= 'webea-main-styled-combo';
	$aAttribs['onclick']	= 'onClickAddElementType(this.value)';
	$aAttribs['onchange']	= 'onClickAddElementType(this.value)';
	$sType = '';
	$sTechnology = '';
	$sDiagramType = '';
	$sSettings = SafeGetInternalArrayParameter($_SESSION, 'add_object_selection');
	if(!strIsEmpty($sSettings))
	{
	$aSettings = json_decode($sSettings);
	$sType = $aSettings->{'type'};
	$sTechnology = $aSettings->{'technology'};
	$sDiagramType = $aSettings->{'diagram_type'};
	}
	echo BuildHTMLComboFrom2DimArray($aComboOptions,$aAttribs,false,$sType);
	echo '</td>';
	echo '</tr>';
	$bDiagramIsFirstEntry = false;
	if (!(empty($aComboOptions)) && ($aComboOptions[0]['value'] === 'diagram'))
	$bDiagramIsFirstEntry = true;
	echo '<tr id="add-element-diagram-tdtech-row1" class="add-element-line" style="display: ' . ($bDiagramIsFirstEntry?'block':'none') . ';">';
	echo '<td id="add-element-diagram-tdtech1" class="add-element-label field-label-vert-align-middle"><span class="noWrapCell">' . _glt('Technology') . '<span class="field-label-required">&nbsp;*</span></span></td>';
	echo '<td id="add-element-diagram-tdtech2" class="add-element-field" onclick="onClickAddElementDiaTech(this.value, \'' . _j(json_encode($aDiagramTypes)) . '\')" onchange="onClickAddElementDiaTech(this.value, \'' . _j(json_encode($aDiagramTypes)) . '\')">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-element-diagramtech-field';
	$aAttribs['name']	= 'diagramtech';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aDiagramTechs, $aAttribs, false, $sTechnology);
	echo '</td>';
	echo '</tr>';
	$iCnt = count($aDiagramTechs);
	$aTypes = array();
	if ( $i > 0 )
	{
	$sTech = SafeGetArrayItem2Dim($aDiagramTypes, 0, 'tech');
	if ( !strIsEmpty($sTech) )
	{
	$iCnt = count($aDiagramTypes);
	for ($i=0; $i<$iCnt; $i++)
	{
	if ( SafeGetArrayItem2Dim($aDiagramTypes, $i, 'tech')===$sTech )
	{
	$sLabel	= SafeGetArrayItem2Dim($aDiagramTypes, $i, 'alias');
	if ( strIsEmpty($sLabel)  )
	$sLabel 	= SafeGetArrayItem2Dim($aDiagramTypes, $i, 'name');
	$sValue	= SafeGetArrayItem2Dim($aDiagramTypes, $i, 'fqname');
	if ( strIsEmpty($sValue)  )
	$sValue 	= SafeGetArrayItem2Dim($aDiagramTypes, $i, 'name');
	$aRow	= array();
	$aRow['label']	= $sLabel;
	$aRow['value']	= $sValue;
	$aTypes[] = $aRow;
	}
	}
	}
	}
	echo '<tr id="add-element-diagram-tdtech-row2"  class="add-element-line" style="display: ' . ($bDiagramIsFirstEntry?'block':'none') . ';">';
	echo '<td id="add-element-diagram-tdtype1" class="add-element-label field-label-vert-align-middle"><span class="noWrapCell">' . _glt('Diagram type') . '<span class="field-label-required">&nbsp;*</span></span></td>';
	echo '<td id="add-element-diagram-tdtype2" class="add-element-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-element-diagramtype-field';
	$aAttribs['name']	= 'diagramtype';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFrom2DimArray($aTypes, $aAttribs, false, $sDiagramType);
	echo '<input hidden id="selected-diagram-type" value="'.$sDiagramType.'">';
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-element-line" style="height: 60px;">';
	echo '<td class="add-element-label field-label-vert-align-top">' . _glt('Name') . '<span class="field-label-required">&nbsp;*&nbsp;</span></td>';
	echo '<td class="add-element-field" style="width: 80%;"><textarea id="add-element-name-field" class="webea-main-styled-textbox" name="name" maxlength="255" onfocus="EnsureInputFieldVisible(this)"></textarea></td>';
	echo '</tr>';
	echo '<tr class="add-element-line" style="height: 160px;">';
	echo '<td class="add-element-label field-label-vert-align-top">' . _glt('Notes') . '</td>';
	echo '<td class="add-element-field last-field" style="width: 80%;"><div id="add-element-notes-div"><textarea id="add-element-notes-field" name="notes" onfocus="EnsureInputFieldVisible(this)"></textarea></div></td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '<input type="hidden" name="parentguid" value="' . _h($sObjectGUID) . '">';
	echo '<input type="hidden" name="parentname" value="' . _h($sParentName) . '">';
	echo '</form>';
	echo '</div>';
	echo '<div class="dialog-footer">';
	echo '<div class="dialog-buttons-container">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-apply" type="submit" onclick="OnFormRunAddElement(event)" value="' . _glt('Add') . '">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-ok" type="submit" onclick="OnFormRunAddElement(event, true)" value="' . _glt('Add') . ' & ' . _glt('Close') . '">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-close" type="submit" onclick="OnClickCloseDialogButton()" value="' . _glt('Close') . '">';
	echo '</div>';
	echo '</div>';
	}
?>
<script>
var unsaved = false;
$(":input").change(function(){
    unsaved = true;
});
$("#add-element-elementtype").click();
$("#add-element-diagram-tdtech2").click();
$("#add-element-diagramtype-field").val($("#selected-diagram-type").val());
function onClickAddElementType(sObjectType)
{
	var ele1 = document.getElementById('add-element-diagram-tdtech-row1');
	var ele2 = document.getElementById('add-element-diagram-tdtech-row2');
	if (ele1)
	{
	if ( sObjectType == 'diagram' )
	{
	ele1.style.display = 'block';
	ele2.style.display = 'block';
	}
	else
	{
	ele1.style.display = 'none';
	ele2.style.display = 'none';
	}
	}
}
function onClickAddElementDiaTech(sObjectType, sDiagramTypes)
{
	var eleTech = document.getElementById('add-element-diagramtech-field');
	var eleTypes = document.getElementById('add-element-diagramtype-field');
	if (eleTech && eleTypes)
	{
	try
	{
	var sTech = eleTech.value;
	var aDTs = JSON.parse(sDiagramTypes);
	for (var i = eleTypes.options.length-1 ; i>=0 ; i--)
	{
	eleTypes.remove(i);
	}
	for (i = 0; i<aDTs.length; i++)
	{
	if (aDTs[i]['tech'] === sTech)
	{
	var sLabel	= aDTs[i]['alias'];
	if ( sLabel === ''  )
	sLabel 	= aDTs[i]['name'];
	var sValue	= aDTs[i]['fqname'];
	if ( sValue === ''  )
	sValue 	= aDTs[i]['name'];
	var el = document.createElement("option");
	el.textContent = sLabel;
	el.value = sValue;
	eleTypes.appendChild(el);
	}
	}
	}
	catch(e)
	{
	}
	}
}
</script>