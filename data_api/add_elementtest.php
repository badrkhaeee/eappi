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
	$sURL 	= $sURL . 'cf/test/';
	$sXML =
	  '<?xml version="1.0" encoding="utf-8"?>'
	. '<rdf:RDF xmlns:oslc_am="http://open-services.net/ns/am#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/"  xmlns:foaf="http://xmlns.com/foaf/0.1/" xmlns:ss="http://www.sparxsystems.com.au/oslc_am#">'
	. '<ss:test>'
	. '<dcterms:title>' . _x($sName) . '</dcterms:title>'
	. '<dcterms:type>' . _x($sType) . '</dcterms:type>'
	. '<ss:classtype>' . _x($sClassType) . '</ss:classtype>'
	. '<ss:status>' . _x($sStatus) . '</ss:status>'
	. '<ss:resourceidentifier>' . _x($sGUID) . '</ss:resourceidentifier>';
	if ( !strIsEmpty($sLastRun) )
	$sXML .= '<ss:lastrun>' . _x($sLastRun) . '</ss:lastrun>';
	if ( !strIsEmpty($sRunBy) )
	$sXML .= '<ss:runby><foaf:Person><foaf:name>' . _x($sRunBy) . '</foaf:name></foaf:Person></ss:runby>';
	if ( !strIsEmpty($sCheckedBy) )
	$sXML .= '<ss:checkedby><foaf:Person><foaf:name>' . _x($sCheckedBy) . '</foaf:name></foaf:Person></ss:checkedby>';
	if ( !strIsEmpty($sNotes) )
	$sXML .= '<dcterms:description>' . _x($sNotes) . '</dcterms:description>';
	if ( !strIsEmpty($sInput) )
	$sXML .= '<ss:input>' . _x($sInput) . '</ss:input>';
	if ( !strIsEmpty($sAcceptance) )
	$sXML .= '<ss:acceptancecriteria>' . _x($sAcceptance) . '</ss:acceptancecriteria>';
	if ( !strIsEmpty($sResults) )
	$sXML .= '<ss:results>' . _x($sResults) . '</ss:results>';
	if ( !strIsEmpty($sLoginGUID) )
	$sXML .= '<ss:useridentifier>' . _x($sLoginGUID) . '</ss:useridentifier>';
	$sXML .= '</ss:test>'
	  .  '</rdf:RDF>';
	$xmlRespDoc = HTTPPostXML($sURL, $sXML);
?>