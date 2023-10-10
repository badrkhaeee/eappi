<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	require_once __DIR__ . '/htmlpurifier.php';
	SafeStartSession();
	AllowedMethods('POST');
	CheckAuthorisation();
	$sHTMLDoc = '';
	include('./data_api/get_linkeddoc.php');
	$sMainClass = 'main-view';
	if (IsSessionSettingTrue('show_browser'))
	{
	$sMainClass = ' class="show-browser main-view"';
	}
	if (strIsEmpty($sHTMLDoc))
	{
	echo '<div id="linked-document-section-empty"' . $sMainClass . '>' . _glt('No content') . '</div>';
	}
	else
	{
	$sHTMLDoc = trim($sHTMLDoc);
	if ( substr($sHTMLDoc, 0, 9) === '<![CDATA[' )
	$sHTMLDoc = substr($sHTMLDoc, 9);
	if ( substr($sHTMLDoc, -3) === ']]>' )
	$sHTMLDoc = substr($sHTMLDoc, 0, -3);
	echo '<div id="linked-document-section"' . $sMainClass . '>';
	echo '<div class="linked-document">' . _hRichText($sHTMLDoc) . '</div>';
	echo '</div>' . PHP_EOL;
	}
	if (IsSessionSettingTrue('show_propertiesview'))
	{
	echo '<div id="miniprops-busy-loader" class=" show-browserminiprops" style="display: none;"><img src="images/navbarwait.gif" alt="" class="miniprops-spinner" width="26" height="26"></div>';
	echo '<div id="main-mini-properties-view" class="'.GetShowBrowserMinPropsStyleClasses().'" style="display: none;"></div>';
	}
?>