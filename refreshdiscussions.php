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
	$sObjectGUIDEnc = SafeGetInternalArrayParameter($_POST, 'objectguid');
	$sObjectGUID 	= urldecode($sObjectGUIDEnc);
	$sResType 	= GetResTypeFromGUID($sObjectGUID);
	$aDiscussions 	= array();
	include('./data_api/get_discussions.php');
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	include('./propertiesdiscussions.php');
	}
	else
	{
	echo 'error: ' . $sOSLCErrorMsg;
	}
?>