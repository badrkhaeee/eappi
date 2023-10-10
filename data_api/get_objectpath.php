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
	if  ( !isset($webea_page_parent_objectpath))
	{
	exit();
	}
	$aRows = array();
	$xmlDoc 	= null;
	$sCurrObjName 	= '';
	$sCurrObjGUID 	= '';
	$sCurrObjType 	= '';
	$sCurrObjResType = '';
	$sCurrObjStereo = '';
	$sCurrObjNType	= '';
	$sCurrLocked	= '';
	$sCurrLockedType= '';
	$sCurrObjStructure = '';
	$sParentGUID 	= '';
	$sKey2	 	= '';
	$sKey3	 	= '';
	$sChgMgmType	= '';
	$bHasObjectPath = true;
	$bHasParent	= false;
	$aStereotypes 	= array();
	$sWebsiteRoot = '';
	$sDocPath = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	$iLastPos = strripos($sDocPath, '/');
	if ( $iLastPos !== FALSE )
	{
	if ( $iLastPos > 0 )
	{
	$sWebsiteRoot = substr($sDocPath, 0, $iLastPos);
	}
	}
	if ( $sObjectGUID==='initialize' )
	{
	$sObjectGUID = '';
	}
	if ( !strIsEmpty($sObjectGUID) )
	{
	$sPrefix = substr($sObjectGUID,0,4);
	if ( $sCurrLinkType === 'document' )
	{
	$bHasParent	= true;
	}
	if ($sObjectGUID === 'search')
	{
	$aRow['name'] 	= _glt('Search');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/search.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ($sObjectGUID === 'searchresults' )
	{
	$aRow['name'] 	= _glt('Search Results');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/searchresults.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ( $sObjectGUID === 'watchlist' )
	{
	$aRow['name'] 	= _glt('Watchlist');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/watchlist.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ( $sObjectGUID === 'watchlistconfig' )
	{
	$aRow['name'] 	= _glt('Watchlist configuration');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/watchlistconfig.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ( $sObjectGUID === 'watchlistresults' )
	{
	$aRow['name'] 	= _glt('Watchlist results');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/watchlistresults.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ( $sCurrLinkType === 'document' )
	{
	$aRow['name'] 	= _glt('Linked Document');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl']	= $sWebsiteRoot . '/images/element16/document.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$sParentGUID 	= $sObjectGUID;
	}
	elseif ($sPrefix === 'lt_{')
	{
	$aRow['name'] 	= $sCurrObjName;
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/connector.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ($sObjectGUID === 'matrix')
	{
	$aRow['name'] 	= _glt('Relationship Matrix');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/matrix.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ($sObjectGUID === 'matrixprofiles')
	{
	$aRow['name'] 	= _glt('Matrix Profiles');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/matrix.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ($sObjectGUID === 'modelmail')
	{
	$aRow['name'] 	= _glt('Model Mail');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/modelmail.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	elseif ($sObjectGUID === 'collaborate')
	{
	$aRow['name'] 	= _glt('Collaborate');
	$aRow['guid'] 	= $sObjectGUID;
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= $sWebsiteRoot . '/images/element16/modelmail.png';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$bHasObjectPath 	= false;
	}
	if ( $bHasObjectPath )
	{
	BuildOSLCConnectionString();
	$sOSLC_URL = $g_sOSLCString . 'pbstructure/completeancestory/';
	if ( $bHasParent )
	$sOSLC_URL = $sOSLC_URL . $sParentGUID . '/';
	else
	$sOSLC_URL = $sOSLC_URL . $sObjectGUID . '/';
	$sParas = '';
	$sLoginGUID  = SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	AddURLParameter($sParas, 'useridentifier', $sLoginGUID);
	$sOSLC_URL 	.= $sParas;
	$xmlDoc = HTTPGetXML($sOSLC_URL);
	}
	}
	if ($xmlDoc !== null)
	{
	$aRow['text'] 	= '';
	$aRow['guid'] 	= '';
	$aRow['restype'] 	= '';
	$aRow['imageurl'] 	= '';
	$aRow['haschild'] 	= 'false';
	$aRows[] 	= $aRow;
	$sStructure	= '';
	$xnRoot = $xmlDoc->documentElement;
	$xnDesc = $xnRoot->firstChild;
	if ($xnDesc != null)
	{
	foreach ($xnDesc->childNodes as $xnObjProp)
	{
	GetXMLNodeValue($xnObjProp, 'dcterms:title', $sCurrObjName);
	GetXMLNodeValue($xnObjProp, 'ss:alias', $sCurrObjAlias);
	GetXMLNodeValue($xnObjProp, 'dcterms:identifier', $sCurrObjGUID);
	GetXMLNodeValue($xnObjProp, 'dcterms:type', $sCurrObjType);
	GetXMLNodeValue($xnObjProp, 'ss:resourcetype', $sCurrObjResType);
	GetXMLNodeValue($xnObjProp, 'ss:iconidentifier', $sCurrObjNType);
	GetXMLNodeValue($xnObjProp, 'ss:ntype', $sCurrObjNType);
	GetXMLNodeValueAttr($xnObjProp, 'ss:pbstructure', 'rdf:resource', $sStructure);
	$sCurrObjStructure = TrimInternalURL($sStructure, '/pbstructure/');
	AddStereotypeToArray($xnObjProp, $aStereotypes);
	GetXMLNodeValue($xnObjProp, 'ss:locked', $sCurrLocked);
	GetXMLNodeValue($xnObjProp, 'ss:locktype', $sCurrLockedType);
	if ($xnObjProp->nodeName === 'ss:ancestors')
	{
	$xnADesc = $xnObjProp->firstChild;
	$xnMembers = $xnADesc->getElementsByTagNameNS('*', 'member');
	foreach ($xnMembers as $xnMem)
	{
	$xnObj = $xnMem->firstChild;
	$sObjName 	= '';
	$sObjAlias 	= '';
	$sObjGUID 	= '';
	$sObjType 	= '';
	$sObjResType 	= '';
	$sObjStereo 	= '';
	$sObjNType	= '';
	$sObjLocked	= '';
	$sObjLockedType	= '';
	$sObjStructure	= '';
	$aAStereotypes	= array();
	$xnObjProps = $xnObj->getElementsByTagNameNS('*', '*');
	foreach ($xnObjProps as $xnObjProp)
	{
	GetXMLNodeValue($xnObjProp, 'dcterms:title', $sObjName);
	GetXMLNodeValue($xnObjProp, 'ss:alias', $sObjAlias);
	GetXMLNodeValue($xnObjProp, 'dcterms:identifier', $sObjGUID);
	GetXMLNodeValue($xnObjProp, 'dcterms:type', $sObjType);
	GetXMLNodeValue($xnObjProp, 'ss:resourcetype', $sObjResType);
	GetXMLNodeValue($xnObjProp, 'ss:iconidentifier', $sObjNType);
	GetXMLNodeValue($xnObjProp, 'ss:ntype', $sObjNType);
	AddStereotypeToArray($xnObjProp, $aAStereotypes);
	GetXMLNodeValue($xnObjProp, 'ss:locked', $sObjLocked);
	GetXMLNodeValue($xnObjProp, 'ss:locktype', $sObjLockedType);
	GetXMLNodeValueAttr($xnObjProp, 'ss:pbstructure', 'rdf:resource', $sStructure);
	$sObjStructure = TrimInternalURL($sStructure, '/pbstructure/');
	}
	if (count($aAStereotypes)>0)
	{
	$sObjStereo = getPrimaryStereotype($aAStereotypes);
	}
	$aRow['name'] 	= GetPlainDisplayName($sObjName);
	$aRow['alias'] 	= $sObjAlias;
	$aRow['type'] 	= $sObjType;
	$aRow['guid'] 	= $sObjGUID;
	$aRow['restype'] 	= $sObjResType;
	$aRow['stereotype']	= $sObjStereo;
	$aRow['ntype'] 	= $sObjNType;
	$aRow['imageurl'] 	= GetObjectImagePath($sObjType, $sObjResType, $sObjStereo, $sObjNType, '16');
	$aRow['haschild'] 	= (!strIsEmpty($sObjStructure)?'true':'false');
	$aRow['locked'] 	= $sObjLocked;
	$aRow['lockedtype'] = $sObjLockedType;
	$aRows[] 	= $aRow;
	}
	}
	}
	$iItemCnt = count($aRows);
	if ($iItemCnt > 2)
	{
	$sObjNType = SafeGetArrayItem2Dim($aRows, $iItemCnt-2, 'ntype');
	if ( strIsEmpty($sObjNType) )
	{
	$sObjType = SafeGetArrayItem2Dim($aRows, $iItemCnt-2, 'type');
	$sObjResType = SafeGetArrayItem2Dim($aRows, $iItemCnt-2, 'restype');
	$sObjStereo = SafeGetArrayItem2Dim($aRows, $iItemCnt-2, 'stereo');
	$aRows[$iItemCnt-2]['imageurl'] 	= GetObjectImagePath($sObjType, $sObjResType, $sObjStereo, '6', '16');
	}
	for ($i=$iItemCnt-3; $i>=0; $i--)
	{
	$sObjType = SafeGetArrayItem2Dim($aRows, $i, 'type');
	$sObjResType = SafeGetArrayItem2Dim($aRows, $i, 'restype');
	$sObjStereo = SafeGetArrayItem2Dim($aRows, $i, 'stereotype');
	if ($sObjType === 'Package' && $sObjResType === 'Package')
	{
	$aRows[$i]['imageurl'] 	= GetObjectImagePath($sObjType, $sObjResType, $sObjStereo, '', '16');
	}
	}
	}
	if (count($aStereotypes)>0)
	{
	$sCurrObjStereo = getPrimaryStereotype($aStereotypes);
	}
	}
	if ( strIsEmpty($sCurrObjName) )
	{
	$sCurrObjName = _glt('<Unnamed object>');
	}
	$iRow = 0;
	if ( $bHasParent )
	{
	$iRow = 1;
	}
	if ($iItemCnt > 2)
	{
	$sCurrObjNType = '';
	}
	elseif ($iItemCnt === 2 && strIsEmpty($sCurrObjNType))
	{
	$sCurrObjNType = '6';
	}
	$aRows[$iRow]['name'] 	= $sCurrObjName;
	$aRows[$iRow]['alias'] 	= $sCurrObjAlias;
	$aRows[$iRow]['type'] 	= $sCurrObjType;
	$aRows[$iRow]['guid'] 	= $sCurrObjGUID;
	$aRows[$iRow]['restype'] 	= $sCurrObjResType;
	$aRows[$iRow]['imageurl'] 	= GetObjectImagePath($sCurrObjType, $sCurrObjResType, $sCurrObjStereo, $sCurrObjNType, '16');
	$aRows[$iRow]['haschild'] 	= (!strIsEmpty($sCurrObjStructure)?'true':'false');
	$aRows[$iRow]['locked'] 	= $sCurrLocked;
	$aRows[$iRow]['lockedtype']	= $sCurrLockedType;
	}
?>