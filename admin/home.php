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
	SafeStartSession();
	$sPCS_URL = SafeGetInternalArrayParameter($_SESSION, 'pcs_url');
	$aPCS_URL = explode(':', $sPCS_URL);
	$sServerName = $aPCS_URL[1];
	$sServerName = str_replace('//' , '', $sServerName);
	$sError = '';
	$aDataPath = ['server-info'];
	$aData = GetPostResults($sPCS_URL.'/config/getserverinfo/','', $sError, $aDataPath);
	$sProEdition = SafeGetArrayItem1Dim($aData, 'pro-edition');
	$sLicenseContact = SafeGetArrayItem1Dim($aData, 'license-contact');
	$sLicenseCompany = SafeGetArrayItem1Dim($aData, 'license-company');
	$sProExpiryDate = SafeGetArrayItem1Dim($aData, 'pro-expiry-date');
	$sProExpiryDate = RemoveTime($sProExpiryDate);
	$sServerVersion = SafeGetArrayItem1Dim($aData, 'server-version');
	$iLastPeriod =  strrpos( g_csWebConfigVersion , '.' );
	$sWebClientVersion = substr(g_csWebConfigVersion, 0, $iLastPeriod);
	if ($sServerVersion !== $sWebClientVersion)
	{
	$sMismatch = '&nbsp&nbsp<img alt="" class="" style="vertical-align: bottom;" title="Warning: The Pro Cloud Server Version is ' . $sServerVersion . ', however the WebConfig version is ' . $sWebClientVersion . '. The WebConfig client may not function as expected. Ensure you are using the WebConfig files which were included with the installed version of the Pro Cloud Server." src="images/alert.png">';
	}
	else
	{
	$sMismatch = '';
	}
	WriteBreadCrumb('Home', 'model_repository/webconfig_home_screen.html');
	echo '<div style="width: 460px;display: inline-block;">';
	WriteHeading('Server Summary','','','');
	echo '<div class="config-section" style="background-color: #f7f7f7;border-radius: 4px; padding: 12px; border: 1px solid #f1f1f1; margin-bottom:24px;">';
	echo '<div class="config-line" title="The Server Name which is being used to access the Pro Cloud Server">';
	WriteLabel('Server Name','','label-summary','');
	WriteValue($sServerName,'','textvalue-summary');
	echo '</div>';
	echo '<div class="config-line" title="Version number of the Pro Cloud Server">';
	WriteLabel('Version','','label-summary','');
	WriteValue($sServerVersion . $sMismatch,'','textvalue-summary');
	echo '</div>';
	$bNoLicense = false;
	$sTokenString = '';
	$sConfigIcon = '';
	$sTokenCount = SafeGetArrayItem1Dim($aData, 'pcs-license-token-count');
	if ($sProEdition === 'Free')
	{
	$bNoLicense = true;
	$sProEdition = 'No License <a class="w3-link" title="Click to Add a Pro Cloud License" onclick="loadConfigPage(\'pcs-licenses.php\')">[Add]</a> <img alt="" class="" style="vertical-align: bottom;" title="No Valid Pro License found. Cloud Model Connections will be accessible, but without Pro Features." src="images/alert.png">';
	$sProExpiryDate = 'Never';
	}
	else
	{
	echo '<div class="config-line" title="Number of Model Connections with Pro Features Enabled">';
	$sProModelsRequested = SafeGetArrayItem1Dim($aData, 'pro-models-requested');
	$sProModelsEnabled = SafeGetArrayItem1Dim($aData, 'pro-models-enabled');
	if ($sProModelsRequested > $sProModelsEnabled)
	{
	if ($sProModelsEnabled === '0')
	{
	$sProModelsString = $sProModelsEnabled. ' Model Connections';
	}
	else if ($sProModelsEnabled === '1')
	{
	$sProModelsString = 'only ' . $sProModelsEnabled. ' Model Connection';
	}
	else
	{
	$sProModelsString = 'only ' . $sProModelsEnabled. ' Model Connections';
	}
	$sAlertIcon = '&nbsp<img alt="" class="summary-alert-icon" title="'.$sProModelsRequested.' Model Connections have the Pro Features Option Enabled, however due to license restrictions ' . $sProModelsString . ' will have Pro Features" src="images/alert.png">';
	}
	else
	{
	$sAlertIcon = '';
	}
	$sProModelsMax = '';
	if (($sProEdition === 'Team Server') || ($sProEdition === 'Express'))
	{
	$sProModelsMax = SafeGetArrayItem1Dim($aData, 'pro-max-models-allowed');
	$sProModelsMax = ' / ' . $sProModelsMax;
	}
	WriteLabel('Pro Models','','label-summary','');
	if ($sProModelsRequested === $sProModelsEnabled)
	{
	WriteValue($sProModelsEnabled . $sProModelsMax,'','textvalue-summary');
	}
	else
	{
	WriteValue($sProModelsEnabled . $sProModelsMax,'','textvalue-summary-pro');
	echo $sAlertIcon;
	}
	echo '</div>';
	if ($sProEdition === 'Token')
	{
	$sProEdition = $sTokenCount.' Tokens';
	}
	$sConfigIcon = '<img alt="" id="config-cog-icon" class="config-icon" src="images/config.png" title="Configure License and Token Allocations" onclick="onClickButton(\'loadpcslicense\', \'loadpcslicense\')">';
	}
	echo '<div class="config-line" title="The type of Pro Cloud Server License">';
	WriteLabel('License','','label-summary','');
	WriteValue($sProEdition,'','textvalue-summary-edition');
	echo $sConfigIcon;
	echo '</div>';
	if (!strIsEmpty($sProExpiryDate))
	{
	echo '<div class="config-line" title="Expiry date of the Pro Cloud Server license">';
	WriteLabel('Expiry','','label-summary','');
	WriteValue($sProExpiryDate,'','textvalue-summary');
	echo '</div>';
	}
	if (!$bNoLicense)
	{
	echo '<div class="config-line" title="The company name associated with this license: ' . SafeGetArrayItem1Dim($aData, 'license-company') . '">';
	WriteLabel('Licensed to','','label-summary','');
	WriteValue(SafeGetArrayItem1Dim($aData, 'license-company'),'','textvalue-summary-company');
	echo '</div>';
	echo '<div class="config-line" title="The email address associated with this license: ' . SafeGetArrayItem1Dim($aData, 'license-email') . '">';
	WriteLabel('Licensee','','label-summary','');
	WriteValue(SafeGetArrayItem1Dim($aData, 'license-email'),'','textvalue-summary-email');
	echo '</div>';
	}
	$aPortDataPath = ['ports','row'];
	$aPortData = GetPostResults($sPCS_URL.'/config/getports/','', $sError, $aPortDataPath, true);
	$sPorts = '';
	$iLast = count($aPortData);
	$bFoundProPort = false;
	$bFoundHTTPSPort = false;
	$aPortsHttp = [];
	$aPortsHttps = [];
	foreach ($aPortData as $aPort)
	{
	$sPort = SafeGetArrayItem1Dim($aPort, 'port');
	$bIsHTTPS = SafeGetArrayItem1Dim($aPort, 'requiresssl');
	$bIsPro = SafeGetArrayItem1Dim($aPort, 'oslc');
	if($bIsHTTPS)
	{
	$aPortsHttps[] = $sPort;
	$bFoundHTTPSPort = true;
	}
	else
	{
	$aPortsHttp[] = $sPort;
	}
	if($bIsPro)
	{
	$bFoundProPort = true;
	}
	}
	$sHttpPorts = '';
	$bIsFirst = true;
	foreach ($aPortsHttp as $sHttpPort)
	{
	if ($bIsFirst)
	{
	$bIsFirst = false;
	}
	else
	{
	$sHttpPorts .= ', ';
	}
	$sHttpPorts .= $sHttpPort;
	}
	if (strIsEmpty($sHttpPorts))
	{
	$sHttpPorts = 'None';
	}
	$sHttpsPorts = '';
	$bIsFirst = true;
	foreach ($aPortsHttps as $sHttpsPort)
	{
	if($bIsFirst)
	{
	$bIsFirst = false;
	}
	else
	{
	$sHttpsPorts .= ', ';
	}
	$sHttpsPorts .= $sHttpsPort;
	}
	if (strIsEmpty($sHttpsPorts))
	{
	$sHttpsPorts = 'None';
	}
	function sort_model_ports($a,$b) {
	  return mb_strtolower($a['port']) > mb_strtolower($b['port']);
	}
	echo '<div class="config-line" title="Ports which are currently defined for use by Model Connections">';
	WriteLabel('Model Ports','','label-ports','');
	WriteLabel('http','','label-port-protocol','');
	WriteValue($sHttpPorts,'','textvalue-summary');
	echo '</div>';
	echo '<div class="config-line" title="Ports which are currently defined for use by Model Connections">';
	WriteLabel('','','label-ports','');
	WriteLabel('https','','label-port-protocol','');
	WriteValue($sHttpsPorts,'','textvalue-summary');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div style="width: 420px;display: inline-block; vertical-align: top; margin-left:18px;">';
	WriteHeading('Tasks','','','');
	WriteButton('Server Settings','','button button-config-server', 'onclick="loadConfigPage(\'pcs-config.php\')" title="Configure Pro Cloud Server settings (Log Level, Password, etc)"');
	WriteButton('Configure Ports','','','style="display: block;" onclick="loadConfigPage(\'ports.php\')" title="Configure Ports which the Pro Cloud Server will listen on"');
	if (!strIsTrue($bNoLicense))
	{
	WriteButton('Configure Integration','','','style="display: block;" onclick="loadConfigPage(\'integrations.php\')" title="Configure Intgration Plugins"');
	}
	WriteButton('Manage EA Licenses','','','style="display: block;" onclick="loadConfigPage(\'floating-licenses.php\')" title="Manage EA Floating Licenses"');
	WriteButton('View Logs','','','style="display: block;" onclick="loadConfigPage(\'logs.php\')" title="View Pro Cloud Server Logs"');
	echo '</div>';
	echo '<br>';
	$aDataPath = ['model-connections','model-connection'];
	$aData = GetPostResults($sPCS_URL.'/config/getmodelconnections/','', $sError, $aDataPath, true);
	foreach ($aData as $key => &$aRow)
	{
	if ($aRow['running-state'] === 'True')
	{
	$aRow['status_icon'] = '<img alt="" class="dot-green-icon" title="Access is Enabled" src="images/spriteplaceholder.png">';
	}
	else
	{
	$aRow['status_icon'] = '<img alt="" class="dot-red-icon" title="Access is Disabled" src="images/spriteplaceholder.png">';
	}
	if (is_array($aRow['alias']))
	$aRow['alias'] = '';
	GetFriendlyDBType($aRow['db-type']);
	if ((SafeGetArrayItem1Dim($aRow, 'pro-features-requested') === 'True') && (SafeGetArrayItem1Dim($aRow,'pro-features-enabled') === 'False'))
	{
	$aRow['pro-features-enabled'] = '<div><div class="table-icon-container"><img alt="" class="" style="vertical-align: bottom;" title="The maximum number of Pro Models has been exceeded. This Model Connection will not currently have Pro Features." src="images/alert.png"></div></div>';
	}
	if ($aRow['pro-features-enabled'] === 'True')
	{
	if($bFoundProPort === false)
	{
	$aRow['pro-features-enabled'] = '<div><div class="table-icon-container"><img alt="" class="" style="vertical-align: bottom;" title="Pro Features option is enabled for this connection, however no Ports have OSLC / WebEA Support enabled." src="images/alert.png"></div></div>';
	}
	else
	{
	$aRow['pro-features-enabled'] = '<div><div class="table-icon-container" title="Pro Features (WebEA, Integration Server, etc) are enabled"><img alt="" class="tick-icon" src="images/spriteplaceholder.png"></div></div>';
	}
	}
	if ($aRow['requires-ssl'] === 'True')
	{
	if ($bFoundHTTPSPort === false)
	{
	$aRow['requires-ssl'] = '<div><div class="table-icon-container"><img alt="" class="" style="vertical-align: bottom;" title="The \'HTTPS Only\' option is enabled for this connection, however no Ports have \'Require HTTPS\' enabled." src="images/alert.png"></div></div>';
	}
	else
	{
	$aRow['requires-ssl'] = '<div><div class="table-icon-container" title="This connection can only be accessed via HTTPS (via a Port which is also configured to use the HTTPS protocol)"><img alt="" class="tick-icon" src="images/spriteplaceholder.png"></div></div>';
	}
	}
	if ($aRow['read-only'] === 'True')
	{
	$aRow['read-only'] = '<div><div class="table-icon-container" title="This connection provides Read-Only access to the model"><img alt="" class="tick-icon" src="images/spriteplaceholder.png"></div></div>';
	}
	$aRow['action'] = '<img alt="" class="config-icon" src="images/edit.png" title="Edit Connection" onclick="loadConfigPage(\'dbmanager-edit.php\',\'edit\',\''.ConvertStringToParameter($aRow['key']) .'\')">';
	$aRow['action'] .= '<img alt="" class="config-icon" src="images/delete.png" title="Delete Connection" onclick="onClickButton(\'deletemodelconnection\',\''.ConvertStringToParameter($aRow['key']).'\')">';
	}
	$iConnectionCount = count($aData);
	usort($aData, "sort_model_by_alias");
	function sort_model_by_alias($a,$b) {
	  return mb_strtolower($a['alias']) <=> mb_strtolower($b['alias']);
	}
	$aData[] = ['status_icon' => '<img alt="" class="config-add-icon" src="images/add.png">' , 'alias' => '<a class="w3-link" onclick="loadConfigPage(\'dbmanager-select-type.php\')">&lt;Add a Connection&gt;</a>'];
	$aHeader = [
	'',
	'<div title="The Model Name/Alias is the unique identifier which is used to access this model connection (via EA, WebEA, etc)">Name / Alias</div>',
	'<div title="Indicates whether Pro Features (WebEA, Integration Server, etc) are enabled for this model connection">Pro</div>',
	'<div title="The database type of the model">Database Type</div>',
	'<div title="Indicates whether this connection provides Read-Only access to the model">Read Only</div>',
	'<div title="When enabled, this connection can only be accessed via HTTPS (via a Port which is also configured to use the HTTPS protocol)">Require HTTPS</div>',
	'Actions'
	];
	$aFields = ['status_icon','alias', 'pro-features-enabled', 'db-type', 'read-only', 'requires-ssl',  'action'];
	$sTableAttr = 'id="dbmanager-table" class="config-table"';
	WriteHeading('Model Connections ('. $iConnectionCount . ')','','config-connections-heading','');
	WriteTable($sTableAttr, $aHeader, $aData, $aFields);
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
</script>