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
	$aAuthors	= array();
	$aRoles	= array();
	include('./data_api/get_shape_elementresalloc.php');
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$aObjectDetails	= array();
	include('./data_api/get_element.php');
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$sObjectName 	= SafeGetArrayItem1Dim($aObjectDetails, 'namealias');
	$sObjImageURL	= SafeGetArrayItem1Dim($aObjectDetails, 'imageurl');
	$sObjectResType = SafeGetArrayItem1Dim($aObjectDetails, 'restype');
	$sObjectName 	= GetPlainDisplayName($sObjectName);
	$sReadOnlyProp = '';
	if ( IsMobileBrowser() )
	{
	$sReadOnlyProp = 'readonly="readonly"';
	}
	$sRichEditCtrlsCSV = '\'add-elementresalloc-notes-field\'';
	$sDatePickerCtrlsCSV = '\'add-elementresalloc-startdate-field|add-elementresalloc-startdate-img,add-elementresalloc-enddate-field|add-elementresalloc-enddate-img\'';
	echo '<div class="default-dialog" style="max-height:480px;">';
	$sHeaderTitle = '';
	$sHeaderTitle .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-resallocadd" onload="OnLoad_SetupSpecialCtrls(' . $sRichEditCtrlsCSV . ',' . $sDatePickerCtrlsCSV . ')">&nbsp';
	$sHeaderTitle .=   _glt('Add resource allocation to');
	$sHeaderTitle .= '  <img alt="' . _h($sObjectResType) . '" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sObjImageURL) . '">';
	$sHeaderTitle .= '  <div class="objectname-in-header">' . _h($sObjectName) . '</div>';
	echo WriteDialogHeader($sHeaderTitle);
	echo '<div class="dialog-body">';
	echo '<form class="add-elementresalloc-form" role="form" onsubmit="OnFormRunAddElementResAlloc(event)">';
	echo '<table id="add-elementresalloc-table"><tbody>';
	echo '<tr class="add-elementresalloc-line">';
	echo '<td class="add-elementresalloc-label">' . _glt('Resource') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="add-elementresalloc-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-elementresalloc-resource-field';
	$aAttribs['name']	= 'resource';
	$aAttribs['maxlength']	= '255';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aAuthors, $aAttribs, true);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementresalloc-line">';
	echo '<td class="add-elementresalloc-label">' . _glt('Role') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="add-elementresalloc-field">';
	$aAttribs['id']	= 'add-elementresalloc-role-field';
	$aAttribs['name']	= 'role';
	$aAttribs['maxlength']	= '255';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aRoles, $aAttribs, true);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementresalloc-line">';
	echo '<td class="add-elementresalloc-label">' . _glt('Start date') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="add-elementresalloc-field"><input id="add-elementresalloc-startdate-field" class="webea-main-styled-date" name="startdate" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'add-elementresalloc-startdate-field\')" ' . _h($sReadOnlyProp) . '>';
	echo '<div style="display: none;"><img id="add-elementresalloc-startdate-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png" height="19" width="17"></div>';
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementresalloc-line">';
	echo '<td class="add-elementresalloc-label">' . _glt('End date') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="add-elementresalloc-field"><input id="add-elementresalloc-enddate-field" class="webea-main-styled-date" name="enddate" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'add-elementresalloc-enddate-field\')" ' . _h($sReadOnlyProp) . '>';
	echo '<div style="display: none;"><img id="add-elementresalloc-enddate-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png" height="19" width="17"></div>';
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementresalloc-line">';
	echo '<td class="add-elementresalloc-label">' . _glt('Percent Complete') . '&nbsp;</td>';
	echo '<td class="add-elementresalloc-field"><input id="add-elementresalloc-percentcomp-field" class="webea-main-styled-number" name="percentcomp" type="number" value="0" min="0" max="100" step="1" maxlength="3"></td>';
	echo '</tr>';
	echo '<tr class="add-elementresalloc-line">';
	echo '<td class="add-elementresalloc-label field-label-vert-align-top">' . _glt('Description') . '</td>';
	echo '<td class="add-elementresalloc-field"><div id="add-elementresalloc-notes-div"><textarea id="add-elementresalloc-notes-field" name="notes" onfocus="EnsureInputFieldVisible(this)"></textarea></div></td>';
	echo '</tr>';
	echo '</tbody></table>';
	echo '<input type="hidden" name="guid" value="' . _h($sObjectGUID) . '">';
	echo '<input type="hidden" name="parentname" value="' . _h($sObjectName) . '">';
	echo '<input type="hidden" name="parentimageurl" value="' . _h($sObjImageURL) . '">';
	echo '</form>';
	echo '</div>';
	echo '<div class="dialog-footer">';
	echo '<div class="dialog-buttons-container">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-apply" type="submit" onclick="OnFormRunAddElementResAlloc(event)" value="' . _glt('Add') . '">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-ok" type="submit" onclick="OnFormRunAddElementResAlloc(event, true)" value="' . _glt('Add') . ' & ' . _glt('Close') . '">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-close" type="submit" onclick="OnClickCloseDialogButton()" value="' . _glt('Close') . '">';
	echo '</div>';
	echo '</div>';
	}
	}
?>
<script>
var unsaved = false;
$(":input").change(function(){
    unsaved = true;
});
</script>