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
	$sPCS_URL = SafeGetInternalArrayParameter($_SESSION , 'pcs_url');
	$sError = '';
	$aDataPath = ['server-info'];
	$aData = GetPostResults($sPCS_URL.'/config/getserverinfo/','', $sError, $aDataPath);
	$sWhitelist = SafeGetArrayItem1Dim($aData, 'admin-white-list');
	$aWhitelist = explode('|', $sWhitelist);
	$aWhiteListTable = [];
	foreach ($aWhitelist as $sIP)
	{
	if(!strIsEmpty($sIP))
	{
	$aWhitelistTable[] = ['ip' => $sIP,'action' => '<img alt="" class="config-icon" src="images/delete.png" title="Remove IP Address" onclick="RemoveTableRow(this)">'];
	}
	}
	$aWhitelistTable[] = ['ip' => '<a id="link-whitelist-prompt" class="w3-link" onclick="ShowWhitelistPrompt()"><img alt="" class="config-add-icon" src="images/add.png">&lt;Add an IP Address&gt;</a>', 'action' => ''];
	echo '<form id="config-server-info-form" role="form" onsubmit="onFormSubmit(event, \'#config-server-info-form\' , \'saveserverinfo\')">';
	WriteBreadcrumb('Server Settings', 'model_repository/webconfig_server_settings.html');
	WriteHeading('Server Settings');
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Default Max Queries');
	WriteTextField($aData['default-max-sim-queries'],'','textfield-small','name="default-max-sim-queries" type="number" title="Model Connections can specify the maximum number of requests which can be serviced concurrently. This setting determines the default \'Max Simultaneous Queries\' when defining a new Model Connection"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Logging Level');
	$aDropdown = ['OFF','FATAL','WARNING','INFO','SYSTEM','DEBUG','TRACE'];
	WriteDropdown($aDropdown,'','','name="logging-level" title="Select the level of information to be written to the log file. System provides the highest level of logging. Each level incudles all logging from the lower levels also."',$aData['logging-level']);
	echo '</div>';
	echo '<div class="config-line">';
	WriteButton('Manage PCS Licenses','','',' type="button" onclick="onClickButton(\'loadpcslicense\', \'loadpcslicense\')" title="Manage Pro Cloud Certificates"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteButton('Change Password','','','type="button" onclick="onClickButton(\'loadchangepwd\', \'loadchangepwd\')" title="Set the Password which will be required when accessing the Pro Cloud Configuration Client"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteButton('Export Config','','','type="button" onclick="onClickButton(\'exportconfig\', \'exportconfig\')" title="Export the Pro Cloud Server configuration (including model connections, port definitions and integration settings) to a file. This file can be imported via the Windows \'Pro Cloud Config Client\'."');
	echo '</div>';
	echo '</div>';
	WriteCollapsibleHeading('Client White List');
	echo '<div class="config-section" style="display:none">';
	echo '<div class="config-line">';
	WriteTable('id="whitelist-table" class="config-table"', ['IP Address','Action'], $aWhitelistTable, ['ip','action']);
	echo '<input hidden name="admin-white-list" value="'.$sWhitelist.'">';
	echo '</div>';
	WriteLabel('<img alt="" class="" style="vertical-align: middle;" src="images/alert.png">&nbsp&nbspNote: If the White List is empty, then all clients are allowed.','','label-large');
	echo '</div>';
	WriteCollapsibleHeading('More Info');
	echo '<div class="config-section" style="display:none">';
	echo '<div class="config-line">';
	WriteLabel('Log File Count');
	WriteValue(SafeGetArrayItem1Dim($aData,'logging-file-count'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Log File Size (bytes)');
	WriteValue(SafeGetArrayItem1Dim($aData,'logging-max-file-size'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Log Audit Time Period (sec)');
	WriteValue(SafeGetArrayItem1Dim($aData,'audit-time-period'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Log Directory');
	WriteValue(SafeGetArrayItem1Dim($aData,'logging-directory'),'','textvalue-large','');
	echo '</div>';
	echo '<br>';
	echo '<div class="config-line">';
	WriteLabel('Floating License Keystore');
	WriteValue(SafeGetArrayItem1Dim($aData,'floating-lic-store-filename'),'','textvalue-large','');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Floating License Group Config');
	WriteValue(SafeGetArrayItem1Dim($aData,'floating-lic-config-filename'),'','textvalue-large','');
	echo '</div>';
	echo '<br>';
	echo '<div class="config-line">';
	WriteLabel('Cloud Installation Directory');
	WriteValue(SafeGetArrayItem1Dim($aData,'install-directory'),'','textvalue-large','');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Temp Directory');
	WriteValue(SafeGetArrayItem1Dim($aData,'temp-directory'),'','textvalue-large','');
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'home.php\')"');
	echo '</form>';
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
</script>