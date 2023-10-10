<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	ob_start();
	require_once __DIR__ .  '/../../security.php';
	require_once __DIR__ . '/../../globals.php';
	SafeStartSession();
	AllowedMethods('POST');
	$sErrorMsg = '';
	$sUserID = '';
	if  ( !isset($_SESSION['model_no']) )
	{
	setResponseCode(400, _glt('Model has not been specified'));
	exit();
	}
	$web_page_parent_ntlm = true;
	$aSettings = ParseINIFile2Array('../../includes/webea_config.ini');
	$sModelNameInConfig = 'model' . $_SESSION['model_no'];
	global $g_sOSLCString;
	BuildOSLCConnectionString();
	$sOSLC_URL = $g_sOSLCString . 'login/';
	$sBody = 'sso=NTLM;';
	$sResponse = HTTPPostXMLRaw($sOSLC_URL, $sBody, $httpCode, $sErrorMsg);
	if ( strIsEmpty($sErrorMsg) )
	{
	include('../process_login.php');
	}
	unset($web_page_parent_ntlm);
	if ( !strIsEmpty($sErrorMsg) )
	{
	if ($httpCode == 422)
	{
	setResponseCode(401, $sErrorMsg);
	}
	else
	{
	setResponseCode($httpCode, $sErrorMsg);
	}
	}
?>