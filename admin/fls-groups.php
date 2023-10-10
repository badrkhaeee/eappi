<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	$sRootPath = dirname(__FILE__);
	require_once $sRootPath . '/globals.php';
	CheckDirectNavigation();
	SafeStartSession();
	$sPCS_URL = $_SESSION['pcs_url'];
	$aDataPath =['fls-groups','fls-group'];
	$aAllGroups = GetPostResults($sPCS_URL.'/config/getflsallgroups/','', $sError, $aDataPath, true);
	WriteBreadcrumb('Floating License Groups', 'model_repository/webconfig_manage_floating_licenses.html');
	WriteHeading('Configure Floating License Groups');
	foreach ($aAllGroups as &$aRow)
	{
	$aRow['actions'] = '<img alt="" class="config-icon" src="images/edit.png" title="Edit Group" onclick="loadConfigPage(\'fls-group-add.php\',\'flsgroupedit\',\''.ConvertStringToParameter($aRow['username']).'\')">';
	$aRow['actions'] .= '<img alt="" class="config-icon" src="images/delete.png" title="Delete Group" onclick="onClickButton(\'deleteflsgroup\',\''.ConvertStringToParameter($aRow['username']).'\')">';
	}
	$aAllGroups[] = ['username' => '<img alt="" class="config-add-icon" src="images/add.png">&nbsp<a class="w3-link" onclick="loadConfigPage(\'fls-group-add.php\',\'flsgroupadd\',\'\')">&lt;Add Group&gt;</a>'];
	$aHeader = [
	'<div title="">User Name</div>',
	'<div title="">Description</div>',
	'<div title="">Start</div>',
	'<div title="">End</div>',
	'<div title="">Is Admin</div>',
	'Actions'];
	$aFields = ['username','description','start-date','end-date','is-manager','actions'];
	$sTableAttr = 'id="keys-table" class="config-table"';
	WriteTable($sTableAttr, $aHeader, $aAllGroups, $aFields);
	WriteButton('OK','','button button-ok','onclick="loadConfigPage(\'floating-licenses.php\')"');
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
</script>