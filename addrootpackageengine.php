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
	$sName  	= SafeGetInternalArrayParameter($_POST, 'name');
	$sObjectType 	= SafeGetInternalArrayParameter($_POST, 'objecttype');
	$sParentGUID 	= SafeGetInternalArrayParameter($_POST, 'parentguid');
	$sParentName	= SafeGetInternalArrayParameter($_POST, 'parentname');
	$sIconStyle	= SafeGetInternalArrayParameter($_POST, 'iconstyle');
	$sNotes 	= SafeGetInternalArrayParameter($_POST, 'notes');
	$sAuthor 	= SafeGetInternalArrayParameter($_SESSION, 'login_fullname');
	$sLoginGUID 	= SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	$sMissingField = '';
	if (strIsEmpty($sObjectType))
	{
	$sMissingField .= _glt('Object type') . ', ';
	}
	if (strIsEmpty($sName))
	{
	$sMissingField .= _glt('Name') . ', ';
	}
	if ( $sObjectType==='view' && strIsEmpty($sParentGUID) )
	{
	$sMissingField .= _glt('Container') . ', ';
	}
	if ( $sObjectType==='view' && strIsEmpty($sIconStyle) )
	{
	$sMissingField .= _glt('Icon Style') . ', ';
	}
	if (!strIsEmpty($sMissingField))
	{
	$sMissingField = substr($sMissingField, 0, strlen($sMissingField)-2 );
	$sErrorMsg = str_replace('%FIELDS%', $sMissingField, _glt('The mandatory fields missing'));
	setResponseCode(400, $sErrorMsg);
	return $sErrorMsg;
	}
	if ($sIconStyle!=='1' && $sIconStyle!=='2' && $sIconStyle!=='3' && $sIconStyle!=='4' & $sIconStyle!=='5')
	{
	$sIconStyle = '0';
	}
	$sReturn = '';
	$sNotes	= ConvertHTMLToEANote($sNotes);
	$xmlRespDoc = null;
	include('./data_api/add_rootpackage.php');
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$sSuccessAttrib = GetOSLCSuccess($xmlRespDoc);
	if (strpos($sSuccessAttrib, '/oslc/am/completeresource/')!==false ||
	strpos($sSuccessAttrib, '/oslc/am/resource/')!==false)
	{
	$sName	= _h($sName);
	if ( $sObjectType==='modelroot' )
	{
	$sParentName = 'the Model';
	}
	$sParentName	= _h($sParentName);
	$sType = _glt('Root Node');
	if ( $sObjectType === 'view')
	{
	$sType = 'View';
	}
	$sReturn = 'success: ' . str_replace(array('%OBJTYPE%','%OBJNAME%','%PARENT%'), array($sType, $sName, $sParentName), _glt('Object added'));
	}
	}
	else
	{
	$sReturn = $sOSLCErrorMsg;
	}
	echo $sReturn;
?>