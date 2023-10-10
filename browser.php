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
	if  ( !isset($webea_page_parent_mainview) )
	{
	exit();
	}
	SafeStartSession();
	$sHome 	= isset($_SESSION['model_name']) ? $_SESSION['model_name'] : 'Home';
	BuildOSLCConnectionString();
	$webea_page_parent_browser = true;
	$bAddElements	= CanAddObjects();
	$bAddPackages	= IsSessionSettingTrue('add_objecttype_package') && IsSessionSettingTrue('login_perm_element');
	$sLinkType = SafeGetInternalArrayParameter($_POST, 'linktype');
	if ($sLinkType === 'favorites')
	{
	include('./data_api/get_favorites.php');
	}
	else
	{
	include('./data_api/get_browserobjects.php');
	}
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	unset($webea_page_parent_browser);
	$sBrowserParentGUID = '';
	$sBrowserParentName = '';
	$sBrowserParentImageURL = '';
	$sSelectedObjectGUID = '';
	if (isset($sLinkTypeGUID))
	{
	$sSelectedObjectGUID = $sLinkTypeGUID;
	}
	else
	{
	$sSelectedObjectGUID = $sObjectGUID;
	}
	echo '<div id="main-browser-view">';
	echo '<div class="browser-view-section">';
	echo '<div style="float:right;">';
	if (IsSessionSettingTrue('show_propertiesview'))
	{
	echo '<div id="statusbar-properties-button" style="height: 24px; width: 24px; float:right; margin: 0px; cursor:pointer;" title="'._glt('Hide Properties View').'" onclick="ShowPropertiesView()"><div id="mainsprite-navbarpropsicon" class="mainsprite-navbarpropscollapse" style="opacity:0.8;"></div></div>';
	}
	else
	{
	echo '<div id="statusbar-properties-button" style="height: 24px; width: 24px; float:right; margin: 0px; cursor:pointer;" title="'._glt('Show Properties View').'" onclick="ShowPropertiesView()"><div id="mainsprite-navbarpropsicon" class="mainsprite-navbarpropsexpand" style="opacity:0.8;"></div></div>';
	}
	echo '</div>';
	if ($sLinkType === 'favorites')
	{
	WriteFavorites($aData);
	}
	else
	{
	$iCnt = count($aBrowserObjs);
	for ($i=0; $i<$iCnt; $i++)
	{
	$sObjName = SafeGetArrayItem2Dim($aBrowserObjs, $i, 'text');
	$sNameWithClassifier 	= GetDisplayNameWithClassifier(SafeGetArrayItem2Dim($aBrowserObjs, $i, 'text'), SafeGetArrayItem2Dim($aBrowserObjs, $i, 'classifiername'));
	$sObjGUID = SafeGetArrayItem2Dim($aBrowserObjs, $i, 'guid');
	$sObjType = SafeGetArrayItem2Dim($aBrowserObjs, $i, 'type');
	$sObjNType = SafeGetArrayItem2Dim($aBrowserObjs, $i, 'ntype');
	$sObjResType = SafeGetArrayItem2Dim($aBrowserObjs, $i, 'restype');
	$sObjStructure = SafeGetArrayItem2Dim($aBrowserObjs, $i, 'structid');
	$sImageURL = SafeGetArrayItem2Dim($aBrowserObjs, $i, 'imageurl');
	$bIsLocked =	SafeGetArrayItem2Dim($aBrowserObjs, $i, 'locked');
	$sLockType =	SafeGetArrayItem2Dim($aBrowserObjs, $i, 'lockedtype');
	$bHasChild 	= SafeGetArrayItem2Dim($aBrowserObjs, $i, 'haschild');
	$sImageURL = AdjustImagePath($sImageURL, '16');
	$sName = GetPlainDisplayName($sObjName);
	if ($sObjGUID === 'home_link')
	{
	$sHref = 'javascript:LoadObject(\'\',\'true\',\'\',\'\',\'' . _j($sHome) . '\',\'home.png\')';
	}
	elseif (( $sObjResType !== 'Diagram') && ($sObjType !== 'ModelRoot'))
	{
	$sHref = 'javascript:LoadObject(\'' . _j($sObjGUID) . '\',\'' . _j($bHasChild) . '\',\'props\',\'browser\',\'' . _j($sObjName) . '\',\'' . _j($sImageURL) . '\')';
	}
	else
	{
	$sHref = 'javascript:LoadObject(\'' . _j($sObjGUID) . '\',\'' . _j($bHasChild) . '\',\'\',\'browser\',\'' . _j($sObjName) . '\',\'' . _j($sImageURL) . '\')';
	}
	if ($i===0)
	{
	echo '<table class="browser-current-table"><tbody>';
	$sSelected = ($sSelectedObjectGUID === $sObjGUID)? 'selected' : '';
	if ($sObjGUID === 'root')
	{
	echo '<tr class="browser-table-tr" style="cursor: default;">';
	}
	else
	{
	echo '<tr class="browser-table-tr" onclick="' . $sHref . '">';
	}
	echo '  <td class="browser-item-image-td" style="width: 18px;">'.BuildImageHTML($sObjResType, $sImageURL, $bHasChild, $bIsLocked, $sLockType, '16').'</td>';
	echo '  <td class="browser-item-name-td ' . $sSelected . '" guid="'._h($sObjGUID).'" title="'. _h($sNameWithClassifier) . '">' . _h($sNameWithClassifier) . '</td>';
	echo '</tr>' . PHP_EOL;
	echo '</tbody></table>';
	$sBrowserParentGUID = $sObjGUID;
	$sBrowserParentName = $sName;
	$sBrowserParentImageURL = $sImageURL;
	}
	else if ($i===1)
	{
	echo '<div class="browser-contents-elements-div">';
	echo '<table id="context-browser-table"><tbody>' . PHP_EOL;
	if (($aBrowserObjs[0]['guid'] !== 'root') &&
	(!IsSessionSettingTrue('favorites_as_home')))
	{
	echo '<tr class="browser-table-tr" onclick="'. $sHref . '">';
	echo '  <td class="browser-item-image-td"><img src="images/spriteplaceholder.png" class="mainsprite-browserup" alt="" title=""></td>';
	echo '  <td class="browser-item-name-td">...</td>';
	echo '</tr>' . PHP_EOL;
	}
	}
	else
	{
	$sSelected = ($sSelectedObjectGUID === $sObjGUID)? 'selected' : '';
	echo '<tr class="browser-table-tr" guid="'._h($sObjGUID).'" name="'._h($sObjName).'" imageurl="'._j($sImageURL).'" haschild="'.$bHasChild.'" onclick="' . $sHref . '">';
	echo '  <td class="browser-item-image-td">' . BuildImageHTML($sObjResType, $sImageURL, $bHasChild, $bIsLocked, $sLockType, '16'). '</td>';
	echo '  <td class="browser-item-name-td ' . $sSelected . '" guid="'._h($sObjGUID).'" title="'. _h($sNameWithClassifier) . '">' . _h($sNameWithClassifier) . '</td>';
	echo '</tr>' . PHP_EOL;
	}
	}
	if ($bAddElements)
	{
	$sAddObjectName 	= _glt('Add new element');
	$sAddObjectImageName = 'element16-add';
	$bCanAdd 	= true;
	$sHref = 'AddObject(\'' . _j($sBrowserParentGUID) . '\', \''._j($sBrowserParentName).'\')';
	if ( (strIsEmpty($sObjectGUID)) || ($sObjectGUID === 'addmodelroot'))
	{
	$sAddObjectName = _glt('Add Root Node');
	$sAddObjectImageName = 'element16-addmodelroot';
	$bCanAdd 	= $bAddPackages;
	$sBrowserParentGUID = '';
	$sBrowserParentName = '';
	$sBrowserParentImageURL = '';
	$sHref = 'AddModelRoot(\'' . _j($sBrowserParentGUID) . '\', \''._j($sBrowserParentName).'\')';
	}
	elseif ( (substr($sObjectGUID,0,3) === 'mr_') || $sObjectGUID === 'addviewpackage' )
	{
	$sAddObjectName = _glt('Add View');
	$sAddObjectImageName = 'element16-addviewpackage';
	$bCanAdd 	= $bAddPackages;
	$sHref = 'AddViewPackage(\'' . _j($sBrowserParentGUID) . '\', \''._j($sBrowserParentName).'\')';
	}
	if ( $bCanAdd )
	{
	$sNameInLink = LimitDisplayString($sBrowserParentName, 255);
	echo '<tr class="browser-table-tr browser-add-object" onclick="' . $sHref . '">';
	echo '  <td class="browser-item-image-td">';
	echo '    <div class="package-list-img-image">';
	echo '      <img src="images/spriteplaceholder.png" class="' . _h($sAddObjectImageName) . '" alt="" title=""/>';
	echo '    </div>';
	echo '</td>';
	echo '  <td class="browser-item-name-td" title="'. $sAddObjectName . '">&lt;' . _glt('New') . '&gt;</td>';
	echo '</tr>' . PHP_EOL;
	}
	}
	echo '</tbody></table>';
	echo '</div>';
	}
	echo '</div>';
	echo '</div>';
?>