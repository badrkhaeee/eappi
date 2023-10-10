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
	$sAction = SafeGetInternalArrayParameter($_POST, 'action','');
	$sGUID = SafeGetInternalArrayParameter($_POST, 'id','');
	$sError = '';
	$sPostBody = '';
	$aDataPath = ['db-drivers'];
	$aDrivers = GetPostResults($sPCS_URL.'/config/getdbdrivers/', $sPostBody, $sError, $aDataPath);
	WriteBreadcrumb('Select Connection Type', 'model_repository/adding_a_model_connection.html');
	WriteHeading('Select Connection Type');
	echo '<div class="config-section">';
	echo '<div class="config-radio-line">';
	WriteRadio('Firebird','','','value="FIREBIRD" checked' ,'db-type',false);
	echo '</div>';
	if (!empty(SafeGetArrayItem1Dim($aDrivers, 'mysql-odbc-driver')))
	{
	echo '<div class="config-radio-line">';
	WriteRadio('MySQL','','','value="mysql"','db-type',false);
	WriteTextField(SafeGetArrayItem1Dim($aDrivers, 'mysql-odbc-driver'),'','textfield-medium','hidden');
	echo '</div>';
	}
	if (!empty(SafeGetArrayItem1Dim($aDrivers, 'postgresql-odbc-driver')))
	{
	echo '<div class="config-radio-line">';
	WriteRadio('PostgreSQL','','','value="postgresql"','db-type',false);
	WriteTextField(SafeGetArrayItem1Dim($aDrivers, 'postgresql-odbc-driver'),'','textfield-medium','hidden');
	echo '</div>';
	}
	if (!empty(SafeGetArrayItem1Dim($aDrivers, 'oracle-oledb-driver')))
	{
	echo '<div class="config-radio-line">';
	WriteRadio('Oracle (OLE DB)','','','value="oracle-oledb"','db-type',false);
	WriteTextField(SafeGetArrayItem1Dim($aDrivers, 'oracle-oledb-driver'),'','textfield-medium','hidden');
	echo '</div>';
	}
	if (!empty(SafeGetArrayItem1Dim($aDrivers, 'sqlserver-oledb-driver-2')))
	{
	echo '<div class="config-radio-line">';
	WriteRadio('SQL Server','','','value="sqlserver2"','db-type',false);
	WriteTextField(SafeGetArrayItem1Dim($aDrivers, 'sqlserver-oledb-driver-2'),'','textfield-medium','hidden');
	echo '</div>';
	}
	if (!empty(SafeGetArrayItem1Dim($aDrivers, 'sqlserver-oledb-driver-1')))
	{
	echo '<div class="config-radio-line" title="SQL Server (pre TLS 1.2) refers to the SQL Server driver which is included with Windows by default. More recent SQL Server drivers have added TLS 1.2 support.">';
	WriteRadio('SQL Server (pre TLS 1.2)','','','value="sqlserver1"','db-type',false);
	WriteTextField(SafeGetArrayItem1Dim($aDrivers, 'sqlserver-oledb-driver-1'),'','textfield-medium','hidden');
	echo '</div>';
	}
	echo '<div class="config-line" style="padding: 4px 0px 8px 0px">';
	WriteLabel('Note: Each connection type is only listed if the relevant driver is found on the server.','','label-large');
	echo '</div>';
	echo '</div>';
	WriteButton('Next','','button button-ok','type="button" onclick="onClickSelectType()"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'home.php\')"');
?>
<script>
var bIsDirty = false;
</script>