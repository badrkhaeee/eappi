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
	AllowedMethods('POST');
	CheckAuthorisation();
	$sGUID	= SafeGetInternalArrayParameter($_POST, 'parentguid');
	$sNotes 	= SafeGetInternalArrayParameter($_POST, 'notes');
	$sParentName	= SafeGetInternalArrayParameter($_POST, 'parentname');
	$sParentImageURL= SafeGetInternalArrayParameter($_POST, 'parentimageurl');
	$sLoginGUID	= SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	$sMissingField = '';
	if (strIsEmpty($sGUID))
	$sMissingField .= _glt('Element ID') . ', ';
	if (!strIsEmpty($sMissingField))
	{
	$sMissingField = substr($sMissingField, 0, strlen($sMissingField)-2 );
	$sErrorMsg = str_replace('%FIELDS%', $sMissingField, _glt('The mandatory fields missing'));
	setResponseCode(400, $sErrorMsg);
	return $sErrorMsg;
	}
	$sNotes	= ConvertHTMLToEANote($sNotes);
	$sReturn = '';
	$xmlRespDoc = null;
	include('./data_api/update_elementnote.php');
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$sSuccessAttrib = GetOSLCSuccess($xmlRespDoc);
	if (strpos($sSuccessAttrib, '/oslc/am/completeresource/')!==false ||
	strpos($sSuccessAttrib, '/oslc/am/resource/')!==false)
	{
	$sParentName	= _h($sParentName);
	$sParentDetails = '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sParentImageURL) . '"> ' . $sParentName;
	$sReturn = 'success: ' . str_replace('%OBJNAME%', $sParentDetails, _glt('Note updated'));
	}
	}
	else
	{
	$sReturn = $sOSLCErrorMsg;
	}
	echo $sReturn;
?>