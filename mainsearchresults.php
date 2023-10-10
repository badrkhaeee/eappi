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
	$sSearch 	= urlencode(SafeGetInternalArrayParameter($_GET, 'search'));
	$sCategory 	= urlencode(SafeGetInternalArrayParameter($_GET, 'category'));
	$sSearchType 	= urlencode(SafeGetInternalArrayParameter($_GET, 'type'));
	$sTerm 	= rawurlencode(SafeGetInternalArrayParameter($_GET, 'term'));
	$sWhen 	= urlencode(SafeGetInternalArrayParameter($_GET, 'recent'));
	if ( !strIsEmpty($sObjectHyper) )
	{
	$a = explode('&', $sObjectHyper);
	if ($a)
	{
	foreach ($a as $sOption)
	{
	list($sOptionName, $sOptionValue) = explode('=', $sOption);
	$sOptionName = trim($sOptionName);
	$sOptionValue = trim($sOptionValue);
	if ($sOptionName==='search')
	{
	$sSearch = $sOptionValue;
	}
	elseif ($sOptionName==='term')
	{
	$sTerm = $sOptionValue;
	}
	elseif ($sOptionName==='category')
	{
	$sCategory = $sOptionValue;
	}
	elseif ($sOptionName==='type')
	{
	$sSearchType = $sOptionValue;
	}
	elseif ($sOptionName==='recent')
	{
	$sWhen = $sOptionValue;
	}
	}
	}
	}
	if ( $sCategory !== 'discussion' && $sCategory !== 'review' && strIsEmpty($sWhen) && strIsEmpty($sTerm) )
	{
	setResponseCode(456, _glt('No term or timeframe'));
	exit();
	}
	$sSearch = 'custom';
	$aRows = array();
	include('./data_api/get_json_search.php');
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	$iRows = count($aRows);
	if ($iRows <= 0)
	{
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	echo '<div id="search-results-div" class="no-results"><div class="search-results-div-inner">' . _glt('No Results Found') . '</div></div>';
	}
	}
	else
	{
	echo '<div id="search-results-count">' . $iRows . '</div>';
	echo '<div id="search-results-div" class="search-related-bkcolor">';
	echo '<div class="search-results-div-inner">';
	echo '<table id="search-results"> <tbody>' . PHP_EOL;
	echo '<tr class="sortable-header">';
	echo '  <th class="default-cursor"></th>';
	echo '  <th class="sortable-col">' . _glt('Name') . '<div class="sort-icon"></div></th>';
	echo '  <th class="sortable-col">' . _glt('Type') . '<div class="sort-icon"></div></th>';
	echo '  <th class="sortable-col">' . _glt('Author') . '<div class="sort-icon"></div></th>';
	echo '  <th class="sortable-col">' . _glt('Modified') . '<div class="sort-icon"></div></th>';
	echo '</tr>' . PHP_EOL;
	for ($iRowID = 0; $iRowID < $iRows; $iRowID++)
	{
	$Name 	= $aRows[$iRowID]['text'];
	$sAuthor 	= $aRows[$iRowID]['author'];
	$sAlias 	= $aRows[$iRowID]['alias'];
	if ( strIsEmpty($Name) && !strIsEmpty($sAlias))
	$Name = $sAlias;
	else if ($aRows[$iRowID]['ntype'] === '19' && !strIsEmpty($sAlias))
	$Name = $sAlias;
	$NameInLink = LimitDisplayString($Name, 255);
	$Href = 'javascript:LoadObject(\'' . _j($aRows[$iRowID]['guid']) . '\',\'\',\'\',\'\',\'' . _j($NameInLink) . '\',\'' . _j($aRows[$iRowID]['imageurl']) . '\')';
	$sImageHTML = '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($aRows[$iRowID]['imageurl']) . '" alt=""/>';
	echo '<tr onclick="' . $Href . '">';
	echo '  <td class="search-results-image">' . $sImageHTML . '</td>';
	echo '  <td class="search-results-name">' . _h($Name) . '</td>';
	echo '  <td class="noWrapCell">' . _h($aRows[$iRowID]['type']) . '</td>';
	echo '  <td class="noWrapCell">' . _h($sAuthor) . '</td>';
	echo '  <td class="noWrapCell">' . _h($aRows[$iRowID]['modified']) . '</td>';
	echo '</tr>' . PHP_EOL;
	}
	echo '</tbody></table>';
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
?>
<script>
var header = $('.sortable-header th');
var table = $('table');
var inverse = false;
SortTable(header, table, inverse);
</script>