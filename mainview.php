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
	SafeStartSession();
	if (!isset($webea_page_parent_index))
	{
	AllowedMethods('POST');
	}
	CheckAuthorisation();
	$sObjectGUIDEnc = SafeGetInternalArrayParameter($_POST, 'objectguid');
	$sObjectGUID 	= urldecode($sObjectGUIDEnc);
	$sResType 	= GetResTypeFromGUID($sObjectGUID);
	$sObjectHasChild = SafeGetInternalArrayParameter($_POST, 'haschild');
	$sLinkType 	= SafeGetInternalArrayParameter($_POST, 'linktype');
	$sObjectName	= SafeGetInternalArrayParameter($_POST, 'objectname');
	$sObjectImageURL = SafeGetInternalArrayParameter($_POST, 'imageurl');
	$sObjectHyper	= SafeGetInternalArrayParameter($_POST, 'hyper');
	$g_sViewingMode	= '0';
	$webea_page_parent_mainview = true;
	$sCurrModelNo = SafeGetInternalArrayParameter($_SESSION, 'model_no', '');
	$bSupportsMiniProps = false;
	$bShowingBrowser = false;
	if ( IsSessionSettingTrue('show_browser') &&
	 ($sLinkType   !== 'edit') &&
	 ($sObjectGUID !== 'matrix') &&
	 ($sObjectGUID !== 'matrixprofiles') &&
	 ($sObjectGUID !== 'modelmail') &&
	 ($sObjectGUID !== 'collaborate') &&
	 ($sObjectGUID !== 'search') &&
	 ($sObjectGUID !== 'searchresults') &&
	 ($sObjectGUID !== 'watchlist') &&
	 ($sObjectGUID !== 'watchlistconfig') &&
	 ($sObjectGUID !== 'watchlistresults') &&
	 ($sResType    !== "Connector"))
	{
	if($sObjectGUID !== 'initialize')
	{
	if($sObjectHyper === 'browser' && (!strIsTrue($sObjectHasChild) && $sResType !== "Package" && $sResType !== "ModelRoot"))
	{
	}
	else
	{
	include('browser.php');
	}
	}
	$bShowingBrowser = true;
	}
	if ( $sObjectGUID === 'initialize' )
	{
	}
	elseif ( $sObjectGUID === 'search' )
	{
	include('mainsearch.php');
	}
	elseif ( $sObjectGUID === 'searchresults' )
	{
	include('mainsearchresults.php');
	}
	elseif ( $sObjectGUID === 'matrixprofiles' )
	{
	include('mainmatrixprofiles.php');
	}
	elseif ( $sObjectGUID === 'matrix' )
	{
	include('mainmatrix.php');
	}
	elseif ( $sObjectGUID === 'collaborate' )
	{
	include('maincollaborate.php');
	}
	elseif ( $sObjectGUID === 'watchlist' )
	{
	include('mainwatchlist.php');
	}
	elseif ( $sObjectGUID === 'watchlistconfig' )
	{
	include('mainwatchlistconfig.php');
	}
	elseif ( $sObjectGUID === 'watchlistresults' )
	{
	include('mainwatchlistresults.php');
	}
	else
	{
	echo '<div id="mainview-busy-loader" class="'. GetShowBrowserMinPropsStyleClasses() . '" style="display: none;"><img src="images/navbarwait.gif" alt="" class="miniprops-spinner" width="26" height="26"></div>';
	if ( $sLinkType === "document" || $sLinkType === "encryptdoc" )
	{
	include('mainlinkeddoc.php');
	}
	elseif ( $sResType === "Connector" )
	{
	include('mainconnector.php');
	}
	elseif ( $sLinkType === 'props' || ($sResType==='Element' && $sLinkType !== 'child') )
	{
	include('mainproperties.php');
	$bSupportsMiniProps = true;
	}
	else
	{
	if ($sResType!=='Element' || $sObjectHasChild==='true' || ($sResType==='Element' && $sLinkType === 'child') )
	{
	if ($sResType === "Diagram")
	{
	$sDiagramLayoutNo = SafeGetInternalArrayParameter($_SESSION, 'diagramlayout', '1');
	if ($sDiagramLayoutNo === '2')
	{
	include('maindiagramlist.php');
	}
	elseif ($sDiagramLayoutNo === '3')
	{
	include('maindiagramspecman.php');
	}
	else
	{
	include('maindiagramimage.php');
	}
	$bSupportsMiniProps = true;
	}
	else if ($sResType === "ModelRoot" || $sResType === "Package" || $sResType === "Element" || strIsEmpty($sResType))
	{
	$bSupportsMiniProps = true;
	if ( $bShowingBrowser )
	{
	include('mainproperties.php');
	}
	else
	{
	if ($sLinkType === 'favorites')
	{
	$webea_page_parent_browser = true;
	include('./data_api/get_favorites.php');
	echo '<div class="mainview-favorites">';
	WriteFavorites($aData);
	echo '</div>';
	}
	else
	{
	$sMainLayoutNo = SafeGetInternalArrayParameter($_SESSION, 'mainlayout', '1');
	if ($sMainLayoutNo === '2')
	{
	include('mainpackagelist.php');
	}
	elseif ($sMainLayoutNo === '3')
	{
	include('mainpackagespecman.php');
	}
	else
	{
	include('mainpackageicon.php');
	}
	}
	}
	}
	}
	}
	}
	echo '<div id="page-built-with-model-no">' . _h($sCurrModelNo) . '</div>';
	if (IsSessionSettingTrue('show_propertiesview') &&
	($sLinkType   !== 'edit') &&
	($sObjectGUID !== 'searchresults') &&
	($bSupportsMiniProps))
	{
	echo '<div id="miniprops-busy-loader" class="'.GetShowBrowserMinPropsStyleClasses().'"><img src="images/navbarwait.gif" alt="" class="miniprops-spinner" height="26" width="26"></div>';
	echo '  <div id="main-mini-properties-view" class="'.GetShowBrowserMinPropsStyleClasses().'">';
	echo '  <div id="mini-properties-view-section">';
	include('miniproperties.php');
	echo '  </div>';
	echo '</div>';
	}
	echo '<div id="webea-viewing-mode" class="w3-hide">' . $g_sViewingMode . '</div>';
	echo '<div id="webea-miniprops-navigates" class="w3-hide">' . (strIsTrue(SafeGetInternalArrayParameter($_SESSION, 'miniprops_navigates', 'true')) ? '1' : '0') . '</div>';
	echo '<div id="webea-navigate-to-diagram" class="w3-hide">' . (strIsTrue(SafeGetInternalArrayParameter($_SESSION, 'navigate_to_diagram', 'true')) ? '1' : '0') . '</div>';
	echo '<div id="webea-last-oslc-error-code" class="w3-hide">' . $g_sLastOSLCErrorCode . '</div>';
	echo '<div id="webea-last-oslc-error-msg" class="w3-hide">' . $g_sLastOSLCErrorMsg . '</div>';
	echo BuildSystemOutputDataDIV();
	unset($webea_page_parent_mainview);
	function WritePackageViewButtons($sMainLayoutNo)
	{
	$sIconImage = 'id="package-iconview-icon"';
	$sListImage = 'id="package-listview-icon"';
	$sNotesImage = 'id="package-notesview-icon"';
	if ($sMainLayoutNo === '1')
	{
	$sIconSelected = 'class="package-icon-selected"';
	$sListSelected = '';
	$sNotesSelected = '';
	}
	else if ($sMainLayoutNo === '2')
	{
	$sIconSelected = '';
	$sListSelected = 'class="package-icon-selected"';
	$sNotesSelected = '';
	}
	else if ($sMainLayoutNo === '3')
	{
	$sIconSelected = '';
	$sListSelected = '';
	$sNotesSelected = 'class="package-icon-selected"';
	}
	else
	{
	$sIconSelected = '';
	$sListSelected = '';
	$sNotesSelected = '';
	}
	echo '<div id="main-package-list-options">';
	echo '<div id="package-iconview-button" '.$sIconSelected.' onclick="SetMainLayout(\'1\')" title="Icon View">';
	echo '<div '.$sIconImage.'>';
	echo '</div>';
	echo '</div>';
	echo '<div id="package-listview-button" '.$sListSelected.' onclick="SetMainLayout(\'2\')" title="List View">';
	echo '<div '.$sListImage.'>';
	echo '</div>';
	echo '</div>';
	echo '<div id="package-notesview-button" '.$sNotesSelected.' onclick="SetMainLayout(\'3\')" title="Notes View">';
	echo '<div '.$sNotesImage.'>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
?>