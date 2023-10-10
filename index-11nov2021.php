<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	error_reporting(E_ERROR | E_WARNING | E_NOTICE);
	require_once __DIR__ . '/modulecheck.php';
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	if(isset($_GET['config']))
	{
	header("location: includes/config.php");
	}
	SafeStartSession();
	$sAutoLoadModel	= SafeGetInternalArrayParameter($_GET, 'm', '');
	if (!filter_var($sAutoLoadModel, FILTER_VALIDATE_INT))
	{
	$sAutoLoadModel = '';
	}
	$sAutoLoadObject	= ValidateGUID(SafeGetInternalArrayParameter($_GET, 'o', ''));
	$bRequiresLogin = false;
	if (isset($_SESSION['authorized']) === false)
	{
	$bRequiresLogin = true;
	}
	else
	{
	$sCurrModelNo = SafeGetInternalArrayParameter($_SESSION, 'model_no', '');
	$sCurrModelNo = strval($sCurrModelNo);
	if ( !strIsEmpty($sAutoLoadModel) )
	{
	if ( $sAutoLoadModel !== $sCurrModelNo )
	{
	if (session_destroy())
	{
	$bRequiresLogin = true;
	}
	}
	}
	}
	if ($bRequiresLogin)
	{
	$sURLLocation = 'location: login.php';
	$sURLParameters = '';
	if ( !strIsEmpty($sAutoLoadModel) )
	{
	$sURLParameters .= 'm=' . $sAutoLoadModel;
	}
	if ( !strIsEmpty($sAutoLoadObject) )
	{
	if ( !strIsEmpty($sURLParameters) )
	$sURLParameters .= '&';
	$sURLParameters .= 'o=' . $sAutoLoadObject;
	}
	if ( !strIsEmpty($sURLParameters) )
	{
	$sURLLocation .= '?' . $sURLParameters;
	}
	header($sURLLocation);
	exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>WebEA<?php echo ' - ' . _h($_SESSION['model_name']);?></title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link type="text/css" rel="stylesheet" href="styles/webea.css?v=<?php echo filemtime('styles/webea.css') ; ?>" />
	<link type="text/css" rel="stylesheet" href="styles/jquery.datepick.blue.css" />
	<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico?<?php echo filemtime('favicon.ico') ?>" />
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/webea.js"></script>
	<script src="js/nicedit.js"></script>
	<script src="js/jquery.plugin.js"></script>
	<script src="js/jquery.datepick.js"></script>
</head>
<body>
	<?php
	$webea_page_parent_index = true;
	$modelName = SafeGetInternalArrayParameter($_SESSION, 'model_name');
	$_POST['objectguid'] = 'initialize';
	$sLoginParam = '';
	if ( (isset($_SESSION['load_object'])) && ($_SESSION['load_object'] !== '') )
	{
	$sLoginParam = '?m=' . SafeGetInternalArrayParameter($_SESSION, 'model_no') . '&o=' . SafeGetInternalArrayParameter($_SESSION, 'load_object');
	unset($_SESSION['load_object']);
	}
	echo '<div id="main-tl-swoosh"><img src="images/spriteplaceholder.png" class="mainsprite-procloudswoosh" alt=""/></div>';
	
	echo '<header>';
	echo '	<div id="header-left">';
	echo '	<div id="main-tl-logo"><img src="images/logo-full.png" alt="" height="32" width="111"/></div>';
	include('hamburger.php');
	echo '	</div>';
	echo '	<div id="header-right">';
	echo '	<div id="project-title" >';
	echo '<div id="main-db-icon"><img src="images/db-icon.png" alt="" height="16" width="16"/></div>';
	echo '<div class="mainsprite-home32white" onclick="LoadHome(false)"></div><div id="project-title-text"><a>' . _h($modelName) . '</a></div></div>';
	echo '	</div>';
	echo '</header>';
	
	if(IsSessionSettingTrue('favorites_as_home'))
	{
	$webea_page_parent_browser = true;
	include('./data_api/get_favorites.php');
	if (strIsTrue($bHasFavorites))
	{
	$_SESSION['default_diagram'] = 'favorites';
	}
	}
	echo '<div class="w3-hide">';
	echo '  <div id="model-default-diagram">' . _h($_SESSION['default_diagram']) . '</div>';
	echo '  <div id="model-lastpage-timeout">' . _h($_SESSION['lastpage_timeout']) . '</div>';
	echo '  <div id="login-parameters">' . _h($sLoginParam) .'</div> ';
	echo '  <div id="show-chat">' . _h($_SESSION['show_chat']) .'</div> ';
	echo '  <div id="chat-notify-freq">' . _h($_SESSION['chat_notify_sec']) .'</div> ';
	include('stringsforjs.php');
	echo '</div>' . PHP_EOL;
	include 'checkbrowser.php';
	echo '<div id="webea-main-content">';
	echo '<div id="main-navbar">' . PHP_EOL;
	include('navbar.php');
	echo '</div>' . PHP_EOL;
	$bShowSystemOutput =  IsSessionSettingTrue('show_system_output');
	echo '<div id="main-contents" ' . (($bShowSystemOutput)? 'class="show_sysoutput"' : '') .  '>' . PHP_EOL;
	echo '<div id="main-busy-loader1"><img src="images/mainwait.gif" alt="" class="main-spinner" height="62" width="62"></div>';
	echo '<div id="main-contents-sub">' . PHP_EOL;
	echo '<div id="main-content-center">';
	include('mainview.php');
	echo '</div>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
	echo '<div id="main-statusbar" ' . (($bShowSystemOutput)? 'class="show_sysoutput"' : '') .  '>';
	include('statusbar.php');
	echo '</div>' . PHP_EOL;
	echo '<div id="main-page-overlay">&nbsp;</div>';
	echo '<div id="full-busy-loader"><img src="images/mainwait.gif" alt="" class="main-spinner" height="62" width="62"></div>';
	echo '<div id="prompt-overlay">&nbsp;</div>';
	echo '<div id="webea-session-timeout">';
	echo '<div class="session-timeout-section">';
	echo '<div class="session-timeout-image"><img src="images/spriteplaceholder.png" class="mainsprite-warning" alt=""></div>';
	echo '<div class="session-timeout-line1">Session Expired!</div>';
	echo '<div class="session-timeout-line2">Your session has timed out. Click <a href="logout.php">here</a> to re-login.</div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-about-dialog">';
	echo '<div class="webea-about-dialog-title">' . _glt('About WebEA') . '<input class="webea-dialog-close-button" type="button" value="" onclick="OnClickClosePopupDialog(\'#webea-about-dialog\')"/></div>';
	echo '<div class="webea-about-dialog-body">';
	echo '<div><img src="images/spriteplaceholder.png" class="mainsprite-webealogos" alt=""></div>';
	echo '<div class="webea-about-dialog-appversion"><div id="webea-about-line-value-appversion"></div></div>';
	echo '<div class="webea-about-dialog-copyright">Copyright &copy; ' . g_csCopyRightYears . ' Sparx Systems Pty Ltd.  All rights reserved.</div>';
	echo '<div class="webea-about-dialog-agreement">The use of this product is subject to the terms of the End User License Agreement (EULA), unless otherwise specified.</div>';
	echo '<div class="webea-about-line-header collapsible-plusminussection-closed" onclick="OnTogglePlusMinusState(this, \'webea-about-sspcs-section\')"><div class="collapsible-block-text">' . _glt('Sparx Systems Pro Cloud Services') . '</div></div>';
	echo '<div id="webea-about-sspcs-section" style="display: none;">';
	echo '<div id="webea-about-line-author"><div class="webea-about-line-label">' . _glt('Author') . '</div><div class="webea-about-line-value" id="webea-about-line-value-author"></div></div>';
	echo '<div id="webea-about-line-server"><div class="webea-about-line-label">' . _glt('Host server') . '</div><div class="webea-about-line-value" id="webea-about-line-value-server"></div> </div>';
	echo '<div id="webea-about-line-pcsversion"><div class="webea-about-line-label">' . _glt('PCS Version') . '</div><div class="webea-about-line-value" id="webea-about-line-value-pcsversion"></div> </div>';
	echo '<div id="webea-about-line-sscsversion"><div class="webea-about-line-label">' . _glt('OSLC Version') . '</div><div class="webea-about-line-value" id="webea-about-line-value-sscsversion"></div> </div>';
	echo '<div id="webea-about-line-sscslicense"><div class="webea-about-line-label">' . _glt('License') . '</div><div class="webea-about-line-value" id="webea-about-line-value-sscslicense"></div> </div>';
	echo '<div id="webea-about-line-sscslicenseexpiry"><div class="webea-about-line-label">' . _glt('License Expiry') . '</div><div class="webea-about-line-value" id="webea-about-line-value-sscslicenseexpiry"></div> </div>';
	echo '</div>';
	echo '<div class="webea-about-line-header collapsible-plusminussection-closed" onclick="OnTogglePlusMinusState(this, \'webea-about-model-section\')"><div class="collapsible-block-text">' . _glt('Model details') . '</div></div>';
	echo '<div id="webea-about-model-section" style="display: none;">';
	echo '<div id="webea-about-line-port"><div class="webea-about-line-label">' . _glt('Read only') . '</div><div class="webea-about-line-value" id="webea-about-line-value-readonly"></div> </div>';
	echo '<div id="webea-about-line-security"><div class="webea-about-line-label">' . _glt('User security') . '</div><div class="webea-about-line-value" id="webea-about-line-value-security"></div> </div>';
	echo '<div id="webea-about-line-user"><div class="webea-about-line-label">' . _glt('User') .'</div><div class="webea-about-line-value" id="webea-about-line-value-user"></div> </div>';
	echo '<div id="webea-about-line-review"><div class="webea-about-line-label">' . _glt('Reviewing') . '</div><div class="webea-about-line-value" id="webea-about-line-value-review"></div> </div>';
	echo '</div>';
	echo '<div class="webea-about-line-cancel"><input class="webea-main-styled-button webea-about-cancel" type="button" value="' . _glt('Close') . '" onclick="OnClickClosePopupDialog(\'#webea-about-dialog\')"/> </div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-messagebox-dialog">';
	echo '<div class="webea-messagebox-title"><div id="webea-messagebox-title-text">' . _glt('WebEA error') . '</div><input class="webea-dialog-close-button" type="button" value="" onclick="OnClickClosePopupDialog(\'#webea-messagebox-dialog\')"/></div>';
	echo '<div class="webea-messagebox-body">';
	echo '<div class="webea-messagebox-line"><div class="webea-messagebox-image"><img src="images/spriteplaceholder.png" class="mainsprite-warning margin-right-10" alt=""></div></div>';
	echo '<div id="webea-messagebox-line-message"><div class="webea-messagebox-line-value" id="webea-messagebox-line-value-message"></div> </div>';
	echo '<div class="webea-about-line-cancel"><input class="webea-main-styled-button webea-about-cancel" type="button" value="' . _glt('OK') . '" onclick="OnClickClosePopupDialog(\'#webea-messagebox-dialog\')"/> </div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="kb-shortcut-dialog">';
	echo '<div class="dialog-header">';
	echo '<div class="dialog-header-title">Keyboard Shortcuts</div>';
	echo '<button class="dialog-header-close-button" onclick="CloseKBShortcutDialog()">';
	echo '<div class="close-icon button-icon icon16">';
	echo '</div>';
	echo '</button>';
	echo '</div>';
	echo '<div class="dialog-body">';
	$sDetail = '';
	$sDetail .=  '<div class="kb-shortcuts-grouping">';
	$aShortcuts = [
	['?', 'Display this dialog'],
	['<escape>', 'Close a dialog'],
	['1', 'Toggle Browser Open/Closed'],
	['2', 'Toggle Properties View Open/Closed'],
	['s', 'Custom Search'],
	['w', 'Watchlist'],
	['h', 'Load this model\'s home page'],
	['m', 'Add an object (when displaying the Browser or Package List)']
	];
	foreach ($aShortcuts as $a)
	{
	$sLabel = SafeGetArrayItem1Dim($a, 'label');
	$sFieldName = SafeGetArrayItem1Dim($a, 'value');
	$sDetail .= '<div class="kb-shortcuts-row">';
	$sDetail .= '<div class="kb-shortcuts-field">';
	$sDetail .= _h($a[0]);
	$sDetail .= '</div>';
	$sDetail .= '<div class="kb-shortcuts-value">';
	$sDetail .= _h($a[1]);
	$sDetail .= '</div>';
	$sDetail .= '</div>';
	}
	$sDetail .= '</div>';
	echo $sDetail;
	echo '<div class="kb-shortcut-notes">Note: Some features / options may not be accessible due to WebEA\'s configuration for this model</div>';
	echo '</div>';
	echo '<div class="dialog-footer">';
	echo '<div class="dialog-buttons-container">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-close" type="submit" onclick="CloseKBShortcutDialog()" value="' . _glt('Close') . '">';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-success-message">';
	echo '<div class="webea-success-message-text" id="webea-success-message-text"></div>';
	echo '</div>';
	echo '<div id="webea-error-message">';
	echo '<div class="webea-error-message-text" id="webea-error-message-text"></div>';
	echo '<div class="webea-error-close-button" onclick="OnClickClosePopupDialog(\'#webea-error-message\')"><div class="webea-error-close-icon"></div></div>';
	echo '</div>';
	if ((g_csOSLCVersion !== $_SESSION['oslc_version']) && (!isset($_SESSION['hide_mismatch_warning'])))
	{
	echo '<div id="webea-display-warning-message" class="oslc-mismatch-warning" style="display: block;">';
	echo '<div class="webea-display-warning-message-text" id="webea-display-warning-message-text">';
	echo _glt('Version mismatch') . ' <a href="'.g_csHelpLocation.'model_repository/webea_troubleshoot.html">[Help]</a><a href="javascript:ToggleVisibility(&quot;.oslc-mismatch-warning&quot;)" style="float: right;">'._glt('Close').'</a>';
	echo '<br><br>';
	echo str_replace('%VERSION%', g_csWebEAVersion, _glt('WebEA Version: xx'));
	echo '<br>';
	echo str_replace('%VERSION%', $_SESSION['oslc_version'], _glt('OSLC Version: xx'));
	echo '<br><br>';
	echo str_replace('%VERSION%', g_csOSLCVersion, _glt('Intended OSLC version: xx'));
	echo '<br>';
	echo '</div>';
	echo '</div>';
	$_SESSION['hide_mismatch_warning'] = true;
	}
	echo '<div id="webea-show-link-dialog">';
	echo '<div class="webea-show-link-title"><div id="webea-show-link-title-text">' . _glt('Link to WebEA item') . '</div><input class="webea-dialog-close-button" type="button" value="" onclick="OnClickClosePopupDialog(\'#webea-show-link-dialog\')"/></div>';
	echo '<div class="webea-show-link-body">';
	echo '<div class="webea-show-link-line" style="padding-top: 16px;">' . _glt('GUID of the current item') . '</div>';
	echo '<div class="webea-show-link-line">';
	echo '<input id="webea-show-link-textarea" class="webea-main-styled-textbox-small" type="text" maxlength="40">';
	echo '<div class="copy-button" title="Copy GUID to clipboard" onclick="CopyText($(this).prev())">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="copy-icon">';
	echo '</div>';
	echo '</div>';
	echo '<div class="webea-show-link-line" style="padding-top: 16px;">' . _glt('Full URL') . '</div>';
	echo '<div class="webea-show-link-line" style="padding-bottom: 16px;">';
	echo '<input id="webea-show-fulllink-textarea" class="webea-main-styled-textbox-small" type="text" maxlength="100">';
	echo '<div class="copy-button" title="Copy URL to clipboard" onclick="CopyText($(this).prev())">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="copy-icon">';
	echo '</div>';
	echo '</div>';
	echo '<div class="webea-about-line-cancel"><input class="webea-main-styled-button webea-about-cancel" type="button" value="' . _glt('Close') . '" onclick="OnClickClosePopupDialog(\'#webea-show-link-dialog\')"/> </div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-goto-link-dialog">';
	echo '<div class="webea-goto-link-title"><div id="webea-goto-link-title-text">' . _glt('Goto WebEA item') . '</div><input class="webea-dialog-close-button" type="button" value="" onclick="OnClickClosePopupDialog(\'#webea-goto-link-dialog\')"/></div>';
	echo '<div class="webea-goto-link-body">';
	echo '<div class="webea-goto-link-line" style="padding-top: 20px;">' . _glt('Paste a WebEA GUID below') . '</div>';
	echo '<div class="webea-goto-link-line"><input id="webea-goto-link-textarea" class="webea-main-styled-textbox-small" type="text" maxlength="40" onkeypress="OnGotoGUIDTextKeypress(event)"></div>';
	echo '<div class="webea-about-line-cancel"><input class="webea-main-styled-button webea-about-cancel" type="button" value="' . _glt('Go') . '" onclick="OnWebEAGotoGUID()"/> </div>';
	echo '</div>';
	echo '</div>';
	WriteDialog();
	echo '<div class="webea-dialog" id="webea-prompt-unsaved-changes-dialog" style="height:150px;">';
	echo '<div class="webea-dialog-title">';
	echo '<div class="webea-dialog-title-text" id="webea-prompt-unsaved-changes-title-text">Unsaved Changes</div>';
	echo '</div>';
	echo '<div class="webea-dialog-body" style="padding-bottom:0px;">';
	echo '<div class="webea-dialog-line" style="padding-top: 20px;">Warning: You have unsaved changes. Would you like to save these changes?</div>';
	echo '</div>';
	echo '<div class="webea-dialog-footer">';
	echo '<div class="dialog-buttons-container"><input id="save-changes-yes-button" class="webea-main-styled-button dialog-button" value="Yes" onclick="ConfirmSaveAndClose()" type="button"><input class="webea-main-styled-button dialog-button" value="No" onclick="DiscardAndClose()" style="margin-left: 16px;" type="button"></div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-stereotype-list">';
	echo '<div class="webea-stereotype-list-body">';
	echo '<div class="webea-stereotype-list-hdr">' . _glt('Stereotypes') . '</div>';
	echo '<div id="webea-stereotype-list-items">&nbsp;</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	if ($bShowSystemOutput)
	{
	echo '<div style="bottom:0px; height:160px; width:100%; position:absolute; display:inline-block; background-color: white;border-radius: 4px;" onkeypress="OnKeyUpSystemOutput(event)">';
	echo '<div style="overflow: auto; height:100%; width:100%;">';
	echo '<div class="sysout-btn-wrapper">';
	echo '<button class="sysout-delete-btn" title="Clear" onclick="OnClickClearSystemOutput()"><img src="images/spriteplaceholder.png" class="mainsprite-sysout-delete" alt=""/></button>';
	echo '<button class="sysout-copy-btn" title="Copy All" onclick="OnClickCopySystemOutput()"><img src="images/spriteplaceholder.png" class="mainsprite-sysout-copy" alt=""/></button>';
	echo '</div>';
	echo '<div id="webea-system-output">';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
	unset($webea_page_parent_index);
?>
<script>
	$(document).ready(function(event)
	{
	var sPageModelNo = $("#page-built-with-model-no").text();
	var sLastPage = GetCookieValue('webea_lastpage_' + sPageModelNo);
	var sURLParameters = window.location.search;
	var bShowChat = $("#show-chat").html();
	var iChatNotifyFreq, iChatNotifyFreqMS;
	if (sURLParameters === '' && ($("#login-parameters").text() !== ''))
	{
	sURLParameters = $("#login-parameters").text();
	sLastPage = 'loadGUID';
	g_bIsLoadGUID = true;
	}
	var sCurrPage = GetCurrentHistoryState();
	if ( typeof(sCurrPage) !== 'undefined' && (sLastPage !== null && sLastPage !== '') )
	{
	if ( sURLParameters !== '' )
	{
	var sObject2Load = '';
	var sModelNo2Load = '';
	if ( sURLParameters.substring(0,1) === '?' )
	{
	sURLParameters = sURLParameters.substring(1);
	}
	var a = sURLParameters.split("&");
	if (a.length > 0)
	{
	for (var i=0; i < a.length; i++)
	{
	if (a[i].substring(0,2)==='m=')
	{
	sModelNo2Load = a[i].substring(2);
	}
	if (a[i].substring(0,2)==='o=')
	{
	sObject2Load = a[i].substring(2);
	}
	}
	if ( sObject2Load!=='' && sModelNo2Load!=='' )
	{
	if (sPageModelNo===sModelNo2Load)
	{
	LoadObjectByGUID(sObject2Load, true);
	}
	else
	{
	WebeaAlert(GetTranslateString('unable_to_load_objects_for_other_models'));
	}
	}
	else
	{
	WebeaAlert(GetTranslateString('invalid_full_webea_url'));
	}
	}
	return;
	}
	}
	if ( sLastPage === null || sLastPage === '' )
	{
	var sURLParameters = window.location.search;
	var sObject2Load = '';
	var a = sURLParameters.split("&");
	if (a.length > 0)
	{
	for (var i=0; i < a.length; i++)
	{
	if (a[i].substring(0,2)==='o=')
	{
	sObject2Load = a[i].substring(2);
	}
	}
	}
	if ( sObject2Load !== '' )
	{
	var sPrefix = String(sObject2Load).substr(0,3);
	if (sPrefix === 'mr_'  || sPrefix === 'pk_' || sPrefix === 'dg_' || sPrefix === 'el_')
	{
	ReplaceHistoryState('', '', '', '', '', '', 'WebEA - ' + GetTranslateString('jsmess_modelroot'), 'index.php');
	LoadDiagramObject(sObject2Load, false);
	}
	else
	{
	ReplaceHistoryState('', '', '', '', '', '', 'WebEA - ' + GetTranslateString('jsmess_modelroot'), 'index.php');
	LoadObjectByGUID(sObject2Load, false);
	}
	}
	else
	{
	var bSomethingLoaded = LoadHome(true);
	}
	}
	else
	{
	var sCurrPage = GetCurrentHistoryState();
	if ( sCurrPage == sLastPage )
	{
	LoadObjectFromString(sLastPage, false);
	g_bAfterRefresh = true;
	}
	else
	{
	ReplaceHistoryState('', '', '', '', '', '', 'WebEA - ' + GetTranslateString('jsmess_modelroot'), 'index.php');
	LoadObjectFromString(sLastPage, true);
	}
	ClearLastPage();
	}
	g_TimerRef = window.setInterval(TimerCheck, 15000);
	if(bShowChat === 'true')
	{
	iChatNotifyFreq = $("#chat-notify-freq").html();
	if(isNaN(iChatNotifyFreq))
	{
	iChatNotifyFreq = 30;
	}
	if (iChatNotifyFreq < 10)
	{
	iChatNotifyFreq = 10;
	}
	iChatNotifyFreqMS = Number(iChatNotifyFreq) * 1000;
	g_ChatTimerRef = window.setInterval(ChatRefreshNotification, iChatNotifyFreqMS);
	}
	});
	function OnClickClosePopupDialog(sDialogName)
	{
	$( "#main-page-overlay" ).hide();
	$( sDialogName ).hide();
	}
	window.addEventListener('popstate', function(event) {
	OnIndexPopState(event);
	}, false);
	window.addEventListener('beforeunload', function(event) {
	OnStoreLastPage(event);
	});
	$("body").on('click', '.object-link', function()
	{
	if (isDefined($(this).attr("object")))
	{
	objectDetails = $.parseJSON($(this).attr("object"));
	LoadObject(
	objectDetails["id"],
	objectDetails["has-child"],
	objectDetails["link-type"],
	objectDetails["hyper"],
	objectDetails["object-name"],
	objectDetails["image-url"]);
	}
	else
	{
	LoadObject(
	$(this).attr("id"),
	$(this).attr("has-child"),
	$(this).attr("link-type"),
	$(this).attr("hyper"),
	$(this).attr("object-name"),
	$(this).attr("image-url"));
	}
	});
	$("body").on('click', '.diagram-object-link', function()
	{
	LoadDiagramObject($(this).attr("object"));
	});
	$(document).on("click", '.tr-expand', function(event) {
	var isExpanded = false;
	isExpanded = $(this).attr("expanded");
	if(isExpanded === "true")
	{
	$(this).next("tr").hide();
	$(this).attr("expanded",false);
	$(this).children().first().children().first().removeClass("collapse-icon").addClass("expand-icon");
	}
	else
	{
	$(this).next("tr").show();
	$(this).attr("expanded",true);
	$(this).children().first().children().first().addClass("collapse-icon").removeClass("expand-icon");
	}
	});
</script>
</body>
</html>