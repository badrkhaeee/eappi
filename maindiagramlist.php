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
	$g_sViewingMode	= '2';
	$aObjs = array();
	include('./data_api/get_diagramobjects.php');
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	echo '<div id="main-diagram-list" class="diagram-related-bkcolor">';
	$iCnt = count($aObjs);
	if ( $iCnt > 0 )
	{
	echo '<div class="main-diagram-inner">';
	echo '<table id="diagram-list-table"> <tbody>' . PHP_EOL;
	echo '<tr class="diagram-list-tr">';
	echo '  <th class="diagram-list-th">&nbsp;&nbsp;' . _glt('Name') . '</th>';
	echo '  <th class="diagram-list-th">' . _glt('Type') . '</th>';
	echo '  <th class="diagram-list-th">' . _glt('Author') . '</th>';
	echo '  <th class="diagram-list-th">' . _glt('Modified') . '</th>';
	echo '</tr>' . PHP_EOL;
	for ($i = 0; $i < $iCnt; $i++)
	{
	$sName 	= GetPlainDisplayName(SafeGetArrayItem2Dim($aObjs, $i, 'name'));
	$sLevel	= SafeGetArrayItem2Dim($aObjs, $i, 'level');
	$sGUID	= SafeGetArrayItem2Dim($aObjs, $i, 'guid');
	$sResType	= SafeGetArrayItem2Dim($aObjs, $i, 'restype');
	$sHasChild	= SafeGetArrayItem2Dim($aObjs, $i, 'haschild');
	$sType	= SafeGetArrayItem2Dim($aObjs, $i, 'type');
	$sNotes	= SafeGetArrayItem2Dim($aObjs, $i, 'notes');
	$sNType	= SafeGetArrayItem2Dim($aObjs, $i, 'ntype');
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
	$sLoadObject = 'javascript:LoadObject(\'' . _j($sGUID) . '\',\'\',\'\',\'\',\'' . _j($sNameInLink) . '\',\'' . _j($sImageURL) . '\')';
	if ($sHasChild === 'true' && $bObjLocked)
	{
	$sImageLink = '<img class="diagram-list-img-image" style="background:url(' . _h($sImageURL) . ') no-repeat 0px 0px" src="images/element16/lockedhaschildoverlay.png" alt="" title="' . _glt('View child objects for locked') . ' ' . _h($sResType) . '" height="16" width="16"/>';
	}
	elseif ($sHasChild === 'true' && !$bObjLocked )
	{
	$sImageLink = '<img class="diagram-list-img-image" style="background:url(' . _h($sImageURL) . ') no-repeat 0px 0px" src="images/element16/haschildoverlay.png" alt="" title="' . _glt('View child objects') . '" height="16" width="16"/>';
	}
	elseif ($sHasChild === 'false' && $bObjLocked)
	{
	$sImageLink = '<img class="diagram-list-img-image" style="background:url(' . _h($sImageURL) . ') no-repeat 0px 0px" src="images/element16/lockedoverlay.png" alt="" title="' . _h($sResType) . ' ' . _glt('is locked') . '" height="16" width="16"/>';
	}
	else
	{
	$sImageLink = '<img class="diagram-list-img-image" src="' . _h($sImageURL) . '" alt="" title="" height="16" width="16"/>';
	}
	$sEvenLine = '';
	if ( ($i % 2) === 0 )
	$sEvenLine = ' diagram-list-tr-nth-child';
	$sIdentStyle = ' style="padding-left: ' . ((($sLevel-1) * 20)+4) . 'px;"';
	echo '<tr class="diagram-list-tr' . _h($sEvenLine) . '" onclick="' . $sLoadObject . '">';
	echo '  <td class="diagram-list-td diagram-list-td-name" ' . _h($sIdentStyle) .'>' . $sImageLink . _h($sName) . '</td>';
	echo '  <td class="diagram-list-td noWrapCell">' . _h($sType) . '</td>';
	echo '  <td class="diagram-list-td noWrapCell">' . _h($sAuthor) . '</td>';
	echo '  <td class="diagram-list-td noWrapCell">' . _h($sModified) . '</td>';
	echo '</tr>' . PHP_EOL;
	}
	echo '</tbody></table>';
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	else
	{
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	echo '<div class="main-diagram-inner">' . _glt('No objects on the diagram') . '</div>';
	}
	else
	{
	echo '<div class="main-diagram-inner">' . $sOSLCErrorMsg . '</div>';
	}
	}
	echo '</div>' . PHP_EOL;
?>