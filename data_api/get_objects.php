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
	CheckAuthorisation();
	$sLoginGUID  = SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	$sSortOrder  = SafeGetInternalArrayParameter($_SESSION, 'object_order');
	if  ( !isset($webea_page_parent_mainview)  ||
	   isset($sObjectGUID) === false)
	{
	exit();
	}
	BuildOSLCConnectionString();
	$sOSLC_URL = $g_sOSLCString . 'pbstructure/';
	if ( !strIsEmpty($sObjectGUID) )
	{
	$sOSLC_URL .= $sObjectGUID . '/';
	}
	$sParas = '';
	AddURLParameter($sParas, 'useridentifier', $sLoginGUID);
	AddURLParameter($sParas, 'options', 'exclude_resource_notes');
	AddURLParameter($sParas, 'orderby', $sSortOrder);
	$sOSLC_URL 	.= $sParas;
	$xmlDoc = HTTPGetXML($sOSLC_URL);
	$sParentObjGUID = '';
	$sParentObjName = '';
	if ($xmlDoc != null)
	{
	$xnRoot = $xmlDoc->documentElement;
	$xnDesc = $xnRoot->firstChild;
	if ($xnDesc != null && $xnDesc->childNodes != null)
	{
	if ( GetOSLCError($xnDesc) )
	{
	foreach ($xnDesc->childNodes as $xnObjProp)
	{
	if ($xnObjProp->nodeName === "ss:locked")
	$bAddElements = false;
	if ($xnObjProp->nodeName === "ss:ancestors")
	{
	$xnADesc = $xnObjProp->firstChild;
	$xnADescMem = $xnADesc->firstChild;
	if ($xnADescMem != null)
	{
	$xnObj = $xnADescMem->firstChild;
	$xnObjProps = $xnObj->getElementsByTagNameNS("*", "*");
	if ($xnObjProps != null)
	{
	foreach ($xnObjProps as $xnObjProp)
	{
	GetXMLNodeValue($xnObjProp, 'dcterms:title', $sParentObjName);
	GetXMLNodeValueAttr($xnObjProp, 'ss:pbstructure', 'rdf:resource', $sStructure);
	$sParentObjGUID = TrimInternalURL($sStructure, '/pbstructure/');
	}
	}
	}
	}
	elseif ($xnObjProp->nodeName === "ss:descendants")
	{
	$xnDDesc = $xnObjProp->firstChild;
	$xnMembers = $xnDDesc->childNodes;
	if ($xnMembers != null)
	{
	foreach ($xnMembers as $xnMem)
	{
	$xnObj = $xnMem->firstChild;
	$sObjName 	= '';
	$sObjGUID 	= '';
	$sObjType 	= '';
	$sObjModified	= '';
	$sObjResType 	= '';
	$sObjStructure 	= '';
	$sObjStereo	= '';
	$sObjNType 	= '0';
	$sStructure 	= '';
	$sObjLinkType	= '';
	$sObjLink	= '';
	$sObjLocked	= '';
	$sObjLockedType	= '';
	$sObjAuthor	= '';
	$sObjAlias	= '';
	$sObjNotes	= '';
	$sClassifierName	= '';
	$aStereotypes 	= array();
	if ( substr($sObjectGUID,0,4)==='mr_{' )
	{
	$sObjNType = '6';
	}
	$xnObjProps = $xnObj->childNodes;
	if ($xnObjProps != null)
	{
	foreach ($xnObjProps as $xnObjProp)
	{
	GetXMLNodeValue($xnObjProp, 'dcterms:title', $sObjName);
	GetXMLNodeValue($xnObjProp, 'dcterms:identifier', $sObjGUID);
	GetXMLNodeValue($xnObjProp, 'ss:resourcetype', $sObjResType);
	GetXMLNodeValue($xnObjProp, 'ss:iconidentifier', $sObjNType);
	GetXMLNodeValue($xnObjProp, 'ss:ntype', $sObjNType);
	AddStereotypeToArray($xnObjProp, $aStereotypes);
	GetXMLNodeValue($xnObjProp, 'dcterms:type', $sObjType);
	GetXMLNodeValue($xnObjProp, 'dcterms:modified', $sObjModified);
	GetXMLNodeValue($xnObjProp, 'dcterms:description', 	$sObjNotes);
	GetXMLNodeValueAttr($xnObjProp, 'ss:pbstructure', 'rdf:resource', $sStructure);
	$sObjStructure = TrimInternalURL($sStructure, '/pbstructure/');
	GetXMLNodeValueAttr($xnObjProp, 'ss:hyperlinktarget', 'ss:type', $sObjLinkType);
	GetXMLNodeValueAttr($xnObjProp, 'ss:hyperlinktarget', 'rdf:resource', $sObjLink);
	$sObjLink = TrimInternalURL($sObjLink, '/pbstructure/');
	GetXMLNodeValue($xnObjProp, 'ss:locked', $sObjLocked);
	GetXMLNodeValue($xnObjProp, 'ss:locktype', $sObjLockedType);
	GetXMLNodeValue($xnObjProp, 'ss:alias', $sObjAlias);
	GetAuthorFromXMLNode($xnObjProp, $sObjAuthor);
	GetClassifierNameFromXMLNode($xnObjProp, $sClassifierName);
	}
	if (count($aStereotypes)>0)
	{
	$sObjStereo = getPrimaryStereotype($aStereotypes);
	}
	$row['text'] 	= $sObjName;
	$row['guid'] 	= $sObjGUID;
	$row['type'] 	= $sObjType;
	$row['modified'] 	= $sObjModified;
	$row['ntype'] 	= $sObjNType;
	$row['restype'] 	= $sObjResType;
	$row['structid'] 	= $sObjStructure;
	$row['notes'] 	= $sObjNotes;
	$row['imageurl'] 	= GetObjectImagePath($sObjType, $sObjResType, $sObjStereo, $sObjNType, 48);
	$row['haschild']	= (!strIsEmpty($sObjStructure)?'true':'false');
	$row['linktype']	= $sObjLinkType;
	$row['hyper']	= $sObjLink;
	$row['locked']	= $sObjLocked;
	$row['lockedtype']	= $sObjLockedType;
	$row['author']	= $sObjAuthor;
	$row['alias']	= $sObjAlias;
	$row['classifiername']	= $sClassifierName;
	$aObjs[] = $row;
	}
	}
	}
	}
	}
	}
	}
	}
?>