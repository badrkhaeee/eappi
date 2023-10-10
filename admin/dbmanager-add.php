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
	$sDriver= SafeGetInternalArrayParameter($_POST, 'action','');
	$sType = SafeGetInternalArrayParameter($_POST, 'id','');
	$sError = '';
	$sPostBody = '';
	echo '<form id="config-add-model-form" role="form" onsubmit="onFormSubmit(event, \'#config-add-model-form\', \'addmodelconnection\')">';
	WriteBreadcrumb('Add Model Connection', 'model_repository/adding_a_model_connection.html');
	WriteHeading('Add Model Connection');
	if ($sType==='FIREBIRD')
	{
	$sError = '';
	$sPostBody = '';
	$aDataPath = ['localdbs','localdb'];
	$aLocalDBs = GetPostResults($sPCS_URL.'/config/getlocaldbs/', $sPostBody, $sError, $aDataPath);
	$aDBList = [];
	foreach ($aLocalDBs as $aLocalDB)
	{
	$sFilename = SafeGetArrayItem1Dim($aLocalDB, 'filename');
	if($sFilename !== 'EABase.fdb')
	{
	$aDBList[] = $sFilename ;
	}
	}
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteRadio('New Firebird Model','config-new-firebird','','value="FIREBIRD" onclick="onClickFirebirdSelection(this)"','db-type',true);
	echo '</div>';
	echo '<div style="padding-left: 32px;">';
	echo '<div class="config-line">';
	WriteLabel('File Name');
	WriteTextField('','config-fb-new','textfield-large','name="connection-string" required title="Enter a name for the new Firebird model. This name should not contain special characters. No path or file extension are required."');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteRadio('Existing Firebird Model','config-existing-firebird','','value="FIREBIRD" onclick="onClickFirebirdSelection(this)"','db-type');
	echo '</div>';
	echo '<div style="padding-left: 32px;">';
	echo '<div class="config-line">';
	WriteLabel('File Name');
	WriteDropdown($aDBList,'config-fb-existing','','name="connection-string" required disabled title="Select an existing Firebird model from the list. This list refers to existing feap/fdb files which exist in the Pro Cloud Server Installation\'s \'Models\' subfolder"', '');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
	else
	{
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Connection Name / Alias');
	WriteTextField('','','','name="dbms-alias" required title="Specify a short unique name for the connection. This is required when accessing the model via EA or WebEA"');
	echo '</div>';
	echo '<br>';
	echo '<div class="config-line">';
	WriteLabel('Database Type');
	WriteValue($sType,'','textvalue-medium','');
	WriteTextField($sType,'','textfield-medium','hidden name="db-type"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Driver');
	WriteValue($sDriver,'','textvalue-medium','');
	WriteTextField($sDriver,'','textfield-medium','hidden name="config-connection-driver"');
	echo '</div>';
	echo '<br>';
	if ($sType === 'oracle-oledb')
	{
	echo '<div class="config-line">';
	WriteLabel('Net Service Name');
	WriteTextField('','','','name="database" required title="Enter the Oracle Net Service Name as defined in the TNSNAMES.ORA."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('User');
	WriteTextField('','','','name="user" title="Enter the user name" required');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Password');
	WriteTextField('','','','name="password" type="password" title="Enter the password for the user specificed above"');
	echo '</div>';
	}
	else if	($sType === 'oracle-odbc')
	{
	echo '<div class="config-line">';
	WriteLabel('Server');
	WriteTextField('','','','name="server" required');
	echo '</div>';
	echo '<div class="config-line">';
	echo '<div class="config-line">';
	WriteLabel('Database');
	WriteTextField('','','','name="database" required');
	echo '</div>';
	WriteLabel('Database User');
	WriteTextField('','','','name="user" required title="Enter the user name"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Database Password');
	WriteTextField('','','','name="password" type="password" title="Enter the password for the user specificed above"');
	echo '</div>';
	}
	else
	{
	echo '<div class="config-line">';
	WriteLabel('Server / Instance');
	WriteTextField('','','','name="server" required title="Enter the Server Name or Server Instance. E.g. \'SQL-SERVER\' or \'SQL-SERVER\SQL2012\'"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Database');
	WriteTextField('','','','name="database" required title="Enter the name of the database that is the Enterprise Architect repository"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Database User');
	WriteTextField('','','','name="user" required title="Enter the user name" ');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Database Password');
	WriteTextField('','','','name="password" type="password" title="Enter the password for the user specificed above"');
	echo '</div>';
	}
	}
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'dbmanager-select-type.php\')"');
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