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
	BuildOSLCConnectionString();
	$sURL	= $g_sOSLCString;
	$sXML 	= '';
	$sURL 	= $sURL . 'cf/resourceallocation/';
	$sXML =
	  '<?xml version="1.0" encoding="utf-8"?>'
	. '<rdf:RDF xmlns:oslc_am="http://open-services.net/ns/am#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/"  xmlns:foaf="http://xmlns.com/foaf/0.1/" xmlns:ss="http://www.sparxsystems.com.au/oslc_am#">'
	. '<ss:resourceallocation>'
	. '<ss:resourcename><foaf:Person><foaf:name>' . _x($sResource) . '</foaf:name></foaf:Person></ss:resourcename>'
	. '<ss:role>' . _x($sRole) . '</ss:role>'
	. '<ss:startdate>' . _x($sStartDate) . '</ss:startdate>'
	. '<ss:enddate>' . _x($sEndDate) . '</ss:enddate>'
	. '<ss:resourceidentifier>' . _x($sGUID) . '</ss:resourceidentifier>';
	if ( !strIsEmpty($sPercentComp) && $sPercentComp !== '0' )
	$sXML .= '<ss:percentagecomplete>' . _x($sPercentComp) . '</ss:percentagecomplete>';
	if ( !strIsEmpty($sExpectedTime) && $sExpectedTime !== '0' )
	$sXML .= '<ss:expectedtime>' . _x($sExpectedTime) . '</ss:expectedtime>';
	if ( !strIsEmpty($sAllocTime) && $sAllocTime !== '0' )
	$sXML .= '<ss:allocatedtime>' . _x($sAllocTime) . '</ss:allocatedtime>';
	if ( !strIsEmpty($sExpendedTime) && $sExpendedTime !== '0' )
	$sXML .= '<ss:expendedtime>' . _x($sExpendedTime) . '</ss:expendedtime>';
	if ( !strIsEmpty($sNotes) )
	$sXML .= '<dcterms:description>' . _x($sNotes) . '</dcterms:description>';
	if ( !strIsEmpty($sHistory) )
	$sXML .= '<ss:history>' . _x($sHistory) . '</ss:history>';
	if ( !strIsEmpty($sLoginGUID) )
	$sXML .= '<ss:useridentifier>' . _x($sLoginGUID) . '</ss:useridentifier>';
	$sXML .= '</ss:resourceallocation>'
	  .  '</rdf:RDF>';
	$xmlRespDoc = HTTPPostXML($sURL, $sXML);
?>