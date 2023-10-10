<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	if  ( !isset($webea_page_parent_navbar) )
	{
	exit();
	}
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	SafeStartSession();
	if (isset($_SESSION['authorized']) === false)
	{
	echo '<div id="main-content-empty">' . _glt('Your session appears to have timed out') . '</div>';
	setResponseCode(440);
	exit();
	}
	$sHome = isset($_SESSION['model_name']) ? $_SESSION['model_name'] : 'Root';
	$sObjectGUID = $sCurrGUID;
	$bShowHomeWithoutLink = true;
	echo '<div class="contextmenu-items">';
	if ( !strIsEmpty($sObjectGUID) && $sObjectGUID !== 'root')
	{
	$iPathCnt = count($aRows);
	if ($iPathCnt > 0)
	{
	echo '<div class="contextmenu-item" onclick="LoadObject(\'\',\'\')"><img alt="Home" src="images/spriteplaceholder.png" class="mainsprite-root">' . _h($sHome) . '</div>';
	$bShowHomeWithoutLink = false;
	for ($i=$iPathCnt-1;$i>=0;$i--)
	{
	$sImageClass= '';
	$sName 	= SafeGetArrayItem2Dim($aRows, $i, 'name');
	$sType 	= SafeGetArrayItem2Dim($aRows, $i, 'type');
	$sAlias	= SafeGetArrayItem2Dim($aRows, $i, 'alias');
	$sName	= GetMeaningfulObjectName($sType, $sName, $sAlias);
	$sName 	= LimitDisplayString($sName, 30);
	$sGUID 	= SafeGetArrayItem2Dim($aRows, $i, 'guid');
	$sImageURL	= SafeGetArrayItem2Dim($aRows, $i, 'imageurl');
	$sResType	= SafeGetArrayItem2Dim($aRows, $i, 'restype');
	$bLocked	= strIsTrue(SafeGetArrayItem2Dim($aRows, $i, 'locked'));
	$sLockType	= SafeGetArrayItem2Dim($aRows, $i, 'lockedtype');
	if($sName==='modelmail')
	$sName=' Model Mail';
	if($sName==='collaborate')
	$sName=' Collaborate';
	if((strIsTrue($bLocked)) && ($sLockType=== 'Security_RULTE_NoLock'))
	{
	$bLocked = false;
	}
	echo '<div class="contextmenu-item"';
	if ($i>0)
	{
	echo ' onclick="LoadObject(\'' . _j($sGUID) . '\',\'true\',\'\',\'\',\'' . _j($sName) . '\',\'' . _j($sImageURL) . '\')"';
	}
	else
	{
	echo ' disabled="true"';
	}
	echo '>';
	echo '  <div class="objectpath-img-image">';
	echo '    <img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '" alt="" title=""/>';
	if ($bLocked)
	{
	echo '    <span class="objectpath-img-span element16-lockedoverlay" alt="" title="' . _h($sResType) . ' ' . _glt('is locked') . '"></span>';
	}
	echo '  </div>';
	echo _h($sName);
	echo '</div>';
	}
	if ( $sResType === 'Package' )
	{
	$sCurrImageURL = SafeGetArrayItem2Dim($aRows, 0, 'imageurl');
	}
	}
	}
	if ($bShowHomeWithoutLink)
	{
	echo '<div class="contextmenu-item"><img alt="Home" src="images/spriteplaceholder.png" class="mainsprite-root">' . _h($sHome) . '</div>';
	}
	echo '</div>';
	echo PHP_EOL;
?>