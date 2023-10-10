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
	CheckAuthorisation();
	$sSearch	= '';
	$sTerm	= '';
	$sCategory	= '';
	$sSearchType	= '';
	$sWhen	= '';
	$sSearchSettings = SafeGetInternalArrayParameter($_SESSION, 'search_settings');
	if(!strIsEmpty($sSearchSettings))
	{
	$aSearchSettings = json_decode($sSearchSettings);
	$sCategory = $aSearchSettings->{'category'};
	$sSearchType = $aSearchSettings->{'type'};
	$sWhen = $aSearchSettings->{'when'};
	}
	$iErrorCode = http_response_code();
	echo '<div class="default-dialog">';
	$sHeaderTitle = '';
	$sHeaderTitle .= _glt('Custom Search');
	echo WriteDialogHeader($sHeaderTitle);
	echo '<div class="dialog-body">';
	echo '	<form class = "search-form" role="form" onsubmit="OnFormRunCustomSearch(event,\'' . _glt('Search Results') . '\')">';
	echo '	<div class="search-searchcriteria-group">';
	echo '	<div class="search-field-label"><label>' . _glt('Term') . '</label></div>';
	echo '	<div><input id="search-criteria-field" type="text" autocapitalize="none" placeholder="' . _glt('<search term>') . '" name="term" max-length="50"' . (strIsEmpty($sTerm) ? '' : ' value="' . $sTerm . '"' ) . '/></div>';
	echo '	</div>';
	echo '	<div class="search-searchcriteria-group">';
	echo '	<div class="search-field-label"><label>' . _glt('Search for') . '</label></div>';
	echo '	<select id="search-searchfor-combo" name="category" onclick="OnClickSearchFor(this)" class="webea-main-styled-combo">';
	echo '	<option value="diagram"' . (strIsEmpty($sCategory) || $sCategory==='diagram' ? ' selected' : '' ) . '>Diagrams</option>';
	echo '	<option value="element"' . ($sCategory==='element' ? ' selected' : '' ) . '>Elements</option>';
	echo '	<option value="package"' . ($sCategory==='package' ? ' selected' : '' ) . '>Packages</option>';
	if (IsSessionSettingTrue('show_discuss'))
	{
	echo '	<option value="discussion"' . ($sCategory==='discussion' ? ' selected' : '' ) . '>Discussions</option>';
	echo '	<option value="review"' . ($sCategory==='review' ? ' selected' : '' ) . '>Reviews</option>';
	}
	echo '	</select>';
	echo '	</div>';
	echo '	<div class="search-searchcriteria-group">';
	echo '	<div class="search-field-label"><label>' . _glt('Search in') . '</label></div>';
	echo '	<select id="search-searchtype-combo" name="type" class="webea-main-styled-combo">';
	echo '	<option value="name"' . (strIsEmpty($sSearchType) || $sSearchType==='name' ? ' selected' : '' ) . '>Name</option>';
	echo '	<option value="simple"' . ($sSearchType==='simple' ? ' selected' : '' ) . '>Name,&nbsp;Alias&nbsp;and&nbsp;Notes</option>';
	echo '	<option value="author"' . ($sSearchType==='author' ? ' selected' : '' ) . '>Author</option>';
	echo '	<option value="guid"' . ($sSearchType==='guid' ? ' selected' : '' ) . '>ID</option>';
	echo '	</select>';
	echo '	</div>';
	echo '	<div class="search-searchcriteria-group">';
	echo '	<div class="search-field-label"><label>' . _glt('When') . '</label></div>';
	echo '	<div>';
	echo '	<select id="search-when-combo" name="recent" class="webea-main-styled-combo">';
	echo '	<option value="0d"' . ($sWhen==='0d' ? ' selected' : '' ) . '>Today</option>';
	echo '	<option value="-3d"' . ((strIsEmpty($sWhen) && strIsEmpty($sObjectHyper)) || $sWhen==='-3d' ? ' selected' : '' ) . '>Last 3 days</option>';
	echo '	<option value="-7d"' . ($sWhen==='-7d' ? ' selected' : '' ) . '>Last 7 days</option>';
	echo '	<option value="-14d"' . ($sWhen==='-14d' ? ' selected' : '' ) . '>Last 14 days</option>';
	echo '	<option value="-30d"' . ($sWhen==='-30d' ? ' selected' : '' ) . '>Last 30 days</option>';
	echo '	<option value="-365d"' . ($sWhen==='-365d' ? ' selected' : '' ) . '>Last 12 months</option>';
	echo '	<option value="Any"' . ($sWhen==='Any' ? ' selected' : '' ) . '>Any</option>';
	echo '	</select>';
	echo '	</div>';
	echo '	</div>';
	echo '	<div id="search-message" class=""></div>';
	echo '	</form>';
	echo '</div>';
	echo '<div class="dialog-footer">';
	echo '<div class="dialog-buttons-container">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-ok" type="submit" onclick="OnFormRunCustomSearch(event,\'' . _glt('Search Results') . '\')" value="' . _glt('Run Search') . '">';
	echo '<input class="webea-main-styled-button dialog-button dialog-button-close" type="submit" onclick="OnClickCloseDialogButton()" value="' . _glt('Close') . '">';
	echo '</div>';
	echo '</div>';
	echo '</div>';
?>
<script>
var unsaved = false;
</script>