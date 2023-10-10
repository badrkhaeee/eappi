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
	$sSelectedPort = SafeGetInternalArrayParameter($_POST, 'id','');
	$aDataPath = ['server-info'];
	$aServerData = GetPostResults($sPCS_URL.'/config/getserverinfo/','', $sError, $aDataPath);
	$sProEdition = SafeGetArrayItem1Dim($aServerData, 'pro-edition');
	if ($sAction === 'null')
	$sAction = 'addport';
	$sHeader = 'Add Port';
	$sPort = '';
	$sProtocol = '';
	$bRequiresSSL = '';
	$bOSLC = '1';
	$sDefaultModel = '';
	$sAuthType = 'None';
	$sGlobalAuth = '';
	$bTLSv13 = '1';
	$bTLSv12 = '1';
	$bTLSv11 = '0';
	$bTLSv10 = '0';
	$bSSLv1 = '0';
	if ($sAction === 'addport')
	{
	$sHeader = 'Add Port';
	$sSubmitAction = 'addport';
	$sDisableGlobalAuth = 'disabled=""';
	$aDataPath = ['ports','row'];
	$aData = GetPostResults($sPCS_URL.'/config/getports/','', $sError, $aDataPath, true);
	$aInUse = [];
	foreach ($aData as $aPort)
	{
	$aInUse[] = SafeGetArrayItem1Dim($aPort, 'port');
	}
	}
	else if ($sAction === 'editport')
	{
	$sSubmitAction = 'saveport';
	$aDataPath = ['ports','row'];
	$aPorts = GetPostResults($sPCS_URL.'/config/getports/','', $sError, $aDataPath, true);
	$aData = [];
	foreach ($aPorts as $aRow)
	{
	if($aRow['port'] === $sSelectedPort)
	{
	$aData = $aRow;
	}
	}
	$sPort = SafeGetArrayItem1Dim($aData, 'port');
	$bRequiresSSL = SafeGetArrayItem1Dim($aData, 'requiresssl');
	if ($bRequiresSSL == 1)
	{
	$sProtocol = 'https';
	}
	$bTLSv13 = SafeGetArrayItem1Dim($aData, 'tlsv1-3');
	$bTLSv12 = SafeGetArrayItem1Dim($aData, 'tlsv1-2');
	$bTLSv11 = SafeGetArrayItem1Dim($aData, 'tlsv1-1');
	$bTLSv10 = SafeGetArrayItem1Dim($aData, 'tlsv1-0');
	$bSSLv1 = SafeGetArrayItem1Dim($aData, 'sslv3');
	$bOSLC = SafeGetArrayItem1Dim($aData, 'oslc');
	$sDefaultModel = SafeGetArrayItem1Dim($aData, 'defaultmodel');
	$sGlobalAuth = SafeGetArrayItem1Dim($aData, 'globalauth');
	$sDisableGlobalAuth = 'disabled=""';
	if (!empty($sGlobalAuth))
	{
	$sAuthType = 'Global';
	$sDisableGlobalAuth = '';
	}
	else if (SafeGetArrayItem1Dim($aData, 'modelauth') === '1')
	{
	$sAuthType = 'Model';
	$sGlobalAuth = '';
	}
	else
	{
	$sAuthType = 'None';
	$sGlobalAuth = '';
	}
	$sHeader = 'Edit Port';
	}
	echo '<form id="config-add-port-form" role="form" onsubmit="onFormSubmit(event, \'#config-add-port-form\', \''.$sSubmitAction.'\')">';
	WriteBreadcrumb($sHeader, 'model_repository/webconfig_add_or_edit_a_port.html');
	WriteHeading($sHeader);
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Server Port<span class="field-label-required">&nbsp;*</span>');
	if ($sAction === 'addport')
	{
	WriteTextField($sPort,'','textfield-small','type="number" min="0" max="65535" name="port" required title="Defines the Port number that the Cloud Service will listen for HTTP/HTTPS connections. Must be a numeric value."');
	WriteTextField(htmlspecialchars(json_encode($aInUse)),'config-ports-inuse','textfield-medium','hidden');
	}
	else
	{
	WriteTextField($sPort, '', 'textfield-small', 'hidden name="port"');
	WriteValue($sPort);
	}
	echo '</div>';
	if($sProtocol==='https')
	{
	$sVersionsDisabled = '';
	$sCheckboxDisabled = '';
	}
	else
	{
	$sVersionsDisabled = 'class="tls-disabled"';
	$sCheckboxDisabled = 'disabled="true"';
	}
	echo '<div class="config-grouping">';
	echo '<div class="config-line">';
	WriteLabel('Protocol<span class="field-label-required">&nbsp;*</span>');
	$aDropdown = ['http','https'];
	WriteDropdown($aDropdown, '','','name="protocol" onchange="onSelectPortProtocol(this)"  required title="The transfer protocol that the Provider Server communicates on."', $sProtocol);
	echo '<div id="config-tls-versions" ' . $sVersionsDisabled . ' title="Select which versions of TLS/SSL are allowed for HTTPS">';
	WriteCheckbox('TLS 1.3','','','name="tlsv1-3" ' . $sCheckboxDisabled, $bTLSv13, true);
	echo '&nbsp&nbsp&nbsp';
	WriteCheckbox('TLS 1.2','','','name="tlsv1-2" ' . $sCheckboxDisabled, $bTLSv12, true);
	echo '&nbsp&nbsp&nbsp';
	WriteCheckbox('TLS 1.1','','','name="tlsv1-1" ' . $sCheckboxDisabled, $bTLSv11, true);
	echo '&nbsp&nbsp&nbsp';
	WriteCheckbox('TLS 1.0','','','name="tlsv1-0" ' . $sCheckboxDisabled, $bTLSv10, true);
	echo '&nbsp&nbsp&nbsp';
	WriteCheckbox('SSL 3.0','','','name="sslv3" ' . $sCheckboxDisabled, $bSSLv1, true);
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div class="config-grouping">';
	echo '<div class="config-line" style="padding-bottom:12px">';
	WriteCheckbox('WebEA / OSLC Supported','','','name="oslc" title="If checked, this flag enables OSLC requests on the current Port.  Note: this option is needed for WebEA"', $bOSLC);
	if ($sProEdition === 'Free')
	{
	echo '&nbsp<img alt="" class="" style="vertical-align: top;" title="No Pro Cloud License found. WebEA / OSLC functionality will not be enabled." src="images/alert.png">';
	}
	echo '</div>';
	echo '</div>';
	echo '<div class="config-grouping">';
	echo '<div class="config-line">';
	WriteLabel('Authentication<span class="field-label-required">&nbsp;*</span>');
	$aDropdown = ['None','Model','Global'];
	WriteDropdown($aDropdown, '', '', 'onchange="onSelectPortAuth(this)" name="authtype" title="Defines the type of authentication used by the current Port."', $sAuthType);
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Global Authentication Model');
	WriteTextField($sGlobalAuth,'config-port-auth-model','','name="globalauth" required title="Required only when the Global Authentication option is used. When a valid connection name/alias is specified, the given model will provide the list of users for all models connecting via the current Port."' . $sDisableGlobalAuth );
	echo '</div>';
	echo '</div>';
	echo '<div class="config-grouping">';
	echo '<div class="config-line port-req-field-line">';
	echo '<div class="config-required-field-message">* Required Fields</div>';
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('<img alt="" class="alert-label-icon" src="images/alert.png">&nbsp&nbspNote: Changes made on this page will not take effect until the Cloud Service is restarted.','','label-large');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'ports.php\')"');
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