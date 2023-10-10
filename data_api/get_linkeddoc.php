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
	CheckAuthorisation();
	$sObjectGUID 	= SafeGetInternalArrayParameter($_POST, 'objectguid');
	$sPassword 	= SafeGetInternalArrayParameter($_POST, 'hyper');
	if (strIsEmpty($sObjectGUID))
	{
	setResponseCode(400);
	exit();
	}
	BuildOSLCConnectionString();
	$sOSLC_URL 	= $g_sOSLCString . "linkeddocument/" . $sObjectGUID . "/";
	$sPostData 	= 'pwd=' . $sPassword;
	$sLoginGUID  = SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	if ( !strIsEmpty($sLoginGUID) )
	{
	$sPostData .= ';useridentifier=' . $sLoginGUID;
	}
	$sErrorMsg 	= '';
	$xmlRespDoc = null;
	$xmlRespDoc = HTTPPostXML($sOSLC_URL, $sPostData);
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	if ($xmlRespDoc != null)
	{
	ExtractSystemOutputDetails($xmlRespDoc);
	$xnRoot = $xmlRespDoc->documentElement;
	$xnFC = $xnRoot->firstChild;
	if ($xnFC != null && $xnFC->childNodes != null)
	{
	if ( GetOSLCError($xnFC) )
	{
	foreach ($xnFC->childNodes as $xnC)
	{
	GetXMLNodeValue($xnC, 'ss:content', $sHTMLDoc);
	}
	}
	}
	}
	}
	else
	{
	$sHTMLDoc = '<div class="search-message-error">' . $sOSLCErrorMsg . '</div>';
	}
?>