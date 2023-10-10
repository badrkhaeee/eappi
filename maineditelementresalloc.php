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
	$sParentGUID = SafeGetInternalArrayParameter($_POST, 'guid');
	$sResource = SafeGetInternalArrayParameter($_POST, 'resource');
	$sRole	= SafeGetInternalArrayParameter($_POST, 'role');
	$sResourceID	= SafeGetInternalArrayParameter($_POST, 'resourceid');
	$aObjectDetails	= array();
	$aResAllocDetails = array();
	$aRoles	= array();
	$aAuthors	= array();
	$sOSLCErrorMsg	= '';
	include('./data_api/get_elementresalloc.php');
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	$sObjectName 	= SafeGetArrayItem1Dim($aObjectDetails, 'name');
	$sObjImageURL	= SafeGetArrayItem1Dim($aObjectDetails, 'imageurl');
	$sObjectResType = SafeGetArrayItem1Dim($aObjectDetails, 'restype');
	$sObjectName 	= GetPlainDisplayName($sObjectName);
	$sEditResource	= SafeGetArrayItem1Dim($aResAllocDetails, 'resource');
	$sEditRole 	= SafeGetArrayItem1Dim($aResAllocDetails, 'role');
	$sStartDate	= SafeGetArrayItem1Dim($aResAllocDetails, 'startdate');
	$sEndDate	= SafeGetArrayItem1Dim($aResAllocDetails, 'enddate');
	$sPercentComplete = SafeGetArrayItem1Dim($aResAllocDetails, 'percentcomplete');
	$sExpectedTime	= SafeGetArrayItem1Dim($aResAllocDetails, 'expectedtime');
	$sAllocatedTime	= SafeGetArrayItem1Dim($aResAllocDetails, 'allocatedtime');
	$sExpendedTime	= SafeGetArrayItem1Dim($aResAllocDetails, 'expendedtime');
	$sNotes 	= SafeGetArrayItem1Dim($aResAllocDetails, 'notes');
	$sHistory	= SafeGetArrayItem1Dim($aResAllocDetails, 'history');
	$sReadOnlyProp = '';
	if ( IsMobileBrowser() )
	{
	$sReadOnlyProp = 'readonly="readonly"';
	}
	$sRichEditCtrlsCSV = '\'edit-elementresalloc-notes-field,edit-elementresalloc-history-field\'';
	$sDatePickerCtrlsCSV = '\'edit-elementresalloc-startdate-field|edit-elementresalloc-startdate-img,edit-elementresalloc-enddate-field|edit-elementresalloc-enddate-img\'';
	if(strIsEmpty($sParentGUID))
	{
	exit();
	}
	echo '<div class="default-dialog" style="max-height:642px;">';
	echo '<div class="dialog-header">';
	echo '<div class="dialog-header-title">';
	echo '  <img alt="" src="images/spriteplaceholder.png" class="propsprite-resallocedit" onload="OnLoad_SetupSpecialCtrls(' . $sRichEditCtrlsCSV . ',' . $sDatePickerCtrlsCSV . ')"> ';
	echo    _glt('Edit resource allocation for') . '&nbsp;<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sObjImageURL) . '">';
	echo '  <div class="objectname-in-header">' . _h($sObjectName) . '</div>';
	echo '</div>';
	echo '<button class="dialog-header-close-button" onclick="OnClickCloseDialogButton()"><div class="close-icon button-icon icon16"></div></button>';
	echo '</div>';
	echo '<div class="dialog-body">';
	echo '<form class="edit-elementresalloc-form" role="form" onsubmit="OnFormRunEditElementResAlloc(event)">';
	echo '<table id="edit-elementresalloc-table"><tbody>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label">' . _glt('Resource') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="edit-elementresalloc-field">';
	$aAttribs	= array();
	$aAttribs['id']	= 'edit-elementresalloc-resource-field';
	$aAttribs['name']	= 'resource';
	$aAttribs['maxlength']	= '255';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aAuthors, $aAttribs, true, $sEditResource);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label">' . _glt('Role') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="edit-elementresalloc-field">';
	$aAttribs['id']	= 'edit-elementresalloc-role-field';
	$aAttribs['name']	= 'role';
	$aAttribs['maxlength']	= '255';
	$aAttribs['class']	= 'webea-main-styled-combo';
	echo BuildHTMLComboFromArray($aRoles, $aAttribs, true, $sEditRole);
	echo '</td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label">' . _glt('Start date') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="edit-elementresalloc-field"><input id="edit-elementresalloc-startdate-field" class="webea-main-styled-date" name="startdate" value="' . _h($sStartDate) . '" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'edit-elementresalloc-startdate-field\')"' . _h($sReadOnlyProp) . '>';
	echo '<div style="display: none;"><img id="edit-elementresalloc-startdate-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png"></div>';
	echo '</td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label">' . _glt('End date') . '<span class="field-label-required"> *</span></td>';
	echo '<td class="edit-elementresalloc-field"><input id="edit-elementresalloc-enddate-field" class="webea-main-styled-date" name="enddate" value="' . _h($sEndDate) . '" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'edit-elementresalloc-enddate-field\')"' . _h($sReadOnlyProp) . '>';
	echo '<div style="display: none;"><img id="edit-elementresalloc-enddate-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png"></div>';
	echo '</td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label">' . _glt('Percent Complete') . '&nbsp;</td>';
	echo '<td class="edit-elementresalloc-field"><input id="edit-elementresalloc-percentcomp-field" class="webea-main-styled-number" name="percentcomp" type="number" value="' . _h($sPercentComplete) . '" min="0" max="100" step="1" maxlength="3"></td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label"><label>' . _glt('Expected') . '</label></td>';
	echo '<td class="edit-elementresalloc-field"><input id="edit-elementresalloc-expectedtime-field" class="webea-main-styled-number" name="expectedtime"	type="number" value="' . _h($sExpectedTime) . '" min="0" step="1" maxlength="8"></td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label"><label>' . _glt('Allocated') . '</label></td>';
	echo '<td class="edit-elementresalloc-field"><input id="edit-elementresalloc-allocatedtime-field" class="webea-main-styled-number" name="allocatedtime" type=" number" value="' . _h($sAllocatedTime) . '" min="0" step="any" maxlength="8"></td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label"><label>' . _glt('Actual') . '</label></td>';
	echo '<td class="edit-elementresalloc-field"><input id="edit-elementresalloc-expendedtime-field" class="webea-main-styled-number" name="expendedtime" type="number" value="' . _h($sExpendedTime) . '" min="0" step="1" maxlength="8"></td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label field-label-vert-align-top">' . _glt('Description') . '</td>';
	echo '<td class="edit-elementresalloc-field"><div id="edit-elementresalloc-notes-div"><textarea id="edit-elementresalloc-notes-field" name="notes" onfocus="EnsureInputFieldVisible(this)">' . ConvertEANoteToHTML($sNotes) . '</textarea></div></td>';
	echo '</tr>';
	echo '<tr class="edit-elementresalloc-line">';
	echo '<td class="edit-elementresalloc-label field-label-vert-align-top"><label>' . _glt('History') . '</label></td>';
	echo '<td class="edit-elementresalloc-field"><div id="edit-elementresalloc-history-div"><textarea id="edit-elementresalloc-history-field" name="history" onfocus="EnsureInputFieldVisible(this)">' . ConvertEANoteToHTML($sHistory) . '</textarea></div></td>';
	echo '</tr>';
	echo '</tbody></table>';
	echo '<div id="edit-elementresalloc-message"' . (strIsEmpty( $sOSLCErrorMsg) ? '>' : ' class="add-elementresalloc-message-error">'. $sOSLCErrorMsg ) . '</div>';
	echo '<input type="hidden" name="key1" value="' . _h($sParentGUID) . '">';
	echo '<input type="hidden" name="key2" value="' . _h($sResource) . '">';
	echo '<input type="hidden" name="key3" value="' . _h($sRole) . '">';
	echo '<input type="hidden" name="resourceid" value="' . _j($sResourceID) . '">';
	echo '<input type="hidden" name="guid" value="' . _h($sParentGUID) . '">';
	echo '<input type="hidden" name="parentname" value="' . _h($sObjectName) . '">';
	echo '<input type="hidden" name="parentimageurl" value="' . _h($sObjImageURL) . '">';
	echo '</form>';
	echo '</div>';
	echo '<div class="dialog-footer">';
	echo '<div class="dialog-buttons-container">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-ok" type="submit" onclick="OnFormRunEditElementResAlloc(event, true)" value="' . _glt('Save') . ' & ' . _glt('Close') . '">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-close" type="submit" onclick="OnClickCloseDialogButton()" value="' . _glt('Close') . '">';
	echo '</div>';
	echo '</div>';
	echo '</div>';
?>
<script>
var unsaved = false;
$(":input").change(function(){
    unsaved = true;
});
</script>