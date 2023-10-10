<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once('globals.php');
	SafeStartSession();
	$sPCS_URL = SafeGetInternalArrayParameter($_SESSION , 'pcs_url');
	if (strIsEmpty($sPCS_URL))
	{
	echo 'Session expired. Click <a href="" onclick="onClickButton(\'logout\',\'logout\')">here</a> to login';
	exit;
	}
	if (isset($bRequiresLogin) && ($bRequiresLogin === 'true'))
	{
	$sLoadPage = 'login.php';
	}
	else
	{
	$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	if ($pageWasRefreshed)
	{
	$sLoadPage = SafeGetInternalArrayParameter($_POST, 'load_page','');
	}
	else
	{
	$sLoadPage = SafeGetInternalArrayParameter($_POST, 'load_page','home.php');
	}
	}
	echo '<div id="main-busy-loader1"><img src="images/mainwait.gif" alt="" class="main-spinner" height="300" width="300"></div>';
	if ($sLoadPage === 'null')
	{
	$sLoadPage = 'home.php';
	}
	$aAllowedPages = [
	'allocate-tokens.php',
	'change-password.php',
	'dbmanager-add.php',
	'dbmanager-edit.php',
	'dbmanager-select-type.php',
	'floating-license-add.php',
	'floating-licenses.php',
	'fls-group-add.php',
	'fls-groups.php',
	'home.php',
	'integration-add-provider.php',
	'integration-bindings.php',
	'integrations.php',
	'log-view.php',
	'login.php',
	'logs.php',
	'main-contents.php',
	'pcs-config.php',
	'pcs-license-add.php',
	'pcs-license-req-file.php',
	'pcs-licenses.php',
	'pcs-new-license-request.php',
	'pcs-renew-license-request.php',
	'port-add.php',
	'ports.php',
	'settings.php',
	'update-settings.php'
	];
	if(!strIsEmpty($sLoadPage) && in_array($sLoadPage, $aAllowedPages))
	{
	include($sLoadPage);
	}
?>