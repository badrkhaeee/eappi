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
	$sGUID  	= SafeGetInternalArrayParameter($_POST, 'guid');
	$sType  	= SafeGetInternalArrayParameter($_POST, 'type');
	$sValue  	= SafeGetInternalArrayParameter($_POST, 'value');
	BuildOSLCConnectionString();
	$sURL	= $g_sOSLCString;
	$sXML 	= '';
	$sURL 	= $sURL . 'pu/discussion/';
	$sUpdate = '';
	$sLoginNode = '';
	$sLoginGUID  = SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	if ($sType === 'status')
	{
	$sUpdate = '<ss:status>' . _x($sValue) . '</ss:status>';
	}
	else if ($sType === 'priority')
	{
	$sUpdate = '<ss:priority>' . _x($sValue) . '</ss:priority>';
	}
	if ( !strIsEmpty($sLoginGUID) )
	{
	$sLoginNode = '<ss:useridentifier>' . _x($sLoginGUID) . '</ss:useridentifier>';
	}
	$sXML  = '<?xml version="1.0" encoding="utf-8"?>';
	$sXML .= '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:ss="http://www.sparxsystems.com.au/oslc_am#">';
	$sXML .= '<ss:discussion>';
	$sXML .= '<ss:key>';
	$sXML .= '<rdf:Description>';
	$sXML .= '<dcterms:identifier>' . _x($sGUID) . '</dcterms:identifier>';
	$sXML .= '</rdf:Description>';
	$sXML .= '</ss:key>';
	$sXML .= $sUpdate;
	$sXML .= $sLoginNode;
	$sXML .= '</ss:discussion>';
	$sXML .= '</rdf:RDF>';
	$xmlRespDoc = HTTPPostXML($sURL, $sXML);
	$sOSLCErrorMsg = BuildOSLCErrorString();
	echo $sOSLCErrorMsg;
?>