<!DOCTYPE html>
<html lang="en">
<head>
	<title>Cloud Configuration</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link type="text/css" rel="stylesheet" href="config.css?v=<?php echo filemtime('config.css') ; ?>" />
	<link type="text/css" rel="stylesheet" href="jquery.datepick.blue.css" />
	<link rel="shortcut icon" href="./favicon.ico?<?php echo filemtime('favicon.ico') ?>" />
	<script src="config.js"></script>
	<script src="jquery.min.js"></script>
	<script src="jquery.plugin.js"></script>
	<script src="jquery.datepick.js"></script>
	<noscript>
	<h1>JavaScript not found</h1>
	The Pro Cloud Web Configuration Client requires JavaScript.<br><br>
	Please enable JavaScript then reload this page.
	<style>div { display:none; } body { padding:16px;}</style>
	</noscript>
</head>
<body>
<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once __DIR__ . '/modulecheck.php';
	require_once('globals.php');
	SafeStartSession();
	$sProtocol = 'http';
	$sServerName = 'localhost';
	$sPort = '804';
	$sEnforceCerts = 'true';
	require_once('settings.php');
	$aValidParams = ['server', 'used_logout'];
	foreach ($_GET as $sParam=>$sParamVal)
	{
	if (!in_array($sParam, $aValidParams))
	{
	echo '<div class="config-request-error">';
	echo '<div style="display: inline-block;">' . '<img alt="" class="config-login-alert" style="vertical-align: top;" src="images/alert.png">';
	echo '</div><div class="login-error-heading">Invalid URL parameter: '. $sParam;
	echo '</div>';
	echo '</div>';
	exit();
	}
	}
	$param = SafeGetInternalArrayParameter($_GET, 'server');
	$session_param = SafeGetInternalArrayParameter($_SESSION, 'server_param');
	if (strIsEmpty($param) && !strIsEmpty($session_param))
	{
	$param = $session_param;
	}
	if (!strIsEmpty($param))
	{
	$_SESSION['server_param'] = $param;
	$aParams = explode(':', $param);
	if (count($aParams) === 2)
	{
	$sServerName = $aParams[0];
	$sPort = $aParams[1];
	}
	else if(count($aParams) === 3)
	{
	$sProtocol = $aParams[0];
	$sServerName = $aParams[1];
	$sServerName = str_replace('//' , '', $sServerName);
	$sPort = $aParams[2];
	}
	else
	{
	echo '<div class="config-request-error">';
	echo '<div style="display: inline-block;">' . '<img alt="" class="config-login-alert" style="vertical-align: top;" src="images/alert.png">';
	echo '</div><div class="login-error-heading">Invalid \'server\' parameter';
	echo '</div>';
	echo '</div>';
	exit();
	}
	}
	$sPCS_URL = $sProtocol . '://' . $sServerName . ':' . $sPort;
	$_SESSION['pcs_url'] = $sPCS_URL;
	$_SESSION['pcs_port'] = $sPort;
	$_SESSION['enforce_certs'] = $sEnforceCerts;
	$bViaIndex = true;
	if (!isset($_SESSION['pcsa']))
	{
	if (isset($_GET['used_logout']))
	{
	$bRequiresLogin = 'true';
	}
	else
	{
	$sURL = $sPCS_URL.'/config/login/';
	$sPostError = '';
	$sPWD = '';;
	$sPWDEnc = EncryptDecrypt($sPWD, true);
	$sPostBody = '';
	$sPostBody .= '<login>';
	$sPostBody .= '<uid>admin</uid>';
	$sPostBody .= '<pwd>'.$sPWDEnc.'</pwd>';
	$sPostBody .= '</login>';
	$xmlDoc = HTTPPostXMLRaw($sURL, $sPostBody, $sPostError);
	$sErrorDetails ='';
	if(!strIsEmpty($sPostError))
	{
	$sWarningIcon = '<img alt="" class="config-login-alert" style="vertical-align: top;" src="images/alert.png">';
	if($sPostError === 'Request Error: invalid connection configuration')
	{
	$sPostError = 'Request Error: Protocol and Port mismatch';
	$sErrorDetails = 'Confirm that port ' . $sPort . ' is configured to use protocol ' . $sProtocol;
	}
	else if ($sPostError === 'Request Error: SSL certificate problem: self signed certificate')
	{
	$sErrorDetails = 'To allow self signed certificates, set the sEnforceCerts option to \'false\'';
	}
	else if ($sPostError === 'Request Error: unknown protocol error')
	{
	$sPostError = 'Request Error: Protocol and Port mismatch';
	$sErrorDetails = 'Confirm that port ' .$sPort . ' is configured to use protocol ' . $sProtocol;
	}
	else if ($sPostError === 'Request Error: Server could not be found')
	{
	$sPostError = 'Request Error: A Pro Cloud Server could not be found listening on the defined server and port!';
	}
	echo '<div class="config-request-error">';
	echo '<div style="display: inline-block;">' .$sWarningIcon;
	echo '</div><div class="login-error-heading">'. $sPostError;
	echo '<br><br>';
	echo 'PCS URL: '. $sPCS_URL ;
	echo '<br><br>';
	echo $sErrorDetails;
	echo '</div>';
	echo '</div>';
	exit();
	}
	if ((strpos($xmlDoc, '<return-code>0</return-code>') !== false))
	{
	$_SESSION['pcsa'] = $sPWDEnc;
	$bRequiresLogin = 'false';
	}
	else
	{
	$bRequiresLogin = 'true';
	}
	}
	}
	else
	{
	$bRequiresLogin = 'false';
	}
	echo '<div class="main-background">';
	echo '<div class="main-header-margin">';
	echo '<div class="main-header">';
	echo '<img src="images/logo-full.png" class="config-logo" alt="">';
	echo '<div id="main-header-label">Pro Cloud Server Configuration</div>';
	echo '<div id="config-logout" onclick="onClickButton(\'logout\',\'logout\')"><img alt="" class="config-logout-icon" src="images/logout.png"><div id="config-logout-text">Logout</div></div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="main-page-overlay">&nbsp;</div>';
	echo '<div>';
	echo '<div id="webea-success-message">';
	echo '<div class="webea-success-message-text" id="webea-success-message-text"></div>';
	echo '</div>';
	echo '<div id="webea-error-message">';
	echo '<div class="webea-error-message-text" id="webea-error-message-text"></div>';
	echo '</div>';
	echo '<div id="webea-messagebox-dialog">';
	echo '<div class="webea-messagebox-title"><div id="webea-messagebox-title-text">' . _glt('WebEA error') . '</div><input class="webea-dialog-close-button" type="button" onclick="OnClickClosePopupDialog(\'#webea-messagebox-dialog\')"/></div>';
	echo '<div class="webea-messagebox-body">';
	echo '<div class="webea-messagebox-line"><div class="webea-messagebox-image"><img src="images/spriteplaceholder.png" class="mainsprite-warning" alt=""></div></div>';
	echo '<div id="webea-messagebox-line-message"><div class="webea-messagebox-line-value" id="webea-messagebox-line-value-message"></div> </div>';
	echo '<div class="webea-about-line-cancel">';
	WriteButton('OK','','button button-ok','onclick="OnClickClosePopupDialog(\'#webea-messagebox-dialog\')"');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-prompt-dialog">';
	echo '<div class="webea-messagebox-title"><div id="webea-prompt-title-text">' . _glt('WebEA error') . '</div><input class="webea-dialog-close-button" type="button" onclick="OnClickClosePopupDialog(\'#webea-prompt-dialog\')"/></div>';
	echo '<div class="webea-messagebox-body">';
	echo '<div id="webea-prompt-line-message"><div class="webea-messagebox-line-value" id="webea-prompt-line-value-message"></div> </div>';
	echo '<div id="webea-prompt-buttons">';
	WriteButton('OK','','button button-ok','onclick="OnClickClosePopupDialog(\'#webea-messagebox-dialog\')"');
	WriteButton('Cancel','','button button-ok','onclick="OnClickClosePopupDialog(\'#webea-prompt-dialog\')"');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div class="main-contents-margin">';
	echo '<div class="main-contents">';
	include('main-contents.php');
	echo '</div>';
	echo '</div>';
	echo '</div>';
?>
<script>
	window.addEventListener('popstate', function(event) {
	onIndexPopState(event);
	}, false);
	window.addEventListener('beforeunload', function(event) {
	});
</script>
</body>
</html>