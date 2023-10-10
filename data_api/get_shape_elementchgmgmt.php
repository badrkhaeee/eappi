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
	BuildOSLCConnectionString();
	if($sChgMgmtType === 'feature')
	$sChgMgmtType = 'mfeature';
	$sOSLC_URL 	= $g_sOSLCString . 'rs/cf' . $sChgMgmtType . '/';
	$aAuthors	= array();
	$aStatuses	= array();
	$aPriorities= array();
	$aTypes	= array();
	if ( !strIsEmpty($sOSLC_URL) )
	{
	$sParas = '';
	$sLoginGUID  = SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	AddURLParameter($sParas, 'useridentifier', $sLoginGUID);
	$sOSLC_URL 	.= $sParas;
	$xmlDoc = HTTPGetXML($sOSLC_URL);
	if ($xmlDoc != null)
	{
	$xnRoot = $xmlDoc->documentElement;
	$xnShape = GetXMLFirstChild($xnRoot);
	if ($xnShape != null && $xnShape->childNodes != null)
	{
	if ( GetOSLCError($xnShape) )
	{
	foreach ($xnShape->childNodes as $xnOProps)
	{
	if ($xnOProps->nodeName === 'oslc:property')
	{
	$xnOProp2 = GetXMLFirstChild($xnOProps);
	if ($xnOProp2 != null && $xnOProp2->childNodes != null)
	{
	$sFieldName = '';
	$sKey 	= '';
	$sValue	= '';
	foreach ($xnOProp2->childNodes as $xn)
	{
	if ( strIsEmpty($sFieldName) )
	{
	GetXMLNodeValue($xn, 'oslc:name', $sFieldName);
	}
	else
	{
	$sKey 	= '';
	$sValue = '';
	if ($xn->nodeName === 'oslc:allowedValue')
	{
	$sValue = $xn->nodeValue;
	$sKey	= str_replace(' ', '', $sValue);
	$sKey	= strtolower($sKey);
	}
	if ( !strIsEmpty($sValue) )
	{
	if ( $sFieldName === 'completedby' || $sFieldName === 'resolvedby' || $sFieldName === 'author' )
	{
	$aAuthors[$sKey] = $sValue;
	}
	elseif ( $sFieldName === 'status')
	{
	$aStatuses[$sKey] = $sValue;
	}
	elseif ( $sFieldName === 'priority')
	{
	$aPriorities[$sKey] = $sValue;
	}
	elseif ( $sFieldName === 'type')
	{
	$aTypes[$sKey] = $sValue;
	}
	}
	}
	}
	}
	}
	}
	}
	else
	{
	}
	}
	}
	else
	{
	return null;
	}
	}
?>