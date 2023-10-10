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
	$sAction = SafeGetInternalArrayParameter($_POST, 'action','');
	$sGUID = SafeGetInternalArrayParameter($_POST, 'id','');
	if ($sAction === 'edit')
	{
	$sError = '';
	$sPostBody = '';
	$sPostBody .= '<model-connection>';
	$sPostBody .= '<key>'.$sGUID.'</key>';
	$sPostBody .= '</model-connection>';
	$aDataPath = ['model-connection-details'];
	$aData = GetPostResults($sPCS_URL.'/config/getmodelconnection/', $sPostBody, $sError, $aDataPath);
	}
	else
	{
	$aData = [];
	}
	echo '<form id="config-save-model-form" role="form" onsubmit="onFormSubmit(event, \'#config-save-model-form\', \'savemodelconnection\')">';
	WriteBreadcrumb('Edit Model Connection', 'model_repository/webconfig_edit_model_connection.html');
	WriteHeading('Edit Model Connection');
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Alias');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'alias'),'','textfield-large','name="alias" title="Specify a short unique name for the connection. This is required when accessing the model via EA or WebEA"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Database Type');
	$sDBType = SafeGetArrayItem1Dim($aData, 'db-type');
	GetFriendlyDBType($sDBType);
	WriteValue($sDBType,'','textvalue-large','title="The database type for this model connection (read-only)"');
	echo '</div>';
	echo '<br>';
	echo '<div class="config-line">';
	WriteCheckbox('Enable Access','','','name="accept-queries" title="Check this option to enable access for this Model Connection. When disabled the this connection cannot be used."', SafeGetArrayItem1Dim($aData, 'running-state'));
	echo '</div>';
	$sProstate = SafeGetArrayItem1Dim($aData, 'pro-state');
	$sWarningIcon = '';
	if (($sProstate === 'Model Limit Reached'))
	{
	if (SafeGetArrayItem1Dim($aData, 'pro-features-requested') !== 'True')
	{
	$sDisabled = 'disabled="disabled"';
	$sWarningIcon = ' <img alt="" class="" style="vertical-align: top;" title="Pro Features Model limit has been reached. To enable Pro Features for this model you must first disable this option on another model." src="images/alert.png">';
	}
	else if ((SafeGetArrayItem1Dim($aData, 'pro-features-requested') === 'True') && (SafeGetArrayItem1Dim($aData, 'pro-features-enabled') === 'False'))
	{
	$sDisabled = '';
	$sWarningIcon = ' <img alt="" class="" style="vertical-align: top;" title="Pro Features Model limit has been reached. This Model Connection will not currently have Pro Features." src="images/alert.png">';
	}
	else
	{
	$sDisabled = '';
	}
	}
	else if($sProstate === 'No License')
	{
	$sDisabled = 'disabled="disabled"';
	$sWarningIcon = ' <img alt="" class="" style="vertical-align: top;" title="No Pro Cloud License found. Pro Features can not be enabled." src="images/alert.png">';
	}
	else
	{
	$sDisabled = '';
	}
	echo '<div class="config-line">';
	WriteCheckbox('Enable Pro Features (OSLC, WebEA and Integration)','','','name="pro-features" ' . $sDisabled . ' title="Check this option to enable for Pro Features for this Model Connection, ie WebEA, Integration Server etc"', SafeGetArrayItem1Dim($aData, 'pro-features-requested'));
	echo $sWarningIcon;
	echo '</div>';
	echo '</div>';
	WriteCollapsibleHeading('Advanced');
	echo '<div class="config-section" style="display:none">';
	echo '<div class="config-grouping">';
	echo '<div id="config-connection-string" class="config-line">';
	WriteLabel('Connection String');
	WriteValue(SafeGetArrayItem1Dim($aData, 'connection-string'),'textvalue-connection-string','textvalue-large','title="The complete connection string for the current database (read-only)"');
	echo '<input name="key" hidden value="'. SafeGetArrayItem1Dim($aData, 'key') . '">';
	echo '<input type="text" hidden value="'.SafeGetArrayItem1Dim($aData, 'connection-string').'" id="conn-string-text">';
	echo '<button id="copy-text-button" title="Copy the Connection String to the clipboard" onclick="CopyText(\'conn-string-text\')">Copy</button>';
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Max Simultaneous Queries');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'max-connections'),'','textfield-small','type="number" name="max-sim-queries" title="This value defines the maximum number of requests that can be serviced concurrently"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Minimum EA Build');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'min-ea-build'),'','textfield-small','type="number" name="min-ea-build" title="This optional value, limits the use of the current Database Manager to EA users beyond the entered build number"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('OSLC Access Code');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'access-code'),'','textfield-large','name="access-code" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title="This optional value, defines an access code that must be sent in the header of all OSLC requests in order for the OSLC service to respond"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteCheckbox('Require HTTPS and Authentication','' ,'' ,'name="secure-only" title="If checked, this model connection can only be access via https, using a port which is configured to use https with authentication enabled"' , SafeGetArrayItem1Dim($aData, 'requires-ssl'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteCheckbox('Read-only connection','','','name="read-only" title="If checked, this flag prevent users from modifying the model via this connection"', SafeGetArrayItem1Dim($aData, 'read-only'));
	echo '</div>';
	echo '</div>';
	echo '</div>';
	WriteCollapsibleHeading('Scheduled Tasks');
	echo '<div class="config-section" style="display:none">';
	echo '<div class="config-grouping">';
	echo '<div class="config-line">';
	WriteCheckbox('Run Scheduled Tasks','','','name="run-scheduled-tasks" title="If checked, this flag defines if the current Database Manager is enabled for scheduled generation of Time Series charts"', SafeGetArrayItem1Dim($aData, 'scheduled-tasks-enabled'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Start Time (HH:MM)');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'chartgen-start'),'','textfield-small chartgen-field','id="chartgen-start" name="chartgen-start" title="The time the scheduled tasks will begin (24hr format, HH:MM)."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Number of times to retry');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'chartgen-retries'),'','textfield-small chartgen-field','type="number" name="chartgen-retries" title="The number of times the task will retry if it fails."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Interval between retries (mins)');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'chartgen-interval'),'','textfield-small chartgen-field','type="number" name="chartgen-interval" title="The number of minutes between retries it the task fails."');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	WriteCollapsibleHeading('Worker Settings');
	echo '<div class="config-section" style="display:none">';
	echo '<div class="config-grouping">';
	echo '<div class="config-line">';
	WriteCheckbox('Enable Worker','','','name="is-worker-enabled" onchange="onSelectEnableWorker(this)" title="When checked, an instance of the EA Worker application will generate diagram images for WebEA/Prolaborate on a periodic basis."', SafeGetArrayItem1Dim($aData, 'is-worker-enabled'));
	echo '</div>';
	$aPortDataPath = ['ports','row'];
	$aPortData = GetPostResults($sPCS_URL.'/config/getports/','', $sError, $aPortDataPath, true);
	$aPortOptions = [];
	$aPortOptions[] = '';
	$sCurrentPort = SafeGetArrayItem1Dim($aData, 'worker-connection-port');
	$sSelectedPortOption = '';
	foreach ($aPortData as $aPort)
	{
	$sPortOption = '';
	$sProtocol = 'http';
	if (SafeGetArrayItem1Dim($aPort, 'requiresssl') === '1')
	{
	$sProtocol = 'https';
	}
	$sPortOption .= SafeGetArrayItem1Dim($aPort, 'port');
	$sPortOption .= ' ';
	$sPortOption .= '(' . $sProtocol . ')';
	if (SafeGetArrayItem1Dim($aPort, 'port') === $sCurrentPort)
	{
	$sSelectedPortOption = $sPortOption;
	}
	$aPortOptions[] = $sPortOption;
	}
	echo '<div class="config-line">';
	WriteLabel('Port');
	$aDropdown = $aPortOptions;
	WriteDropdown($aDropdown,'','','name="worker-connection-port-protocol" title="Select the Port which the Worker will use to connect to this Model."',$sSelectedPortOption);
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Model User');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'worker-connection-user'),'','textfield-large','name="worker-connection-user" title="If user security is enabled, enter the user name which the Worker will use when connecting to this Model. If user security is not enabled then this field can be left blank." autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title=""');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Model Password');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'worker-connection-pwd'),'','textfield-large','name="worker-connection-pwd" title="If user security is enabled, enter the password name which the Worker will use when connecting to this Model. If user security is not enabled then this field can be left blank." type="password" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title=""');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Update Period (seconds)');
	WriteTextField(SafeGetArrayItem1Dim($aData, 'worker-update-period'),'','textfield-small','type="number" name="worker-update-period" title="This value specifies how often (in seconds) the Worker application will save diagram images."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Logging Level');
	$aDropdown = ['OFF','FATAL','WARNING','INFO','SYSTEM','DEBUG','TRACE'];
	WriteDropdown($aDropdown,'','','name="worker-logging-level" title="Select the level of information to be written to the log file. System provides the highest level of logging. Each level incudles all logging from the lower levels also."',$aData['worker-logging-level']);
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('<img alt="" class="alert-label-icon" src="images/alert.png">&nbsp&nbspNote: Changes to the Worker Settings will not take effect until the Cloud Service is restarted.','','label-large');
	echo '</div>';
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'home.php\')"');
	echo '</form>';
?>
<script>
workerSelection = $("[name=is-worker-enabled]");
onSelectEnableWorker(workerSelection);
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
document.getElementById("copy-text-button").addEventListener("click", function(event){
  event.preventDefault()
});
</script>