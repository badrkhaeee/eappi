<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once __DIR__ . '/../security.php';
	require_once __DIR__ . '/../globals.php';
	SafeStartSession();
	AllowedMethods('POST');
	global $gLog;
	$accessCode = SafeGetInternalArrayParameter($_POST, 'accessCode');
	$sUserID = SafeGetInternalArrayParameter($_POST, 'username');
	$sPassword = SafeGetInternalArrayParameter($_POST, 'password');
	if  ( !isset($_SESSION['model_no']) )
	{
	$aCookieParams = session_get_cookie_params();
	setResponseCode(400, _glt('Model has not been specified'));
	if($aCookieParams['secure'])
	{
	setResponseCode(400, _glt('Unable to access secure session cookie'));
	}
	exit();
	}
	$aSettings = ParseINIFile2Array('../includes/webea_config.ini');
	$sAccessCodeFromConfig = SafeGetArrayItem2DimByName($aSettings, 'model' . $_SESSION['model_no'], 'auth_code', '');
	if (!strIsEmpty($sAccessCodeFromConfig)
	&& $sAccessCodeFromConfig !== md5('')
	&& md5($accessCode) !== $sAccessCodeFromConfig)
	{
	setResponseCode(401, 'The entered access code is incorrect!');
	exit();
	}
	$sErrorMsg	= 'Unknown login error';
	$sReadonlyModel 	= 'true';
	$sValidLicense 	= 'false';
	$sLicense	 	= '';
	$sLicenseExpiry	= '';
	$sSecurityEnabledModel = 'true';
	$sLoginGUID	= '';
	$sLoginFullName	= '';
	$sVersion	= '';
	$sPermElement	= '0';
	$sPermTest	= '0';
	$sPermResAlloc	= '0';
	$sPermMaintenance	= '0';
	$sPermProjMan	= '0';
	$_SESSION['security_enabled_model']	= 'false';
	$_SESSION['readonly_model'] = 'true';
	$_SESSION['valid_license'] 	= 'false';
	$_SESSION['login_user'] 	= '';
	$_SESSION['login_guid'] 	= '';
	$_SESSION['login_fullname'] = '';
	$_SESSION['login_perm_element']	= '0';
	$_SESSION['login_perm_test'] 	= '0';
	$_SESSION['login_perm_resalloc'] = '0';
	$_SESSION['login_perm_maintenance'] = '0';
	$_SESSION['login_perm_projman'] = '0';
	global $g_sOSLCString;
	BuildOSLCConnectionString();
	$sURL 	= $g_sOSLCString;
	$sURL 	   .= 'login/';
	if ($sUserID === '' && $sPassword === '')
	{
	$sUserID = $_SESSION['model_user'];
	$sPassword = $_SESSION['model_pwd'];
	}
	$sPayload 	= 'uid=' . $sUserID . ';pwd=' . $sPassword;
	$_SESSION['ssl_userid'] = $sUserID;
	$_SESSION['ssl_password'] = $sPassword;
	$sResponse 	= HTTPPostXMLRaw($sURL, $sPayload, $httpCode, $sErrorMsg);
	AddItemToSystemOutput('validate_login:  response=' . $sResponse);
	if ( strIsEmpty($sErrorMsg) )
	{
	include('process_login.php');
	}
	if ( !strIsEmpty($sErrorMsg) )
	{
	setResponseCode($httpCode, $sErrorMsg);
	}
?>