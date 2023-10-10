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
	if ($sFeatureType !== 'risk')
	{
	$sURL 	= $sURL . 'cf/maintenanceitem/';
	$sDate1Desc 	= '';
	$sDate2Desc 	= '';
	$sPerson1Desc 	= '';
	$sPerson2Desc 	= '';
	GetChgMgmtFieldNames($sFeatureType, 2, $sDate1Desc, $sDate2Desc, $sPerson1Desc, $sPerson2Desc);
	$sXML =
	  '<?xml version="1.0" encoding="utf-8"?>'
	. '<rdf:RDF xmlns:oslc_am="http://open-services.net/ns/am#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/"  xmlns:foaf="http://xmlns.com/foaf/0.1/" xmlns:ss="http://www.sparxsystems.com.au/oslc_am#">'
	. '<ss:' . _x($sFeatureType) . '>'
	. '<dcterms:title>' . _x($sName) . '</dcterms:title>'
	. '<ss:status>' . _x($sStatus) . '</ss:status>'
	. '<ss:priority>' . _x($sPriority) . '</ss:priority>'
	. '<ss:resourceidentifier>' . _x($sGUID) . '</ss:resourceidentifier>';
	if ( !strIsEmpty($sAuthor1) )
	$sXML .= '<ss:' . _x($sPerson1Desc) . '><foaf:Person><foaf:name>' . _x($sAuthor1) . '</foaf:name></foaf:Person></ss:' . _x($sPerson1Desc) . '>';
	if ( !strIsEmpty($sDate1) )
	$sXML .= '<ss:' . _x($sDate1Desc) . '>' . _x($sDate1) . '</ss:' . _x($sDate1Desc) . '>';
	if ( !strIsEmpty($sAuthor2) )
	$sXML .= '<ss:' . _x($sPerson2Desc) . '><foaf:Person><foaf:name>' . _x($sAuthor2) . '</foaf:name></foaf:Person></ss:' . _x($sPerson2Desc) . '>';
	if ( !strIsEmpty($sDate2) )
	$sXML .= '<ss:' . _x($sDate2Desc) . '>' . _x($sDate2) . '</ss:' . _x($sDate2Desc) . '>';
	if ( !strIsEmpty($sVersion) )
	$sXML .= '<ss:version>' . _x($sVersion) . '</ss:version>';
	if ( !strIsEmpty($sNotes) )
	$sXML .= '<dcterms:description>' . _x($sNotes) . '</dcterms:description>';
	if ( !strIsEmpty($sHistory) )
	$sXML .= '<ss:history>' . _x($sHistory) . '</ss:history>';
	if ( !strIsEmpty($sLoginGUID) )
	$sXML .= '<ss:useridentifier>' . _x($sLoginGUID) . '</ss:useridentifier>';
	$sXML .= '</ss:' . _x($sFeatureType) . '>'
	  .  '</rdf:RDF>';
	}
	else
	{
	$sURL 	= $sURL . 'cf/projectmanagementitem/';
	$sXML =
	  '<?xml version="1.0" encoding="utf-8"?>'
	. '<rdf:RDF xmlns:oslc_am="http://open-services.net/ns/am#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/"  xmlns:ss="http://www.sparxsystems.com.au/oslc_am#">'
	. '<ss:' . _x($sFeatureType) . '>'
	. '<dcterms:title>' . _x($sName) . '</dcterms:title>'
	. '<ss:resourceidentifier>' . _x($sGUID) . '</ss:resourceidentifier>';
	if ( !strIsEmpty($sType) )
	$sXML .= '<dcterms:type>' . _x($sType) . '</dcterms:type>';
	if ( !strIsEmpty($sWeight) )
	$sXML .= '<ss:weight>' . _x($sWeight) . '</ss:weight>';
	if ( !strIsEmpty($sNotes) )
	$sXML .= '<dcterms:description>' . _x($sNotes) . '</dcterms:description>';
	if ( !strIsEmpty($sLoginGUID) )
	$sXML .= '<ss:useridentifier>' . _x($sLoginGUID) . '</ss:useridentifier>';
	$sXML .= '</ss:' . _x($sFeatureType) . '>'
	  .  '</rdf:RDF>';
	}
	$xmlRespDoc = HTTPPostXML($sURL, $sXML);
?>