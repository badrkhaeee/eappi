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
	$sReturn = '';
	$sVariableName = SafeGetInternalArrayParameter($_GET, 'varname');
	if ($sVariableName === 'default_diagram')
	{
	$sReturn = SafeGetInternalArrayParameter($_SESSION, 'default_diagram');
	}
	elseif ($sVariableName === 'timer_check')
	{
	$sReturn = 'false&';
	$sAuth = SafeGetInternalArrayParameter($_SESSION, 'authorized');
	$sModelNo = SafeGetInternalArrayParameter($_SESSION, 'model_no');
	$sReturn = $sAuth . '&' . $sModelNo;
	}
	echo $sReturn;
?>