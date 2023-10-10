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
	$sErrorMsg = '';
	include('./data_api/send_logout.php');
	$cookieParams = session_get_cookie_params();
	setcookie(
	session_name(),
	'',
	time() - 60,
	$cookieParams["path"],
	$cookieParams["domain"],
	$cookieParams["secure"],
	$cookieParams["httponly"]);
	$_SESSION = array();
	RedirectToLogin();
?>