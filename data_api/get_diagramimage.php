<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	if  ( !isset($webea_page_parent_mainview) ||
	   isset($sObjectGUID) === false )
	{
	exit();
	}
	SafeStartSession();
	CheckAuthorisation();
	BuildOSLCConnectionString();
	$sOSLC_URL = $g_sOSLCString . "completeresource/" . $sObjectGUID . "/";
	if ( !strIsEmpty($sObjectGUID) )
	{
	if ( !strIsEmpty($sOSLC_URL) )
	{
	if (isset($bShowMiniProps) === false)
	{
	$bShowMiniProps = false;
	}
	$sStatusCode = '';
	$sStatusMessage = '';
	$sParas = '';
	$sLoginGUID  = SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	AddURLParameter($sParas, 'useridentifier', $sLoginGUID);
	$sResourceFeaturesFilter = "di";
	if ( !strIsEmpty($sResourceFeaturesFilter) )
	{
	AddURLParameter($sParas, 'features', $sResourceFeaturesFilter);
	}
	$sOSLC_URL 	.= $sParas;
	$xmlDoc = HTTPGetXML($sOSLC_URL);
	if ($xmlDoc != null)
	{
	$sImageInSync = '';
	$sObjectGenerated = '';
	$sDiagramNotes = '';
	$xnRoot = $xmlDoc->documentElement;
	$xnDesc = GetXMLFirstChild($xnRoot);
	if ($xnDesc != null && $xnDesc->childNodes != null)
	{
	if ( GetOSLCError($xnDesc) )
	{
	$xnFeatures = null;
	foreach($xnDesc->childNodes as $xnDescB)
	{
	if($xnDescB->nodeName === "ss:features")
	$xnFeatures = GetXMLFirstChild($xnDescB);
	else if($xnDescB->nodeName === "ss:locked")
	$sDiagLocked = '1';
	else if ($xnDescB->nodeName === "dcterms:description")
	$sDiagramNotes = $xnDescB->nodeValue;
	}
	if ($xnFeatures !== null)
	{
	foreach ($xnFeatures->childNodes as $xnDescC)
	{
	if ($xnDescC->nodeName === "ss:diagramimage")
	{
	$xnImgDesc = $xnDescC->firstChild;
	$xnImgProps = $xnImgDesc->getElementsByTagNameNS("*", "*");
	if ($xnImgProps !== null)
	{
	foreach ($xnImgProps as $xnImgProp)
	{
	if ($xnImgProp->nodeName === "ss:image")
	{
	$sImgBin = $xnImgProp->nodeValue;
	}
	elseif ($xnImgProp->nodeName === "ss:imagemap")
	{
	$sImgMap = $xnImgProp->nodeValue;
	}
	elseif ($xnImgProp->nodeName === "ss:imageinsync")
	{
	$sImageInSync = $xnImgProp->nodeValue;
	}
	elseif($xnImgProp->nodeName === "dcterms:created")
	{
	$sObjectGenerated = $xnImgProp->nodeValue;
	}
	}
	}
	}
	}
	}
	if ( !strIsEmpty($sImgMap) )
	{
	$sImgMap = str_replace('<![CDATA[', '', $sImgMap);
	$sImgMap = str_replace("]]>","", $sImgMap);
	$sJSFunct = 'javascript:LoadDiagramObject(\'';
	if ( substr_count($sImgMap, '" alt="') > 0 )
	{
	$aMap = explode('$element=',$sImgMap);
	$i=0;
	foreach ($aMap as &$sMapline)
	{
	if($i !==0)
	{
	if (strpos($sMapline, 'alt="Element"'))
	{
	$sMapline = $sJSFunct . 'el_' .$sMapline ;
	}
	else if (strpos($sMapline, 'alt="Package"'))
	{
	$sMapline = $sJSFunct . 'pk_' . $sMapline;
	}
	else
	{
	$sMapline = $sJSFunct . 'el_' .$sMapline;
	}
	$sMapline = str_replace('}','}\')',$sMapline);
	}
	$i++;
	}
	$sImgMap = implode ($aMap);
	}
	else
	{
	$sImgMap = str_replace('$element=', $sJSFunct . 'el_', $sImgMap);
	$sImgMap = str_replace('}',"}');", $sImgMap);
	}
	$sImgMap = '<map id = "diagrammap" name="diagrammap">' . $sImgMap . '</map>';
	}
	}
	}
	}
	}
	}
?>