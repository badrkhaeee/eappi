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
	AllowedMethods('POST');
	}
	$sContextMenu = '';
	$sCMPart1 = '';
	$sCMPart2 = '';
	if (CanAddObjects())
	{
	$sCMPart1 .= '<div class="contextmenu-item" onclick="AddObject(\'' . _j($sObjectGUID) . '\', \''._j($sObjectName).'\')">';
	$sCMPart1 .= '<img alt="" src="images/spriteplaceholder.png" class="element16-add">' . _glt('Add Object') . '</div>';
	}
	if (IsSessionSettingTrue('add_object_features'))
	{
	if ( IsSessionSettingTrue('login_perm_test') )
	{
	if (IsSessionSettingTrue('add_objectfeature_tests'))
	{
	$sCMPart1 .= '<div class="contextmenu-item" onclick="AddTest(\'' . _j($sObjectGUID) .  '\',\'change\')">';
	$sCMPart1 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-testadd">' . _glt('Add test') . '</div>';
	}
	}
	if ( IsSessionSettingTrue('login_perm_resalloc') )
	{
	if (IsSessionSettingTrue('add_objectfeature_resources'))
	{
	$sCMPart1 .= '<div class="contextmenu-item" onclick="AddResAlloc(\'' . _j($sObjectGUID) . '\')">';
	$sCMPart1 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-resallocadd">' . _glt('Add resource') . '</div>';
	}
	}
	if ( IsSessionSettingTrue('login_perm_maintenance') )
	{
	if (IsSessionSettingTrue('add_objectfeature_features'))
	{
	$sCMPart2 .= '<div class="contextmenu-item" onclick="AddChgMgmt(\'' . _j($sObjectGUID) . '\',\'feature\')">';
	$sCMPart2 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-featureadd">' . _glt('Add feature') . '</div>';
	}
	if (IsSessionSettingTrue('add_objectfeature_changes'))
	{
	$sCMPart2 .= '<div class="contextmenu-item" onclick="AddChgMgmt(\'' . _j($sObjectGUID) . '\',\'change\')">';
	$sCMPart2 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-changeadd">' . _glt('Add change') . '</div>';
	}
	if (IsSessionSettingTrue('add_objectfeature_documents'))
	{
	$sCMPart2 .= '<div class="contextmenu-item" onclick="AddChgMgmt(\'' . _j($sObjectGUID) . '\',\'document\')">';
	$sCMPart2 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-documentadd">' . _glt('Add document') . '</div>';
	}
	if (IsSessionSettingTrue('add_objectfeature_defects'))
	{
	$sCMPart2 .= '<div class="contextmenu-item" onclick="AddChgMgmt(\'' . _j($sObjectGUID) . '\',\'defect\')">';
	$sCMPart2 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-defectadd">' . _glt('Add defect') . '</div>';
	}
	if (IsSessionSettingTrue('add_objectfeature_issues'))
	{
	$sCMPart2 .= '<div class="contextmenu-item" onclick="AddChgMgmt(\'' . _j($sObjectGUID) . '\',\'issue\')">';
	$sCMPart2 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-issueadd">' . _glt('Add issue') . '</div>';
	}
	if (IsSessionSettingTrue('add_objectfeature_tasks'))
	{
	$sCMPart2 .= '<div class="contextmenu-item" onclick="AddChgMgmt(\'' . _j($sObjectGUID) . '\',\'task\')">';
	$sCMPart2 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-taskadd">' . _glt('Add task') . '</div>';
	}
	}
	if ( IsSessionSettingTrue('login_perm_projman') )
	{
	if (IsSessionSettingTrue('add_objectfeature_risks'))
	{
	$sCMPart2 .= '<div class="contextmenu-item" onclick="AddChgMgmt(\'' . _j($sObjectGUID) . '\',\'risk\')">';
	$sCMPart2 .= '<img alt="" src="images/spriteplaceholder.png" class="propsprite-riskadd">' . _glt('Add risk') . '</div>';
	}
	}
	}
	if ( !strIsEmpty($sCMPart1) || !strIsEmpty($sCMPart2) )
	{
	$sContextMenu .= '<button onclick="ShowAddItemMenu()" id="properties-add-new-button" class="properties-tab" value="add-new" type="button"><img alt="" style="vertical-align: bottom;margin-right: 4px;" src="images/spriteplaceholder.png" class="element16-add">Add New</button>';
	$sContextMenu .= '<div id="element-hamburger-menu">';
	$sContextMenu .= '<div class="contextmenu-arrow-bottom"></div><div class="contextmenu-arrow-top"></div>';
	$sContextMenu .= '<div class="contextmenu-content">';
	$sContextMenu .= '<div class="contextmenu-header">' . _glt('Add Item') . '</div>';
	$sContextMenu .= '<div class="contextmenu-items">';
	$sContextMenu .= $sCMPart1;
	if ( !strIsEmpty($sCMPart1) && !strIsEmpty($sCMPart2) )
	$sContextMenu .= '<hr>';
	$sContextMenu .= $sCMPart2;
	$sContextMenu .= '</div>';
	$sContextMenu .= '</div>';
	$sContextMenu .= '</div>';
	}
	echo $sContextMenu;
?>
<script>
$(document).ready( function ()
{
	$(".contextmenu-item").click(function()
	{
	$("#hamburger-menu").hide();
	});
	$('#element-hamburger-button').click(function()
	{
	$('#element-hamburger-menu').toggle();
	});
});
$(document).mouseup(function (e)
{
	HideMenu(e,"#element-hamburger-button","#element-hamburger-menu");
});
function ShowAddItemMenu()
{
	$("#element-hamburger-menu").show();
}
</script>