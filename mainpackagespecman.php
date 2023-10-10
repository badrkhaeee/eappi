<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	if  ( !isset($webea_page_parent_mainview) )
	{
	exit();
	}
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	require_once __DIR__ . '/htmlpurifier.php';
	SafeStartSession();
	CheckAuthorisation();
	$g_sViewingMode	= '1';
	$bAddElements	= false;
	if ( !strIsEmpty($sObjectGUID) && (substr($sObjectGUID,0,3) === 'pk_' || substr($sObjectGUID,0,3) === 'mr_') )
	{
	$bAddElements	= CanAddObjects();
	}
	$bShowMiniProps = IsSessionSettingTrue('show_propertiesview');
	$aProps = array();
	$aObjs = array();
	$sErrorMsg = '';
	include('./data_api/get_objectsspecman.php');
	echo '<div id="main-package-list" class="package-related-bkcolor main-view ' . GetShowBrowserMinPropsStyleClasses() . '">';
	WritePackageViewButtons($sMainLayoutNo);
	$iCnt = count($aObjs);
	if ( $iCnt > 0 )
	{
	echo '<div class="main-package-inner">';
	echo '<div id="package-specman">';
	for ($i = 0; $i < $iCnt; $i++)
	{
	$sLevel	= SafeGetArrayItem2Dim($aObjs, $i, 'level');
	$sName 	= GetPlainDisplayName(SafeGetArrayItem2Dim($aObjs, $i, 'name'));
	$sGUID	= SafeGetArrayItem2Dim($aObjs, $i, 'guid');
	$sResType	= SafeGetArrayItem2Dim($aObjs, $i, 'restype');
	$sHasChild	= SafeGetArrayItem2Dim($aObjs, $i, 'haschild');
	$sType	= SafeGetArrayItem2Dim($aObjs, $i, 'type');
	$sNotes	= SafeGetArrayItem2Dim($aObjs, $i, 'notes');
	$sNType	= SafeGetArrayItem2Dim($aObjs, $i, 'ntype');
	$sLinkType	= SafeGetArrayItem2Dim($aObjs, $i, 'linktype');
	$sHyper	= SafeGetArrayItem2Dim($aObjs, $i, 'hyper');
	$sImageURL	= SafeGetArrayItem2Dim($aObjs, $i, 'imageurl');
	$bObjLocked	= SafeGetArrayItem2Dim($aObjs, $i, 'locked');
	$sObjLockedType	= SafeGetArrayItem2Dim($aObjs, $i, 'lockedtype');
	$sAuthor 	= SafeGetArrayItem2Dim($aObjs, $i, 'author');
	$sAlias 	= SafeGetArrayItem2Dim($aObjs, $i, 'alias');
	$sModified 	= SafeGetArrayItem2Dim($aObjs, $i, 'modified');
	$sImageURL	= AdjustImagePath($sImageURL, '16');
	if ( strIsEmpty($sName) && !strIsEmpty($sAlias))
	$sName = $sAlias;
	else if ($sNType === '19' && !strIsEmpty($sAlias))
	$sName = $sAlias;
	$sNameInLink = LimitDisplayString($sName, 255);
	$sHref = 'javascript:LoadObject(\'' . _j($sGUID) . '\',\'\',\'' . (($sHasChild === 'true') ? 'child1' : '') .  '\',\'\',\'' . _j($sNameInLink) . '\',\'' . _j($sImageURL) . '\')';
	$sImageHTML  = '';
	$sImageHTML .= '<div class="package-specman-img-image">';
	if ($sHasChild === 'true' && $bObjLocked)
	{
	$sImageHTML .= '  <span class="package-specman-img-span element16-lockedhaschildoverlay" alt="" title="' . _glt('View child objects for locked') . ' ' . _h($sResType) . '"></span>';
	}
	elseif ($sHasChild === 'true' && !$bObjLocked )
	{
	$sImageHTML .= '  <span class="package-specman-img-span element16-haschildoverlay" alt="" title="' . _glt('View child objects') . '"/>';
	}
	elseif ($sHasChild === 'false' && $bObjLocked)
	{
	$sImageHTML .= '  <span class="package-specman-img-span element16-lockedoverlay.png" alt="" title="' . _h($sResType) . ' ' . _glt('is locked') . '"/>';
	}
	$sImageHTML .= '  <img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '" alt="" title=""/>';
	$sImageHTML .= '</div>';
	$sIdentStyle = ' style="padding-left: ' . ((($sLevel-1) * 20)+4) . 'px;"';
	if ( substr($sGUID,0,3) === 'pk_' || substr($sGUID,0,3) === 'mr_' )
	{
	$sNameSize = 'diagram-specman-item-name-size3';
	}
	else if ( $sLevel > 0 && $sLevel < 4 )
	{
	$sNameSize = 'diagram-specman-item-name-size' . $sLevel;
	}
	echo '<div class="package-specman-item" ' . _h($sIdentStyle) . '>';
	echo '<div class="package-specman-item-name ' . _h($sNameSize) . '" onclick="' . $sHref . '"><div style="float: left;">' . $sImageHTML . '</div><div style="padding-left: 24px;">' . _h($sName) . '</div></div>';
	echo '<div class="package-specman-item-notes">' . _hRichText($sNotes) . '</div>';
	echo '</div>';
	}
	echo '</div>';
	echo '</div>';
	}
	else
	{
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	if ( strIsEmpty($sObjectGUID) )
	{
	echo '<div class="main-package-inner">' . _glt('Problem reading model root') . '</div>';
	}
	else
	{
	echo '<div class="main-package-inner">' . _glt('No child elements') . '</div>';
	}
	}
	else
	{
	echo '<div class="main-package-inner">' . $sOSLCErrorMsg . '</div>';
	}
	}
	echo '</div>' . PHP_EOL;
?>