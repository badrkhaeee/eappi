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
	$sChgMgmtType = SafeGetInternalArrayParameter($_POST, 'chmgmttype');
	$aAuthors	= array();
	$aStatuses	= array();
	$aPriorities= array();
	$aTypes	= array();
	include('./data_api/get_shape_elementchgmgmt.php');
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
	$sDate1Desc 	= '';
	$sDate2Desc 	= '';
	$sPerson1Desc 	= '';
	$sPerson2Desc 	= '';
	GetChgMgmtFieldNames($sChgMgmtType, 1, $sDate1Desc, $sDate2Desc, $sPerson1Desc, $sPerson2Desc);
	$sToday = date('Y-m-d');
	$sReadOnlyProp = '';
	if ( IsMobileBrowser() )
	{
	$sReadOnlyProp = 'readonly="readonly"';
	}
	$sRichEditCtrlsCSV = '\'add-elementchgmgmt-notes-field,add-elementchgmgmt-history-field\'';
	$sDatePickerCtrlsCSV = '\'add-elementchgmgmt-date1-field|add-elementchgmgmt-date1-img,add-elementchgmgmt-date2-field|add-elementchgmgmt-date2-img\'';
	$sText = '';
	$sImageClass ='';
	GetChgMgtAddText($sChgMgmtType, $sText, $sImageClass);
	$sDialogHeight = '650px';
	if ($sChgMgmtType === 'risk')
	$sDialogHeight = '400px';
	echo '<div class="default-dialog" style="max-height:' . $sDialogHeight . ';">';
	$sHeaderTitle = '';
	$sHeaderTitle .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-testedit" onload="OnLoad_SetupSpecialCtrls(' . $sRichEditCtrlsCSV . ',' . $sDatePickerCtrlsCSV . ')">&nbsp';
	$sHeaderTitle .= _h($sText) . ' <img alt="' . _h($sObjectResType) . '" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sObjImageURL) . '">';
	$sHeaderTitle .= '  <div class="objectname-in-header">' .  _h($sObjectName) . '</div>';
	echo WriteDialogHeader($sHeaderTitle);
	echo '<div class="dialog-body">';
	echo '<form class="add-elementchgmgmt-form" role="form" onsubmit="OnFormRunAddElementChgMgmt(event)">';
	echo '<table id="add-elementchgmgmt-table"><tbody>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label">' . _glt('Name') . '<span class="field-label-required">&nbsp;*</span></td>';
	echo '<td class="add-elementchgmgmt-field"><input id="add-elementchgmgmt-name-field" class="webea-main-styled-textbox" name="name" maxlength="255"></td>';
	echo '</tr>';
	if ( $sChgMgmtType !== 'risk' )
	{
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-element-label">' . _glt('Status') . '<span class="field-label-required">&nbsp;*</span></td>';
	echo '<td class="add-elementchgmgmt-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-elementchgmgmt-status-field';
	$aAttribs['name']	= 'status';
	$aAttribs['maxlength']	= '50';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aStatuses, $aAttribs, true);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-element-label">' . _glt('Priority') . '<span class="field-label-required">&nbsp;*</span></td>';
	echo '<td class="add-elementchgmgmt-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-elementchgmgmt-priority-field';
	$aAttribs['name']	= 'priority';
	$aAttribs['maxlength']	= '50';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aPriorities, $aAttribs);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-element-label">' . $sPerson1Desc . '</td>';
	echo '<td class="add-elementchgmgmt-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-elementchgmgmt-author1-field';
	$aAttribs['name']	= 'author1';
	$aAttribs['maxlength']	= '255';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aAuthors, $aAttribs);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label">' . $sDate1Desc . '&nbsp;&nbsp;</td>';
	echo '<td class="add-elementchgmgmt-field"><input id="add-elementchgmgmt-date1-field" class="webea-main-styled-date" name="date1" placeholder="yyyy-mm-dd" value="' . $sToday . '" onblur="OnDateFieldLostFocus(\'add-elementchgmgmt-date1-field\')" ' . $sReadOnlyProp . '>';
	echo '<div style="display: none;"><img id="add-elementchgmgmt-date1-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png"></div>';
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-element-label">' . $sPerson2Desc . '</td>';
	echo '<td class="add-elementchgmgmt-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-elementchgmgmt-author2-field';
	$aAttribs['name']	= 'author2';
	$aAttribs['maxlength']	= '255';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aAuthors, $aAttribs, true);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label">' . $sDate2Desc . '&nbsp;&nbsp;</td>';
	echo '<td class="add-elementchgmgmt-field"><input id="add-elementchgmgmt-date2-field" class="webea-main-styled-date" name="date2" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'add-elementchgmgmt-date2-field\')" ' . $sReadOnlyProp . '>';
	echo '<div style="display: none;"><img id="add-elementchgmgmt-date2-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png"></div>';
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label">' . _glt('Version') . '</td>';
	echo '<td class="add-elementchgmgmt-field"><input id="add-elementchgmgmt-version-field" class="webea-main-styled-number" name="version" value="1.0" maxlength="10"></td>';
	echo '</tr>';
	}
	else
	{
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label">' . _glt('Type') . '<span class="field-label-required">&nbsp;*</span></td>';
	echo '<td class="add-elementchgmgmt-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'add-elementchgmgmt-type-field';
	$aAttribs['name']	= 'type';
	$aAttribs['maxlength']	= '12';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aTypes, $aAttribs, true);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label">' . _glt('Weight') . '</td>';
	echo '<td class="add-elementchgmgmt-field"><input id="add-elementchgmgmt-weight-field" class="webea-main-styled-number" name="weight" type="number" value="1" maxlength="10"></td>';
	echo '</tr>';
	}
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label field-label-vert-align-top">' . _glt('Description') . '</td>';
	echo '<td class="add-elementchgmgmt-field"><div id="add-elementchgmgmt-notes-div"><textarea id="add-elementchgmgmt-notes-field" name="notes" onfocus="EnsureInputFieldVisible(this)"></textarea></div></td>';
	echo '</tr>';
	if ( $sChgMgmtType !== 'risk' )
	{
	echo '<tr class="add-elementchgmgmt-line">';
	echo '<td class="add-elementchgmgmt-label field-label-vert-align-top">' . _glt('History') . '</td>';
	echo '<td class="add-elementchgmgmt-field"><div id="add-elementchgmgmt-notes-div"><textarea id="add-elementchgmgmt-history-field" name="history" onfocus="EnsureInputFieldVisible(this)"></textarea></div></td>';
	echo '</tr>';
	}
	echo '</tbody></table>';
	echo '<input type="hidden" name="guid" value="' . _h($sObjectGUID) . '">';
	echo '<input type="hidden" name="featuretype" value="' . $sChgMgmtType . '">';
	echo '<input type="hidden" name="parentname" value="' . _h($sObjectName) . '">';
	echo '<input type="hidden" name="parentimageurl" value="' . _h($sObjImageURL) . '">';
	echo '</form>';
	echo '</div>';
	echo '<div class="dialog-footer">';
	echo '<div class="dialog-buttons-container">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-apply" type="submit" onclick="OnFormRunAddElementChgMgmt(event)" value="' . _glt('Add') . '">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-ok" type="submit" onclick="OnFormRunAddElementChgMgmt(event, true)" value="' . _glt('Add') . ' & ' . _glt('Close') . '">';
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