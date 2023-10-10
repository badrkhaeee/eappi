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
	$sErrorMsg = '';
	$webea_page_parent_browser = true;
	include('./data_api/get_browserobjects.php');
	$aObjs = $aBrowserObjs;
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	echo '<div id="main-package-list" class="package-related-bkcolor main-view ' . GetShowBrowserMinPropsStyleClasses() .'">';
	WritePackageViewButtons($sMainLayoutNo);
	$iCnt = count($aObjs);
	if ( $iCnt > 0 )
	{
	echo '<div class="main-package-inner">';
	echo '<table id="package-list-table"> <thead>' . PHP_EOL;
	echo '<tr class="package-list-tr sortable-header">';
	echo '  <th class="package-list-th" disabled="disabled" style="cursor:default;"></div></th>';
	echo '  <th class="package-list-th">' . _glt('Name') . '<div class="sort-icon"></div></th>';
	echo '  <th class="package-list-th">' . _glt('Type') . '<div class="sort-icon"></div></th>';
	echo '  <th class="package-list-th">' . _glt('Author') . '<div class="sort-icon"></div></th>';
	echo '  <th class="package-list-th">' . _glt('Modified') . '<div class="sort-icon"></div></th>';
	echo '</tr>' . PHP_EOL;
	echo '</thead>';
	echo '<tbody>';
	for ($i = 1; $i < $iCnt; $i++)
	{
	$sName 	= GetPlainDisplayName(SafeGetArrayItem2Dim($aObjs, $i, 'text'));
	$sNameWithClassifier 	= GetDisplayNameWithClassifier(SafeGetArrayItem2Dim($aObjs, $i, 'text'), SafeGetArrayItem2Dim($aObjs, $i, 'classifiername'));
	$sGUID	= SafeGetArrayItem2Dim($aObjs, $i, 'guid');
	$sResType	= SafeGetArrayItem2Dim($aObjs, $i, 'restype');
	$bHasChild	= SafeGetArrayItem2Dim($aObjs, $i, 'haschild');
	$sType	= SafeGetArrayItem2Dim($aObjs, $i, 'type');
	$sNotes	= SafeGetArrayItem2Dim($aObjs, $i, 'notes');
	$sNType	= SafeGetArrayItem2Dim($aObjs, $i, 'ntype');
	$sLinkType	= SafeGetArrayItem2Dim($aObjs, $i, 'linktype');
	$sHyper	= SafeGetArrayItem2Dim($aObjs, $i, 'hyper');
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
	$sHref = 'javascript:LoadObject(\'' . _j($sGUID) . '\',\'\',\'\',\'\',\'' . _j($sNameInLink) . '\',\'' . _j($sImageURL) . '\')';
	$sImageHTML  = '';
	$sImageHTML .= '<div class="package-list-img-image">';
	$sImageHTML .= BuildImageHTML($sObjResType, $sImageURL, $bHasChild, $bObjLocked, $sObjLockedType, '16');
	$sImageHTML .= '</div>';
	if($i===1)
	{
	if($sGUID === 'home_link')
	{
	$sHref = 'LoadObject(\'\',\'\')';
	}
	echo '<tr class="package-list-tr" onclick="' . $sHref . '" title="Go to Parent">';
	echo '  <td class="package-list-td no-sort">  <img src="images/spriteplaceholder.png" class="mainsprite-browserup" alt="" title=""> </td>';
	echo '  <td class="package-list-td no-sort package-list-td-name">...</td>';
	echo '  <td class="package-list-td no-sort noWrapCell">' .  '</td>';
	echo '  <td class="package-list-td no-sort noWrapCell">' . '</td>';
	echo '  <td class="package-list-td no-sort noWrapCell">' . '</td>';
	echo '</tr>' . PHP_EOL;
	}
	else
	{
	echo '<tr class="package-list-tr" onclick="' . $sHref . '">';
	echo '  <td class="package-list-td">' . $sImageHTML . '</td>';
	echo '  <td class="package-list-td package-list-td-name">' . _h($sNameWithClassifier) . '</td>';
	echo '  <td class="package-list-td noWrapCell">' . _h($sType) . '</td>';
	echo '  <td class="package-list-td noWrapCell">' . _h($sAuthor) . '</td>';
	echo '  <td class="package-list-td noWrapCell">' . _h($sModified) . '</td>';
	echo '</tr>' . PHP_EOL;
	}
	}
	if ($bAddElements)
	{
	echo WriteAddObjectListView($sObjectGUID, $sObjectName, $bAddPackages);
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
	if ( strIsEmpty($sObjectGUID) )
	{
	echo '<div class="main-package-inner">' . _glt('Problem reading model root') . '</div>';
	}
	else
	{
	if ($bAddElements)
	{
	$sAddRow = WriteAddObjectListView($sObjectGUID, $sObjectName, $bAddPackages);
	if (!strIsEmpty($sAddRow))
	{
	echo '<div class="main-package-inner">';
	echo '<table id="package-list-table"> <tbody>' . PHP_EOL;
	echo '<tr class="package-list-tr">';
	echo '  <th class="package-list-th"></th>';
	echo '  <th class="package-list-th">' . _glt('Name') . '</th>';
	echo '  <th class="package-list-th">' . _glt('Type') . '</th>';
	echo '  <th class="package-list-th">' . _glt('Author') . '</th>';
	echo '  <th class="package-list-th">' . _glt('Modified') . '</th>';
	echo '</tr>' . PHP_EOL;
	echo $sAddRow;
	echo '</tbody></table>';
	echo '</div>';
	}
	else
	{
	echo '<div class="main-package-inner">' . _glt('No child elements') . '</div>';
	}
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
	function WriteAddObjectListView($sObjectGUID, $sObjectName, $bAddPackages)
	{
	$sHTML = '';
	$sAddObjectName 	= _glt('Add new element');
	$sAddObjectImageName = 'element16-add';
	$bCanAdd 	= true;
	$sHref = 'AddObject(\'' . _j($sObjectGUID) . '\', \''._j($sObjectName).'\')';
	if ( strIsEmpty($sObjectGUID) )
	{
	$sAddObjectName = _glt('Add Root Node');
	$sAddObjectImageName = 'element16-addmodelroot';
	$bCanAdd 	= $bAddPackages;
	$sHref = 'AddModelRoot(\'' . _j($sObjectGUID) . '\', \''._j($sObjectName).'\')';
	}
	elseif ( substr($sObjectGUID,0,3) === 'mr_' )
	{
	$sAddObjectName = _glt('Add View');
	$sAddObjectImageName = 'element16-addviewpackage';
	$bCanAdd 	= $bAddPackages;
	$sHref = 'AddViewPackage(\'' . _j($sObjectGUID) . '\', \''._j($sObjectName).'\')';
	}
	if ( $bCanAdd )
	{
	$sNameInLink = LimitDisplayString($sObjectName, 255);
	$sHTML .= '<tr class="package-list-tr package-add-object" title="' . _h($sAddObjectName) . '" onclick="'.$sHref.'">';
	$sHTML .= '  <td class="package-list-td no-sort">';
	$sHTML .= '    <div class="package-list-img-image">';
	$sHTML .= '      <img src="images/spriteplaceholder.png" class="' . _h($sAddObjectImageName) . '" alt="" title=""/>';
	$sHTML .= '    </div>';
	$sHTML .= '  </td>';
	$sHTML .= '  <td class="package-list-td package-list-td-name w3-italic no-sort">&lt;' . _glt('New') . '&gt;</td>';
	$sHTML .= '  <td class="package-list-td noWrapCell no-sort"></td>';
	$sHTML .= '  <td class="package-list-td noWrapCell no-sort"></td>';
	$sHTML .= '  <td class="package-list-td noWrapCell no-sort"></td>';
	$sHTML .= '</tr>' . PHP_EOL;
	}
	return $sHTML;
	}
?>
<script>
var header = $('.sortable-header th');
var table = $('table');
var inverse = false;
SortTable(header, table, inverse);
</script>