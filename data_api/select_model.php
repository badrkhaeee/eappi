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
	$sErrorMsg = '';
	$sModelNo = SafeGetInternalArrayParameter($_POST, 'modelNumber');
	$deviceLayout = SafeGetInternalArrayParameter($_POST, 'deviceLayout');
	if (strIsEmpty($sModelNo))
	{
	http_response_code(400);
	echo 'No model number specified';
	exit();
	}
	$sModelNameInConfig = 'model' . $sModelNo;
	$aSettings = ParseINIFile2Array('../includes/webea_config.ini');
	SafeStartSession();
	$_SESSION['model_no']	= $sModelNo;
	$_SESSION['model_name'] 	= SafeGetArrayItem2DimByName($aSettings,'model_list' , $sModelNameInConfig, '');
	$_SESSION['protocol'] 	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_protocol', 'http');
	$_SESSION['server'] 	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_server', 'localhost');
	$_SESSION['port'] 	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_port', '80');
	$_SESSION['db_alias']	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_db_alias', 'ea');
	$_SESSION['use_ssl']	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_use_ssl', 'false');
	$_SESSION['enforce_certs']	 	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_enforce_certs', 'true');
	$_SESSION['model_user']	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_model_user', '');
	$_SESSION['model_pwd']	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_model_pwd', '');
	$_SESSION['eao_access_code']	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_access_code', '');
	$_SESSION['max_communication_time']	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_max_communication_time', '30');
	$_SESSION['auto_login_windows_auth']	 	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'auto_login_windows_auth', 'false');
	SetUseSSLFlagBasedOnProtocol();
	unset($_SESSION['authorized']);
	$_SESSION['security_enabled_model']	= 'false';
	$_SESSION['readonly_model'] = 'true';
	$_SESSION['valid_license'] 	= 'false';
	$_SESSION['login_user'] 	= '';
	$_SESSION['login_guid'] 	= '';
	$_SESSION['login_access_token']	= '';
	$_SESSION['login_refresh_token'] = '';
	$_SESSION['login_fullname'] = '';
	$_SESSION['login_perm_element']	= '0';
	$_SESSION['login_perm_test'] 	= '0';
	$_SESSION['login_perm_resalloc'] = '0';
	$_SESSION['login_perm_maintenance'] = '0';
	$_SESSION['login_perm_projman'] = '0';
	$_SESSION['login_perm_creatediagram'] = '0';
	$_SESSION['login_perm_updatediagram'] = '0';
	$_SESSION['login_perm_relmatrix'] = '0';
	$_SESSION['propertylayout'] 	= '1';
	if ( $deviceLayout === 'm' )
	{
	$_SESSION['mainlayout']	= '1';
	$_SESSION['show_browser']	= '0';
	$_SESSION['show_propertiesview']= '0';
	$_SESSION['diagram_zoom']= '75';
	$_SESSION['mail_preview'] = 'hide';
	}
	elseif ( $deviceLayout === 't' )
	{
	$_SESSION['mainlayout']	= '2';
	$_SESSION['show_browser']	= '1';
	$_SESSION['show_propertiesview']= '0';
	$_SESSION['diagram_zoom']= '100';
	$_SESSION['mail_preview'] = 'right';
	}
	else
	{
	$_SESSION['mainlayout']	= '2';
	$_SESSION['show_browser']	= '1';
	$_SESSION['show_propertiesview']= '1';
	$_SESSION['diagram_zoom']= '100';
	$_SESSION['mail_preview'] = 'right';
	}
	BuildOSLCConnectionString();
	$sOSLC_URL = $g_sOSLCString . 'sp/';
	$xmlDoc = HTTPGetXML($sOSLC_URL);
	if((!IsHTTPSuccess(http_response_code())) ||
	(!strIsEmpty($sErrorMsg)) ||
	($xmlDoc == null))
	{
	exit();
	}
	$modelDetails = array();
	$xPath = new DomXPath($xmlDoc);
	$xPath->registerNamespace('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
	$xPath->registerNamespace('oslc', 'http://open-services.net/ns/core#');
	$xPath->registerNamespace('dcterms', 'http://purl.org/dc/terms/');
	$xPath->registerNamespace('ss', 'http://www.sparxsystems.com.au/oslc_am#');
	$modelDetails['modelNumber'] = $sModelNo;
	$modelDetails['modelNameInConfig'] = $sModelNameInConfig;
	$modelDetails['securityEnabled'] = $xPath->evaluate('string(oslc:ServiceProvider/dcterms:publisher/oslc:Publisher/ss:securityenabledmodel)') == 'true';
	$restrictToSSO = $xPath->evaluate('string(oslc:ServiceProvider/dcterms:publisher/oslc:Publisher/ss:restricttossousers)') == 'true';
	$modelDetails['credentialsSetInConfig'] = !strIsEmpty(SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_model_user', '')) && !$restrictToSSO;
	$modelDetails['allowBasicAuthentication'] = $modelDetails['securityEnabled'] && !$restrictToSSO && !$modelDetails['credentialsSetInConfig'];
	$modelDetails['allowWindowsAuthentication'] = $xPath->evaluate('string(oslc:ServiceProvider/dcterms:publisher/oslc:Publisher/ss:windowsauthenticationenabled)') == 'true';
	$modelDetails['auto_login_windows_auth']	 	= SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'auto_login_windows_auth', 'false');
	$sOpenIDAuthorisationEndpoint = $xPath->evaluate('string(oslc:ServiceProvider/oslc:oauthConfiguration/oslc:OAuthConfiguration/oslc:authorizationURI/@rdf:resource)');
	$sOpenIDClientID = $xPath->evaluate('string(oslc:ServiceProvider/oslc:oauthConfiguration/oslc:OAuthConfiguration/ss:clientid)');
	$sOpenIDScope = $xPath->evaluate('string(oslc:ServiceProvider/oslc:oauthConfiguration/oslc:OAuthConfiguration/ss:scope)');
	$modelDetails['allowOpenIDAuthentication'] = false;
	$modelDetails['openIDURL'] = '';
	if (!$modelDetails['credentialsSetInConfig'])
	{
	if (!strIsEmpty($sOpenIDAuthorisationEndpoint) && !strIsEmpty($sOpenIDClientID))
	{
	$modelDetails['allowOpenIDAuthentication'] = true;
	$sOpenIDRedirectURI = GetOpenIDRedirectURL();
	$openIDState = CreateGUID();
	$_SESSION['openIDState'] = $openIDState;
	$modelDetails['openIDURL'] = $sOpenIDAuthorisationEndpoint . '?response_type=code&client_id=' . $sOpenIDClientID . '&scope=' . $sOpenIDScope . '&redirect_uri=' . $sOpenIDRedirectURI . '&state=' . $openIDState;
	}
	}
	$modelDetails['allowBlankPassword'] = SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'login_allow_blank_pwd', 'false') == 'true';
	$modelDetails['protocol'] = SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'sscs_protocol', 'http');
	$modelDetails['requiresAccessCode'] = !$restrictToSSO && SafeGetArrayItem2DimByName($aSettings, $sModelNameInConfig, 'auth_code', '') !== md5('');
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($modelDetails);
?>