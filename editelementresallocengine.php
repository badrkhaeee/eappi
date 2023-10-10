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
	$sKey1  	= SafeGetInternalArrayParameter($_POST, 'key1');
	$sKey2  	= SafeGetInternalArrayParameter($_POST, 'key2');
	$sKey3  	= SafeGetInternalArrayParameter($_POST, 'key3');
	$sResource 	= SafeGetInternalArrayParameter($_POST, 'resource');
	$sRole	= SafeGetInternalArrayParameter($_POST, 'role');
	$sStartDate	= SafeGetInternalArrayParameter($_POST, 'startdate');
	$sEndDate	= SafeGetInternalArrayParameter($_POST, 'enddate');
	$sGUID	= SafeGetInternalArrayParameter($_POST, 'guid');
	$sPercentComp 	= SafeGetInternalArrayParameter($_POST, 'percentcomp');
	$sExpectedTime 	= SafeGetInternalArrayParameter($_POST, 'expectedtime');
	$sAllocTime	= SafeGetInternalArrayParameter($_POST, 'allocatedtime');
	$sExpendedTime 	= SafeGetInternalArrayParameter($_POST, 'expendedtime');
	$sNotes 	= SafeGetInternalArrayParameter($_POST, 'notes');
	$sHistory	= SafeGetInternalArrayParameter($_POST, 'history');
	$sParentName	= SafeGetInternalArrayParameter($_POST, 'parentname');
	$sParentImageURL= SafeGetInternalArrayParameter($_POST, 'parentimageurl');
	$sLoginGUID	= SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	$sMissingField = '';
	if ($sResource==='[blank]')
	$sResource = '';
	if ($sRole==='[blank]')
	$sRole = '';
	if (strIsEmpty($sResource))
	$sMissingField .= _glt('Resource') . ', ';
	if (strIsEmpty($sRole))
	$sMissingField .= _glt('Role') . ', ';
	if (strIsEmpty($sStartDate))
	$sMissingField .= _glt('Start Date') . ', ';
	if (strIsEmpty($sEndDate))
	$sMissingField .= _glt('End Date') . ', ';
	if (strIsEmpty($sGUID))
	$sMissingField .= _glt('Element ID') . ', ';
	if (!strIsEmpty($sMissingField))
	{
	$sMissingField = substr($sMissingField, 0, strlen($sMissingField)-2 );
	$sErrorMsg = str_replace('%FIELDS%', $sMissingField, _glt('The mandatory fields missing'));
	setResponseCode(400, $sErrorMsg);
	return $sErrorMsg;
	}
	if ( !strIsEmpty($sStartDate) )
	{
	if (!IsDateValid($sStartDate))
	{
	$sErrorMsg = str_replace('%FIELD%', _glt('Start Date'), _glt('Invalid date value entered for FIELD'));
	setResponseCode(400, $sErrorMsg);
	return $sErrorMsg;
	}
	}
	if ( !strIsEmpty($sEndDate) )
	{
	if (!IsDateValid($sEndDate))
	{
	$sErrorMsg = str_replace('%FIELD%', _glt('End Date'), _glt('Invalid date value entered for FIELD'));
	setResponseCode(400, $sErrorMsg);
	return $sErrorMsg;
	}
	}
	$sNotes	= ConvertHTMLToEANote($sNotes);
	$sHistory	= ConvertHTMLToEANote($sHistory);
	$sReturn 	= '';
	$xmlRespDoc = null;
	include('./data_api/update_elementresalloc.php');
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$sSuccessAttrib = GetOSLCSuccess($xmlRespDoc);
	if (strpos($sSuccessAttrib, '/oslc/am/resourceallocation/')!==false)
	{
	$sResource	= _h($sResource);
	$sParentName	= _h($sParentName);
	$sParentDetails = '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sParentImageURL) . '"> ' . $sParentName;
	$sReturn = 'success: ' . str_replace(array('%RESNAME%','%OBJNAME%'), array($sResource, $sParentDetails), _glt('Resource allocation updated'));
	}
	}
	else
	{
	$sReturn = $sOSLCErrorMsg;
	}
	echo $sReturn;
?>