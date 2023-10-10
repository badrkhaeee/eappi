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
	SafeStartSession();
	CheckAuthorisation();
	$g_sViewingMode	= '1';
	$bAddElements	= CanAddObjects();
	$bAddPackages	= IsSessionSettingTrue('add_objecttype_package') && IsSessionSettingTrue('login_perm_element');
	$bShowMiniProps = IsSessionSettingTrue('show_propertiesview');
	$aObjs = array();
	include('./data_api/get_objects.php');
	echo '<div id="main-package-icon" class="package-related-bkcolor ' . GetShowBrowserMinPropsStyleClasses() . '">';
	WritePackageViewButtons($sMainLayoutNo);
	$iCnt = count($aObjs);
	if ( $iCnt > 0 )
	{
	echo '<div class="main-package-inner">';
	echo '<div id="icon-view">';
	for ($i=0; $i<$iCnt; $i++)
	{
	$sName 	= GetPlainDisplayName($aObjs[$i]['text']);
	$sNameWithClassifier 	= GetDisplayNameWithClassifier(SafeGetArrayItem2Dim($aObjs, $i, 'text'), SafeGetArrayItem2Dim($aObjs, $i, 'classifiername'));
	$sGUID	= $aObjs[$i]['guid'] ;
	$sResType	= $aObjs[$i]['restype'];
	$sHasChild	= $aObjs[$i]['haschild'];
	$sLinkType	= $aObjs[$i]['linktype'];
	$sHyper	= $aObjs[$i]['hyper'];
	$sImageURL	= $aObjs[$i]['imageurl'];
	$bObjLocked	= strIsTrue($aObjs[$i]['locked']);
	$sObjLockedType	= $aObjs[$i]['lockedtype'];
	if((strIsTrue($bObjLocked)) && ($sObjLockedType=== 'Security_RULTE_NoLock'))
	{
	$bObjLocked = false;
	}
	echo '<div class="icon-view-item" onclick="LoadObject(\'' . _j($sGUID) . '\',\'' . _j($sHasChild) . '\',\'' . _j($sLinkType) . '\',\'' . _j($sHyper) . '\',\'' . _j($sName) . '\',\'' . _j($sImageURL) . '\')">';
	echo '<div class="package-icon-img-image">';
	if ($sHasChild === 'true' && $bObjLocked)
	{
	echo '<span class="package-icon-img-span element48-lockedhaschildoverlay" alt="" title="' . _glt('View child objects for locked') . ' ' . _j($sResType) . '"></span>';
	}
	elseif ($sHasChild === 'true' && !$bObjLocked )
	{
	echo '<span class="package-icon-img-span element48-haschildoverlay" alt="" title="' . _glt('View child objects') . '"></span>';
	}
	elseif ($sHasChild === 'false' && $bObjLocked)
	{
	echo '<span class="package-icon-img-span element48-lockedoverlay" alt="" title="' . _j($sResType) . ' ' . _glt('is locked') . '"></span>';
	}
	echo '  <img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '" alt="" title=""/>';
	echo '</div>';
	echo '<h3><div>' . _h($sNameWithClassifier) . '</div></h3>';
	echo '</div>';
	}
	if ($bAddElements)
	{
	echo WriteAddObjectIconView($sObjectGUID, $sObjectName, $bAddPackages);
	}
	echo '</div>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
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
	if ($bAddElements)
	{
	echo '<div class="main-package-inner">';
	echo '<div id="icon-view">';
	$sIcon = WriteAddObjectIconView($sObjectGUID, $sObjectName, $bAddPackages);
	if (!strIsEmpty($sIcon))
	{
	echo $sIcon;
	}
	else
	{
	echo _glt('No child elements');
	}
	echo '</div>';
	echo '</div>';
	}
	else
	{
	echo '<div class="main-package-inner">' . _glt('No child elements') . '</div>';
	}
	}
	}
	else
	{
	echo '<div class="main-package-inner">' . $sOSLCErrorMsg . '</div>';
	}
	}
	echo '</div>' . PHP_EOL;
	function WriteAddObjectIconView($sObjectGUID, $sObjectName, $bAddPackages)
	{
	$sHTML = '';
	$sAddObjectName 	= _glt('Add new element');
	$sAddObjectImageName = 'element48-add';
	$bCanAdd 	= true;
	$sHref = 'AddObject(\'' . _j($sObjectGUID) . '\', \''._j($sObjectName).'\')';
	if ( strIsEmpty($sObjectGUID) )
	{
	$sAddObjectName = _glt('Add Root Node');
	$sAddObjectImageName = 'element48-addmodelroot';
	$bCanAdd 	= $bAddPackages;
	$sHref = 'AddModelRoot(\'' . _j($sObjectGUID) . '\', \''._j($sObjectName).'\')';
	}
	elseif ( substr($sObjectGUID,0,3) === 'mr_' )
	{
	$sAddObjectName = _glt('Add View');
	$sAddObjectImageName = 'element48-addviewpackage';
	$bCanAdd 	= $bAddPackages;
	$sHref = 'AddViewPackage(\'' . _j($sObjectGUID) . '\', \''._j($sObjectName).'\')';
	}
	if ( $bCanAdd )
	{
	$sHTML .= '<div class="icon-view-item w3-italic" title="' . _h($sAddObjectName) . '" onclick="'.$sHref.'">';
	$sHTML .= '<div class="package-icon-img-image">';
	$sHTML .= '  <img src="images/spriteplaceholder.png" class="' . _h($sAddObjectImageName) . '" alt="" title="' . _h($sAddObjectName) . '"/>';
	$sHTML .= '</div>';
	$sHTML .= '<h3><div>&lt;' . _glt('New') . '&gt;</div></h3>';
	$sHTML .= '</div>';
	}
	return $sHTML;
	}
?>