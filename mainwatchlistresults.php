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
	$sHyper = '';
	$sWatchlistClause = SafeGetInternalArrayParameter($_POST, 'clause');
	if ( strIsEmpty($sWatchlistClause) )
	{
	$sWatchlistClause = SafeGetInternalArrayParameter($_POST, 'hyper');
	}
	if (empty($sWatchlistClause))
	{
	setResponseCode(400);
	exit();
	}
	$aRows = array();
	$sOSLCErrorMsg 	= '';
	include('./data_api/get_watchlistresults.php');
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$iRows = count($aRows);
	if ($iRows <= 0)
	{
	echo '<div class="search-results-empty">' . _glt('No Results Found') . '</div>';
	exit();
	}
	else
	{
	echo '<div id="watchlist-results-count">' . $iRows . '</div>';
	echo '<div class="search-results-div-inner">';
	echo '<table id="search-results"> <tbody>' . PHP_EOL;
	echo '<tr>';
	echo '  <th></th>';
	echo '  <th>' . _glt('Type') . '</th>';
	echo '  <th>' . _glt('Name') . '</th>';
	echo '  <th>' . _glt('Author') . '</th>';
	echo '  <th>' . _glt('Modified') . '</th>';
	echo '</tr>';
	for ($iRowID = 0; $iRowID < $iRows; $iRowID++)
	{
	$sName 	= $aRows[$iRowID]['text'];
	$sAuthor 	= $aRows[$iRowID]['author'];
	$sAlias 	= $aRows[$iRowID]['alias'];
	if (strIsEmpty($sName) && !strIsEmpty($sAlias))
	$sName = $sAlias;
	else if ($aRows[$iRowID]['ntype'] === '19' && !strIsEmpty($sAlias))
	$sName = $sAlias;
	$sNameInLink = LimitDisplayString($sName, 255);
	$sHref = 'javascript:LoadObject(\'' . _j($aRows[$iRowID]['guid']) . '\',\'\',\'\',\'\',\'' . _j($sNameInLink) . '\',\'' . _j($aRows[$iRowID]['imageurl']) . '\')';
	$sImageHTML = '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($aRows[$iRowID]['imageurl']) . '" alt=""/>';
	echo '<tr onclick="' . $sHref . '">';
	echo '  <td class="search-results-image">' . $sImageHTML . '</td>';
	echo '  <td class="noWrapCell">' . _h($aRows[$iRowID]['type']) . '</td>';
	echo '  <td class="search-results-name">' . _h($sName) . '</td>';
	echo '  <td class="noWrapCell">' . _h($sAuthor) . '</td>';
	echo '  <td class="noWrapCell">' . _h($aRows[$iRowID]['modified']) . '</td>';
	echo '</tr>';
	}
	echo '</tbody></table>' . PHP_EOL;
	echo '</div>';
	}
	}
	else
	{
	echo '<div class="search-results-empty">' . $sOSLCErrorMsg . '</div>';
	}
?>