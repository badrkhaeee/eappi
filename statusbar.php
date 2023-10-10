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
	SafeStartSession();
	$sReviewSession = isset($_POST['reviewguid']) ? $_POST['reviewguid'] : '';
	$sReviewSessionName = isset($_POST['reviewname']) ? $_POST['reviewname'] : '';
	$sGUID = SafeGetInternalArrayParameter($_POST, 'guid');
	$sAction = SafeGetInternalArrayParameter($_POST, 'action');
	$sResType = GetResTypeFromGUID($sGUID);
	BuildOSLCConnectionString();
	$sURL	= $g_sOSLCString;
	if ( strIsEmpty($sReviewSession) )
	{
	$sReviewSession = SafeGetInternalArrayParameter($_SESSION, 'review_session');
	$sReviewSessionName = SafeGetInternalArrayParameter($_SESSION, 'review_session_name');
	}
	$sDisplayReviewName = LimitDisplayString($sReviewSessionName, 30);
	echo '<div id="main-statusbar-tb">';
	echo '<div id="main-statusbar-tb-row">';
	echo '<div id="main-statusbar-left">';
	if($sResType === 'ModelRoot' ||
	$sResType === 'Package' ||
	$sResType === 'Diagram' ||
	$sResType === 'Element' ||
	$sResType === '')
	{
	if (IsSessionSettingTrue('show_browser'))
	{
	echo '<div id="statusbar-browser-button" title="' . _glt('Hide Browser') . '" onclick="ShowBrowser()"><div><div id="mainsprite-navbarbrowsericon" class="mainsprite-navbarbrowsercollapse">&#160;</div></div></div>';
	}
	else
	{
	echo '<div id="statusbar-browser-button" title="' . _glt('Show Browser') . '" onclick="ShowBrowser()"><div><div id="mainsprite-navbarbrowsericon" class="mainsprite-navbarbrowserexpand">&#160;</div></div></div>';
	}
	if (IsSessionSettingTrue('show_propertiesview'))
	{
	echo '<div id="statusbar-properties-button" title="'._glt('Hide Properties View').'" onclick="ShowPropertiesView()"><div id="mainsprite-navbarpropsicon" class="mainsprite-navbarpropscollapse"></div></div>';
	}
	else
	{
	echo '<div id="statusbar-properties-button" title="'._glt('Show Properties View').'" onclick="ShowPropertiesView()"><div id="mainsprite-navbarpropsicon" class="mainsprite-navbarpropsexpand"></div></div>';
	}
	}
	echo '</div>';
	if($sAction !== 'icon_refresh')
	{
	echo '        <div class="statusbar-item-about-cell">';
	echo '            <div class="statusbar-item-about" onclick="OnShowAboutPage(\'' . g_csWebEAVersion . '\')">';
	echo '                <img src="images/spriteplaceholder.png" class="mainsprite-about" alt="A" title="' . _glt('Show About screen') . '">';
	echo '            </div>';
	echo '        </div>';
	if(isset($_SESSION['ios_scroll']))
	{
	$sDevice = '';
	$sScrollState = '';
	$sScrollClass = '';
	$bIOSScroll = SafeGetInternalArrayParameter($_SESSION, 'ios_scroll');
	$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	if($iPod)
	$sDevice = "iPod";
	else if($iPhone)
	$sDevice = "iPhone";
	else if($iPad)
	$sDevice = "iPad";
	if($bIOSScroll)
	{
	$sScrollState = 'On';
	$sScrollClass = 'propsprite-greendot';
	}
	else if ($bIOSScroll === false)
	{
	$sScrollState = 'Off';
	$sScrollClass = 'propsprite-reddot';
	}
	else
	{
	$sScrollState = 'Off';
	$sScrollClass = 'propsprite-reddot';
	}
	echo '<div class="statusbar-scroll-mode-cell">';
	echo '<div class="statusbar-item-scroll-mode" onclick="ToggleScrolling()">';
	echo '<img alt="" src="images/spriteplaceholder.png" id="scroll-mode-icon" class="'.$sScrollClass.'"/>&nbsp;';
	echo '<div style="display: inline-block;padding-right: 4px;">'.$sDevice.' Diagram Scroll:</div>';
	echo '<div id="scroll-mode">';
	echo $sScrollState;
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
	if ( !strIsEmpty($sReviewSession) )
	{
	echo '    <div class="statusbar-item-review-cell">';
	echo '	<div class="statusbar-item-review" onclick="LoadObject(\'' . _j($sReviewSession) . '\',\'false\',\'\',\'\',\'' . _j($sReviewSessionName) . '\',\'images/element16/review.png\')">';
	echo '	<img alt="" src="images/spriteplaceholder.png" class="statusbar-review-icon propsprite-review"/>&nbsp;';
	echo 	_glt('Review') . '<a class="statusbar-review-name" > "' . _h($sDisplayReviewName) . '"</a>';
	echo '	</div>';
	echo '    </div>';
	}
	if (IsSessionSettingTrue('show_chat'))
	{
	include('chatnotification.php');
	}
	}
	echo '    </div>';
	echo '</div>';
	echo PHP_EOL;
?>