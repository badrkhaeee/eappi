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
	$sObjectGUID = SafeGetInternalArrayParameter($_POST, 'objectguid');
	$aObjectDetails	= array();
	include('./data_api/get_element.php');
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$sObjectName 	= SafeGetArrayItem1Dim($aObjectDetails, 'name');
	$sObjectName 	= GetPlainDisplayName($sObjectName);
	$sNotes	= SafeGetArrayItem1Dim($aObjectDetails, 'notes');
	$sImageURL	= SafeGetArrayItem1Dim($aObjectDetails, 'imageurl');
	echo '<div class="default-dialog" style="max-height:500px;">';
	echo '<div class="dialog-header">';
	echo '<div class="dialog-header-title">';
	echo '<img alt="" src="images/spriteplaceholder.png" hidden onload="OnLoad_SetupSpecialCtrls(\'edit-elementnote-notes-field\',\'\')"> ';
	echo   _glt('Edit note for') . '&nbsp;<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '">';
	echo   '<div class="objectname-in-header">' . _h($sObjectName) . '</div>';
	echo '</div>';
	echo '<button class="dialog-header-close-button" onclick="OnClickCloseDialogButton()"><div class="close-icon button-icon icon16"></div></button>';
	echo '</div>';
	echo '<form style="display:initial;" class="edit-elementnote-form" role="form" onsubmit="OnFormRunEditElementNote(event)">';
	echo '<div class="dialog-body">';
	echo '<div id="edit-elementnote-form">';
	echo '<div id="edit-elementnote-notes-div"><textarea id="edit-elementnote-notes-field" name="notes" onfocus="EnsureInputFieldVisible(this)">' . ConvertEANoteToHTML($sNotes) . '</textarea></div>';
	echo '<input type="hidden" name="parentguid" value="' . _h($sObjectGUID) . '">';
	echo '<input type="hidden" name="parentname" value="' . _h($sObjectName) . '">';
	echo '<input type="hidden" name="parentimageurl" value="' . _h($sImageURL) . '">';
	echo '</div>';
	echo '</div>';
	echo '<div class="dialog-footer">';
	echo '<div><input class="webea-main-styled-button edit-elementnote-submit" type="submit" value="' . _glt('Save') . '"></div>';
	echo '</div>';
	echo '</form>';
	echo '</div>';
	}
?>
<script>
var unsaved = false;
$(":input").change(function(){
    unsaved = true;
});
</script>