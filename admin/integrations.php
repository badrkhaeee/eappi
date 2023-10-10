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
	$aCloudData = GetPostResults($sPCS_URL.'/config/getserverinfo/','', $sError, $aDataPath);
	$sDefaultSBPIPath = SafeGetArrayItem1Dim($aCloudData, 'install-directory');
	$sDefaultSBPIPath = str_replace('\Service\\','\SBPI\SBPI.exe', $sDefaultSBPIPath);
	$sDefaultSBPIPath = ConvertStringToParameter($sDefaultSBPIPath);
	$aDataPath = ['sbpiconfiguration', 'sbpiserver'];
	$aSBPIServer = GetPostResults($sPCS_URL.'/config/getsbpi/','', $sError, $aDataPath);
	$aDataPath = ['sbpiconfiguration', 'sbpiproviders', 'sbpiprovider'];
	$aSBPIProviders = GetPostResults($sPCS_URL.'/config/getsbpi/','', $sError, $aDataPath, true);
	WriteBreadcrumb('Integration', 'model_repository/webconfig_integration_plugins.html');
	echo '<form id="config-save-sbpi-form" role="form" onsubmit="onFormSubmit(event, \'#config-save-sbpi-form\', \'savesbpi\')">';
	WriteHeading('Integration Settings');
	echo '<div class="config-section" style="background-color: #f7f7f7;border-radius: 4px;padding: 12px;border: 1px solid #f1f1f1; margin-bottom:24px;">';
	echo '<div class="config-line">';
	echo '<div id="config-sbpi-useproxy" style="float:right;" title="Only check this option if your environment requires the Pro Cloud Server to communicate to the SBPI Server on one URL but Enterprise Architect clients are required to communicate to a different URL because of a Proxy.">';
	WriteCheckbox('Use Legacy Settings','','','name="uselegacy" onchange="onSelectUseLegacy(this)"', SafeGetArrayItem1Dim($aSBPIServer, 'uselegacy'),true);
	echo '</div>';
	echo '</div>';
	echo '<div class="config-line">';
	WriteCheckbox('Enable Integration','','','name="enabled" title="Enable or disable the Integration Server on the Pro Cloud Server."', SafeGetArrayItem1Dim($aSBPIServer, 'enabled'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Port');
	WriteTextField(SafeGetArrayItem1Dim($aSBPIServer, 'localport'),'','textfield-small','id="sbpilocalport" name="localport" type="number" required title="The port the Integration Plugin is configured to listen to."');
	echo '</div>';
	echo '<div class="config-legacy-settings" style="display:none">';
	echo '<div id="config-line-int-protocol" class="config-line">';
	echo '<div class="config-line-url">';
	echo '<div>Protocol</div><div>Name/IP</div><div>Port</div>';
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('SBPI Server URL');
	$aDropdown = ['http','https'];
	WriteDropdown($aDropdown,'config-sbpiserver-protocol','','name="protocol" title="The transfer protocol that Enterprise Architect communicates to the Integration Server."', SafeGetArrayItem1Dim($aSBPIServer, 'protocol'));
	$sServer = SafeGetArrayItem1Dim($aSBPIServer, 'server');
	WriteTextField($sServer,'config-sbpiserver-ip','textfield-medium','name="server" title="The server name (or IP) that hosts the Integration Server. This name must be resolvable by the Enterprise Architect clients."');
	WriteTextField(SafeGetArrayItem1Dim($aSBPIServer, 'port'),'','textfield-small config-sbpiserver-port-legacy','name="port" type="number" min="0" max="65535" title="The port the Integration Server will listen for connections on."');
	WriteCheckbox('Ignore SSL Errors','','','name="ignoressl" title="Indicates if Enterprise Architect clients should ignore all SSL errors while communicating to the Integration Server."', SafeGetArrayItem1Dim($aSBPIServer, 'ignoressl'), true);
	echo '</div>';
	echo '</div>';
	echo '<div class="config-line">';
	WriteCheckbox('Attempt URL Auto Discovery','','',' onclick="onSelectAutoDiscover(this)" name="attemptautodiscovery" title="Attempt URL Auto Discovery."', SafeGetArrayItem1Dim($aSBPIServer, 'attemptautodiscovery'));
	echo '</div>';
	echo '<div class="config-line">';
	echo '<div id="config-sbpi-proxyurl">';
	echo '<div>';
	WriteLabel('Fallback URL', '', 'label config-sbpi-fallback');
	$aDropdown = ['http','https'];
	WriteDropdown($aDropdown,'config-sbpiserver-protocol','','name="clientprotocol" title="Enterprise Architect clients will be told to use the defined Protocol to communicate to the Integration server."', SafeGetArrayItem1Dim($aSBPIServer, 'clientprotocol'));
	WriteTextField(SafeGetArrayItem1Dim($aSBPIServer, 'clientserver'),'config-sbpiserver-ip','textfield-medium','name="clientserver" title="Enterprise Architect clients will be told to connect to the Integration server name (or IP) defined by this field, which is potentially different than that of the physical machine name. This name (or IP) must be resolvable by the Enterprise Architect clients."');
	WriteTextField(SafeGetArrayItem1Dim($aSBPIServer, 'clientport'),'','textfield-small config-sbpiserver-port-legacy','name="clientport" type="number" min="0" max="65535" title="Enterprise Architect clients will be told to connect to the Integration server on this port instead of the port the server will listen on."');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	WriteButton('Apply','','button button-apply','');
	echo '</div>';
	echo '</form>';
	foreach ($aSBPIProviders as &$aRow)
	{
	if (strIsTrue($aRow['enabled']))
	{
	$aRow['status_icon'] = '<img alt="" class="dot-green-icon" title="Provider is Enabled" src="images/spriteplaceholder.png">';
	}
	else
	{
	$aRow['status_icon'] = '<img alt="" class="dot-red-icon" title="Provider is Disabled" src="images/spriteplaceholder.png">';
	}
	$aRow['actions'] = '<img alt="" class="config-icon" title="Edit Provider" onclick="loadConfigPage(\'integration-add-provider.php\',\'edit\',\''.$aRow['guid'] .'\')" src="images/edit.png">';
	$aRow['actions'] .= '<img alt="" class="config-icon" title="Edit Model Bindings" onclick="loadConfigPage(\'integration-bindings.php\',\'edit\',\''.$aRow['guid'] .'\')" src="images/binding.png">';
	$aRow['actions'] .= '<img alt="" class="config-icon" src="images/delete.png" title="Delete Provider" onclick="onClickButton(\'deletesbpiprovider\',\''.ConvertStringToParameter($aRow['guid']).'\')">';
	}
	foreach ($aSBPIProviders as &$aSBPIProvider)
	{
	$sBindingList = '';
	$sPostBody = '';
	$sPostBody .= '<sbpi-bindings>';
	$sPostBody .= '<plugin>' . $aSBPIProvider['guid']. '</plugin>';
	$sPostBody .= '</sbpi-bindings>';
	$aDataPath = ['sbpiconfiguration','sbpibindings','sbpibinding'];
	$aBindings = GetBindings($sPCS_URL, $sPostBody, $sError,  $aDataPath);
	if (is_array($aBindings))
	{
	$iLast = count($aBindings);
	$i=0;
	foreach ($aBindings as $aBinding)
	{
	$i++;
	$sBindingList .= $aBinding['dbalias'];
	if ($i !== $iLast)
	$sBindingList .=  ', ';
	}
	}
	if (strIsEmpty($sBindingList))
	{
	$sBindingList = '<img alt="" class="" style="vertical-align: top; height:16px" title="No bindings defined. This Integration Provider will not currently be accessible in any Models." src="images/alert.png">';
	}
	$aSBPIProvider['bindings'] = $sBindingList;
	}
	$aSBPIProviders[] = ['status_icon' => '<img alt="" class="config-add-icon" src="images/add.png">','name' => '<a class="w3-link" onclick="loadConfigPage(\'integration-add-provider.php\')">&lt;Add a Provider&gt;</a>'];
	$aHeader = [
	'<div></div>',
	'<div title="A friendly name that describes the provider, this value will show within Enterprise Architect.">Name</div>',
	'<div title="The type of external provider.">Provider</div>',
	'<div title="A short unique value for the provider. This value is added to each external link within Enterprise Architect.">Prefix</div>',
	'<div title="The port the Integration Plugin is configured to listen to.">Port</div>',
	'<div title="Bindings refer to the model connections which will have access to this integration plugin. These can be configured via the Actions column.">Bindings</div>',
	'Actions'
	];
	$aFields = ['status_icon','name', 'type', 'prefix','port','bindings','actions'];
	$sTableAttr = 'id="integrations-table" class="config-table"';
	WriteHeading('Integration Providers');
	WriteTable($sTableAttr, $aHeader, $aSBPIProviders, $aFields);
	WriteButton('OK','','button button-ok','onclick="loadConfigPage(\'home.php\')"');
?>
<script>
	var bIsDirty = false;
	$(document).ready(function() {
	   $('input, select').change(function() {
	bIsDirty = true;
	   });
	});
	useLegacy = $("[name=uselegacy]");
	onSelectUseLegacy(useLegacy);
	autoDiscovery = $("[name=attemptautodiscovery]");
	onSelectAutoDiscover(autoDiscovery);
</script>