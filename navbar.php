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
	$sCurrGUID 	= isset($_POST['guid']) ? $_POST['guid'] : '';
	$sCurrHasChild 	= isset($_POST['haschild']) ? $_POST['haschild'] : '';
	$sCurrLinkType 	= isset($_POST['linktype']) ? $_POST['linktype'] : '';
	$sCurrHyper 	= isset($_POST['hyper']) ? $_POST['hyper'] : '';
	$sCurrName	 	= isset($_POST['name']) ? $_POST['name'] : '';
	$sCurrImageURL 	= isset($_POST['imageurl']) ? $_POST['imageurl'] : '';
	$sCurrResType	= GetResTypeFromGUID($sCurrGUID);
	$sCurrName 	= GetPlainDisplayName($sCurrName);
	$iDiagramZoom = SafeGetInternalArrayParameter($_SESSION, 'diagram_zoom',100);
	$bShowHomeWithoutLink = true;
	$webea_page_parent_navbar = true;
	$sHome = isset($_SESSION['model_name']) ? $_SESSION['model_name'] : 'Root';
	$sObjectGUID = $sCurrGUID;
	if ( !strIsEmpty($sObjectGUID) && $sObjectGUID !== 'root')
	{
	$webea_page_parent_objectpath = true;
	include('./data_api/get_objectpath.php');
	unset($webea_page_parent_objectpath);
	}
	if(!IsHTTPSuccess(http_response_code()))
	{
	exit();
	}
	echo '<div class="navbar">';
	echo '<div id="navbar-busy-loader">';
	echo '<img src="images/navbarwait.gif" alt="" class="navbar-spinner" height="26" width="26">';
	echo '</div>';
	echo '<div class="navbar-panel-left" id="navbar-panel-left">';
	echo '<div id="navbar-home-button" title="' . _glt('Navigate to initial page') . '" onclick="LoadHome(false)"><div><div class="mainsprite-home24">&#160;</div></div></div>';
	$sNavbarNameAdjustment = '';
	if (IsSessionSettingTrue('show_path_button'))
	{
	echo '<div class="navbar-path-dropdown">';
	echo '<div id="navbar-path-button" title="' . _glt('Complete path to root node') . '" onclick="show_path()"><div><div class="mainsprite-path">&#160;</div></div></div>';
	echo '<div id="path-menu" class="contextmenu">';
	echo '<div class="contextmenu-arrow-bottom"></div><div class="contextmenu-arrow-top"></div>';
	echo '<div class="contextmenu-content">';
	echo WriteContextMenuHeader(_glt('Path'));
	if(!isset($webea_page_parent_index))
	{
	include('objectpath.php');
	}
	else
	{
	echo '<div class="contextmenu-items">';
	echo '<div class="contextmenu-item" onclick="LoadObject(\'\',\'\')"><img alt="Home" src="images/spriteplaceholder.png" class="mainsprite-root">' . _h($sHome) . '</div>';
	echo '</div>';
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
	else
	{
	$sNavbarNameAdjustment = ' class="path-hidden" ';
	}
	$sRefreshLink = 'RefreshCurrent()';
	echo '<div id="navbar-back-button" title="' . _glt('Back') . '" onclick="GoBack()"><div><div class="back-icon icon24">&#160;</div></div></div>';
	echo '<div class="navbar-spacer"><img alt="|" src="images/spriteplaceholder.png" class="mainsprite-bar36"></div>';
	echo '<div class="navbar-spacer-2"><img alt="|" src="images/spriteplaceholder.png" class="mainsprite-bar36"></div>';
	if(!isset($webea_page_parent_index))
	{
	$bCurrentShown = false;
	if ( IsGUIDAddEditAction($sCurrGUID)  )
	{
	$sImageClass = '';
	$sText = '';
	$bHasParentAndChild = true;
	echo '<div id="navbar-current-name"'.$sNavbarNameAdjustment.'><div id="navbar-current-name-text">';
	if ( $sCurrGUID==='addmodelroot' )
	{
	$sImageClass = 'element16-addmodelroot';
	$sText = _glt('Add Root Node');
	$bHasParentAndChild = false;
	}
	elseif ( $sCurrGUID==='addviewpackage' )
	{
	$sImageClass = 'element16-addviewpackage';
	$sText = _glt('Add view to');
	}
	elseif ( $sCurrGUID==='addelement' )
	{
	$sImageClass = 'element16-add';
	$sText = _glt('Add element to');
	}
	elseif ( $sCurrGUID==='addelementtest' )
	{
	$sImageClass = 'propsprite-testadd';
	$sText = _glt('Add Test to');
	}
	elseif ( $sCurrGUID==='addelementresalloc' )
	{
	$sImageClass = 'propsprite-resallocadd';
	$sText = _glt('Add resource allocation to');
	}
	elseif ( $sCurrGUID==='addelementchgmgmt' )
	{
	$sObjectGUID = '';
	$sChangeMgtType = '';
	list($sObjectGUID, $sChangeMgtType) = explode('|', $sCurrLinkType);
	$sObjectGUID = trim($sObjectGUID);
	$sChangeMgtType = trim($sChangeMgtType);
	GetChgMgtAddText($sChangeMgtType, $sText, $sImageClass);
	}
	elseif ( $sCurrGUID==='editelementnote' )
	{
	$sImageClass = 'propsprite-noteedit';
	$sText = _glt('Edit note for');
	}
	elseif ( $sCurrGUID==='editelementtest' )
	{
	$sImageClass = 'propsprite-testedit';
	$sText = _glt('Edit Test for');
	}
	elseif ( $sCurrGUID==='editelementresalloc' )
	{
	$sImageClass = 'propsprite-resallocedit';
	$sText = _glt('Edit resource allocation for');
	}
	echo '<img alt="" src="images/spriteplaceholder.png" class="' . _h($sImageClass) . '">&nbsp;' . _h($sText) . '&nbsp;';
	if ( $bHasParentAndChild )
	{
	echo '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName(AdjustImagePath($sCurrImageURL, '16')) . '">&nbsp;' . _h($sCurrName) . '&nbsp;';
	}
	echo '</div></div>';
	$bCurrentShown = true;
	}
	else if ( !strIsEmpty($sCurrResType) )
	{
	echo '<div id="navbar-current-name"'.$sNavbarNameAdjustment.'><div id="navbar-current-name-text">';
	$sImagePath = AdjustImagePath($sCurrImageURL, '16');
	if ( $sCurrLinkType === 'document' )
	{
	echo '<img alt="" src="images/spriteplaceholder.png" class="element16-document">&nbsp;' . _glt('Linked Document for') . '&nbsp;';
	}
	elseif ( $sCurrLinkType === 'encryptdoc' )
	{
	echo _glt('Encrypted Document for') . '&nbsp;';
	}
	$sPrefix = substr($sCurrGUID,0,4);
	if ($sPrefix === 'lt_{')
	{
	$s = trim($sCurrName);
	$a = explode(' ', $s);
	if (count($a)>1)
	{
	$a[0] = _glt($a[0]);
	$a[1] = _glt($a[1]);
	}
	$sCurrName = implode(' ', $a);
	}
	elseif ($sPrefix !== 'mr_{' && $sPrefix !== 'pk_{' && $sPrefix !== 'dg_{' && $sPrefix !== 'di_{' && $sPrefix !== 'el_{' && $sCurrGUID !== 'matrix')
	{
	$sCurrName = _glt($sCurrName);
	}
	elseif ( $sCurrGUID === 'matrix' )
	{
	$sCurrName = _glt('Matrix') .' - '. $sCurrHyper;
	$sImagePath = 'mainsprite-matrixcolor';
	}
	echo '<img alt="" title="' . _h($sCurrResType) . '" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImagePath) . '">&nbsp;' . _h($sCurrName) . '&nbsp;';
	echo '</div></div>';
	$bCurrentShown = true;
	}
	if ($bCurrentShown === false)
	{
	echo '<div id="navbar-current-name"'.$sNavbarNameAdjustment.'><div id="navbar-current-name-text"><img alt="" src="images/spriteplaceholder.png" class="mainsprite-root">&nbsp;' . _h($sHome) . '&nbsp;</div></div>';
	}
	}
	echo '</div>';
	if (IsSessionSettingTrue('show_search'))
	{
	echo '<div class="navbar-panel-right" id="navbar-panel-right">';
	}
	else
	{
	echo '<div class="navbar-panel-right no-search" id="navbar-panel-right">';
	}
	$sPlainGUID = '';
	$sFullURL = '';
	$sFirst3Chars 	= substr($sCurrGUID,0 ,3);
	if ( $sFirst3Chars === 'mr_' ||
	 $sFirst3Chars === 'pk_' ||
	 $sFirst3Chars === 'dg_' ||
	 $sFirst3Chars === 'el_' ||
	 $sCurrGUID    === 'matrix' )
	{
	if ( $sCurrGUID === 'matrix' )
	{
	$sPlainGUID = 'matrix_' . $sCurrHyper;
	}
	else
	{
	$sPlainGUID = substr($sCurrGUID, 4);
	$sPlainGUID = trim($sPlainGUID, '{}');
	}
	$sFullURL  = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
	$sFullURL .= '://' . $_SERVER['HTTP_HOST'];
	$sWebsiteRoot = '';
	$sDocPath = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	$iLastPos = strripos($sDocPath, '/');
	if ( $iLastPos !== FALSE )
	{
	if ( $iLastPos > 0 )
	{
	$sWebsiteRoot = substr($sDocPath, 0, $iLastPos);
	}
	}
	$sFullURL .= $sWebsiteRoot;
	$sFullURL .= '?m=' . SafeGetInternalArrayParameter($_SESSION, 'model_no', '');
	$sFullURL .= '&o=' . $sPlainGUID;
	}
	echo '<input type="hidden" name="current_guid" value="' . _h($sPlainGUID) .'"> ';
	echo '<input type="hidden" name="current_url" value="' . _h($sFullURL) .'"> ';
	if (isset($sResType))
	{
	if ($sResType === 'Diagram')
	{
	if ($sCurrLinkType === 'edit')
	{
	}
	else
	{
	}
	}
	}
	if (isset($sResType))
	{
	if ($sResType === 'Diagram')
	{
	if ($sCurrLinkType === 'edit')
	{
	}
	}
	}
	$sLoadProps = '';
	$sLoadDiagram = '';
	$sLoadDiagram = 'LoadObject(\'' . _j($sCurrGUID) . '\', \'' . _j($sCurrHasChild) . '\', \'\', \'' . _j($sCurrHyper) . '\', \'' . _j($sCurrName) . '\', \'' . _j($sCurrImageURL) . '\')';
	if($sCurrResType === "Element")
	{
	$sLoadDiagram = 'LoadObject(\'' . _j($sCurrGUID) . '\', \'' . _j($sCurrHasChild) . '\',\'child\', \'' . _j($sCurrHyper) . '\', \'' . _j($sCurrName) . '\', \'' . _j($sCurrImageURL) . '\')';
	}
	if ( $sCurrResType === "Diagram" || $sCurrResType === "Package" || $sCurrResType === "Element")
	{
	$sLoadProps = 'LoadObject(\'' . _j($sCurrGUID) . '\', \'' . _j($sCurrHasChild) . '\', \'props\', \'' . _j($sCurrHyper) . '\', \'' . _j($sCurrName) . '\', \'' . _j($sCurrImageURL) . '\')';
	$sLoadInfo = 'ShowMenu(this)';
	echo '<div id="navbar-ellipsis-button" title="' . _glt('View the object properties') . '" onclick="' . $sLoadInfo . '"><div><div class="ellipsis-icon icon24">&#160;</div></div></div>';
	}
	$sZoomOutVal = GetZoomPtc($iDiagramZoom,true);
	$sZoomInVal = GetZoomPtc($iDiagramZoom,false);
	$bPropsCheckbox = '';
	$bDiagramCheckbox = '';
	if($sCurrLinkType === 'props' ||
	($sCurrResType === 'Element' && $sCurrLinkType !== 'child'))
	{
	$bPropsCheckbox = 'icon16 tick-icon';
	}
	else
	{
	$bDiagramCheckbox = 'icon16 tick-icon';
	}
	$sHeader = _glt('Diagram Options');
	$sOption1 = _glt('Show Image');
	if ($sCurrResType === "Package")
	{
	$sHeader = _glt('Package Options');
	$sOption1 = _glt('Show Contents');
	}
	else if ($sCurrResType === "Element")
	{
	$sHeader = _glt('Element Options');
	$sOption1 = _glt('Show Children');
	}
	$sShowChildDisabled = '';
	if ( $sCurrResType !== "Diagram" && $sCurrHasChild === "false")
	{
	$sShowChildDisabled = 'disabled';
	}
	echo '<div id="navbar-context-menu" class="contextmenu hide-menu">';
	echo WriteContextMenuHeader($sHeader);
	echo '<div class="contextmenu-items">';
	echo '<div class="contextmenu-item small-margin" onclick="'.$sLoadDiagram.'" '.$sShowChildDisabled.'>' . '<a class="blank-icon nav-context-checkbox '.$bDiagramCheckbox.' "></a>&nbsp;&nbsp;' . $sOption1 . '</div>';
	echo '<div class="contextmenu-item small-margin" onclick="'.$sLoadProps.'">' . '<a class="blank-icon nav-context-checkbox '.$bPropsCheckbox.'"></a>&nbsp;&nbsp;' . _glt('Show Properties') . '</div>';
	if($sCurrResType === "Diagram")
	{
	echo '<hr>';
	echo '<div class="contextmenu-item diagram-zoom small-margin">' . '<a class="blank-icon nav-context-checkbox"></a>&nbsp&nbsp' . _glt('Zoom') . '<a>: '.$iDiagramZoom.'%</a><div class="diagram-zoom-out-btn" zoom-val="'.$sZoomOutVal.'" onclick="DiagramZoom(this)">-</div><div class="diagram-zoom-in-btn" zoom-val="'.$sZoomInVal.'" onclick="DiagramZoom(this)">+</div></div>';
	}
	echo '</div>';
	echo '</div>';
	echo '<div class="navbar-spacer-2"><img alt="|" src="images/spriteplaceholder.png" class="mainsprite-bar36"></div>';
	if (IsSessionSettingTrue('show_search'))
	{
	echo '<div class="navbar-search-dropdown">';
	echo '  <input id="navbar-search-button" value="&nbsp;" type="button" title="Search" onclick="show_navbar_search()">';
	echo '	<div id="navbar-search-menu" class="contextmenu">';
	echo '	  <div class="contextmenu-arrow-bottom"></div><div class="contextmenu-arrow-top"></div>';
	echo '	  <div class="contextmenu-content">';
	WriteSearchMenu();
	echo '	  </div>';
	echo '	</div>';
	echo '</div>';
	}
	$sBrowserStyle = '';
	if (IsSessionSettingTrue('show_browser'))
	{
	$sBrowserStyle = 'class="mainsprite-tick"';
	}
	$sMiniPropsStyle = '';
	if (IsSessionSettingTrue('show_propertiesview'))
	{
	$sMiniPropsStyle = 'class="mainsprite-tick"';
	}
	$sMainLayoutNo = SafeGetInternalArrayParameter($_SESSION, 'mainlayout', '1');
	$sShowRadioIcons = 'class="hamburger-radio-icon"';
	$sShowRadioList = '';
	$sShowRadioNotes = '';
	if ($sMainLayoutNo === '2')
	{
	$sShowRadioIcons = '';
	$sShowRadioList = 'class="hamburger-radio-icon"';
	}
	elseif ($sMainLayoutNo === '3')
	{
	$sShowRadioIcons = '';
	$sShowRadioNotes = 'class="hamburger-radio-icon"';
	}
	echo '<div class="navbar-hamburger-dropdown">';
	echo '  <input id="navbar-hamburger-button" value="&nbsp;" type="button" title="Complete path back to root node" onclick="show_navbar_hamburger()">';
	echo '  <div id="navbar-hamburger-menu">';
	echo '	  <div class = "contextmenu-arrow-bottom"></div><div class = "contextmenu-arrow-top"></div>';
	echo '	  <div class="contextmenu-content">';
	WriteHamburger($sBrowserStyle, $sMiniPropsStyle, $sShowRadioIcons, $sShowRadioList, $sShowRadioNotes);
	echo '    </div>';
	echo '  </div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo PHP_EOL;
	echo BuildSystemOutputDataDIV();
	unset($webea_page_parent_navbar);
	function GetZoomPtc($sCurrentZoom,$bIsZoomOut = true)
	{
	$sNextZoomLevel = 'NA';
	$aSupportedZoomLevels = ['50','75','100','125','150'];
	$iZoomLvCount = count($aSupportedZoomLevels);
	for ($i=0; $i<$iZoomLvCount; $i++)
	{
	if ($sCurrentZoom === $aSupportedZoomLevels[$i])
	{
	if($bIsZoomOut)
	{
	if($i===0)
	$sNextZoomLevel = 'NA';
	else
	$sNextZoomLevel = $aSupportedZoomLevels[$i-1];
	}
	else
	{
	if($i===$iZoomLvCount-1)
	$sNextZoomLevel = 'NA';
	else
	$sNextZoomLevel = $aSupportedZoomLevels[$i+1];
	}
	}
	}
	return $sNextZoomLevel;
	}
?>
<script>
function show_path()
{
	$('#path-menu').toggle();
}
function show_navbar_search()
{
	$('#navbar-search-menu').toggle();
}
function show_navbar_hamburger()
{
	$('#navbar-hamburger-menu').toggle();
}
$(document).mouseup(function (e)
{
	HideMenu(e, "#navbar-path-button","#path-menu");
});
function expand_navbar()
{
	var pr = document.getElementById("navbar-panel-right");
	pr.className += " navbar-panel-right-expanded";
	var pl = document.getElementById("navbar-panel-left");
	pl.className += " navbar-panel-left-collapsed";
}
function collapse_navbar()
{
	var pr = document.getElementById("navbar-panel-right");
	pr.className = "navbar-panel-right";
	var pl = document.getElementById("navbar-panel-left");
	pl.className = "navbar-panel-left";
}
</script>