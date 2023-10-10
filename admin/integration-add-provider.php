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
	$aDataPath = [];
	$aProviderTypesAll = GetPostResults($sPCS_URL.'/config/getsbpiprovidertypes/','', $sError, $aDataPath, true);
	$aProviderTypes = $aProviderTypesAll['provider-types']['provider-type'];
	$aDataPath = ['sbpi-config-dataprovider-withtype'];
	$sPostBody = '<sbpi-dataproviderwithtype><key>sstx</key></sbpi-dataproviderwithtype>';
	$sTranslatorCheck = GetPostResults($sPCS_URL.'/config/sbpidataproviderwithtype/',$sPostBody, $sError, $aDataPath, true);
	$bHasTranslator = SafeGetArrayItem1Dim($sTranslatorCheck, 'exists');
	$sGroupList = SafeGetArrayItem1Dim($aProviderTypesAll, 'grouplist');
	$aCustomGroupList = str_getcsv($sGroupList);
	$sDLLList = SafeGetArrayItem1Dim($aProviderTypesAll, 'csvofcustomdlls');
	$aDLLList = str_getcsv($sDLLList);
	$aSupportedProviders = [];
	foreach ($aProviderTypes as &$aProviderType)
	{
	$bExclude = false;
	foreach ($aProviderType as &$aProviderValue)
	{
	if (is_array($aProviderValue) && empty($aProviderValue))
	{
	$aProviderValue = "";
	}
	}
	if (($sAction !== 'edit') &&
	(strIsTrue($bHasTranslator)) &&
	$aProviderType['key']==='sstx')
	{
	$bExclude = true;
	}
	if(!$bExclude)
	{
	$aSupportedProviders[] = [$aProviderType['name'], $aProviderType['key']];
	}
	$aProviderType['defaultcustomprops'] = html_entity_decode($aProviderType['defaultcustomprops'],ENT_QUOTES);
	}
	echo '<div id="sbpi-json" style="display:none;">'. json_encode($aProviderTypes) .'</div>';
	if ($sAction === 'edit')
	{
	$sError = '';
	$sPostBody = '';
	$sPostBody .= '<sbpi-provider>';
	$sPostBody .= '<plugin>' . $sGUID . '</plugin>';
	$sPostBody .= '</sbpi-provider>';
	$aDataPath = ['sbpiconfiguration','sbpiprovider'];
	$aSbpiprovider = GetPostResults($sPCS_URL.'/config/getsbpiprovider/', $sPostBody, $sError, $aDataPath);
	$sHeaderText = 'Edit Data Provider';
	$bEditMode = '1';
	$sPortsInUse = '';
	GetProviderTypeName($aSbpiprovider, $aSupportedProviders);
	$sGroupList = SafeGetArrayItem1Dim($aSbpiprovider, 'grouplist');
	$aCustomGroupList = str_getcsv($sGroupList);
	array_shift($aCustomGroupList);
	$sDLLList = SafeGetArrayItem1Dim($aSbpiprovider, 'csvofcustomdlls');
	$aDLLList = str_getcsv($sDLLList);
	}
	else
	{
	$sHeaderText = 'Add Data Provider';
	$aSbpiprovider = [];
	$aSbpibindings = [];
	$bEditMode = '0';
	$aSbpiprovider['server'] = '127.0.0.1';
	$aSbpiprovider['port'] = '8081';
	$aSbpiprovider['path'] = 'C:\Program Files (x86)\Sparx Systems\Pro Cloud Server\SBPI\easbpi.exe';
	$aSbpiprovider['config'] = '';
	$aSbpiprovider['rport'] = '80';
	$aSbpiprovider['postdiscuss'] = 'true';
	$aSbpiprovider['logfilecnt'] = '3';
	$aSbpiprovider['logfilesize'] = '1048576';
	$aSbpiprovider['loglevel'] = 'WARNING';
	$aSbpiprovider['logdir'] = 'C:\\Program Files (x86)\\Sparx Systems\\Pro Cloud Server\\SBPI\Logs\\';
	$iDefaultPort = 8081;
	$aDataPath = ['sbpiconfiguration', 'sbpiproviders', 'sbpiprovider'];
	$aSBPIProviders = GetPostResults($sPCS_URL.'/config/getsbpi/','', $sError, $aDataPath, true);
	$aProviderPorts = [];
	if(!empty($aSBPIProviders))
	{
	$sPortsInUse = '(In Use: ';
	}
	else
	{
	$sPortsInUse = '';
	}
	$numItems = count($aSBPIProviders);
	$i=0;
	$aPortsInUse = [];
	foreach ($aSBPIProviders as $aSBPIProvider)
	{
	$i++;
	$aProviderPorts[] = $aSBPIProvider['port'];
	$sPortsInUse .= $aSBPIProvider['port'];
	$aPortsInUse[]= $aSBPIProvider['port'];
	if ($i < $numItems)
	$sPortsInUse .= ', ';
	else
	$sPortsInUse .= ')';
	}
	foreach ($aPortsInUse as $sPortInUse)
	{
	if (in_array(strval($iDefaultPort),$aPortsInUse))
	$iDefaultPort++;
	}
	$aSbpiprovider['port'] = $iDefaultPort;
	}
	$aProtocols = ['http','https'];
	$sCustomGrpVal = '';
	$sCustomGrp = SafeGetArrayItem1Dim($aSbpiprovider, 'group');
	if(!strIsEmpty($sCustomGrp))
	{
	$sCustomGrpVal = 'value="'.$sCustomGrp .'"';
	}
	$sCustomDLLVal = '';
	$sCustomDLL = SafeGetArrayItem1Dim($aSbpiprovider, 'customdllpath');
	if(!strIsEmpty($sCustomDLL))
	{
	$sCustomDLLVal = 'value="'.$sCustomDLL .'"';
	}
	WriteBreadcrumb($sHeaderText, 'model_repository/webconfig_add_or_edit_data_provider.html');
	echo '<form id="config-save-sbpi-provider-form" role="form" onsubmit="onFormSubmit(event, \'#config-save-sbpi-provider-form\', \'savesbpiprovider\')">';
	WriteHeading('Data Provider');
	echo '<div class="config-section">';
	echo '<div class="config-line auto-start-line">';
	WriteCheckbox('Enabled','','','name="enabled" title="Enable or Disable this Integration."',SafeGetArrayItem1Dim($aSbpiprovider, 'enabled'), 'true');
	if ((!strIsTrue(SafeGetArrayItem1Dim($aSbpiprovider, 'enabledbylicense')))	&&
	$sAction === 'edit')
	{
	echo '<img alt="" class="" style="vertical-align: top; margin-left:8px;" title="Provider type \''.SafeGetArrayItem1Dim($aSbpiprovider, 'typename').'\' is currently disabled. To enable this please adjust your license\'s Token Allocations." src="images/alert.png">';
	}
	echo '</div>';
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'guid'),'','textfield-small','hidden name="guid"');
	WriteTextField($bEditMode,'','textfield-small','hidden name="editmode"');
	echo '<div class="config-line">';
	WriteLabel('Name<span class="field-label-required">&nbsp;*</span>');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'name'),'','','name="name" title="A friendly name that describes the provider, this value will show within Enterprise Architect" required');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Provider<span class="field-label-required">&nbsp;*</span>');
	if ($sAction === 'edit')
	{
	WriteValue(SafeGetArrayItem1Dim($aSbpiprovider, 'typename'),'','textvalue-medium','name="name" title="The type of external provider (read-only when editing an existing provider)"');
	WriteDropdown($aSupportedProviders,'config-integration-provider','','onchange="onSelectProvider(this)" title="The type of external provider" name="typekey" hidden required',SafeGetArrayItem1Dim($aSbpiprovider, 'typename'),true);
	}
	else
	{
	WriteDropdown($aSupportedProviders,'config-integration-provider','','onchange="onSelectProvider(this)" title="The type of external provider" name="typekey" required',SafeGetArrayItem1Dim($aSbpiprovider, 'typekey'),true);
	}
	echo '</div>';
	echo '<div class="config-line supports-group">';
	WriteLabel('Group<span class="field-label-required">&nbsp;*</span>');
	WriteCombo('id="config-integration-custom-group" name="group" class="textfield-medium supports-group" '.$sCustomGrpVal, 'custom-group-list', $aCustomGroupList);
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Prefix<span class="field-label-required">&nbsp;*</span>');
	if ($sAction === 'edit')
	{
	WriteValue(SafeGetArrayItem1Dim($aSbpiprovider, 'prefix'),'','textvalue-large','name="prefix" title="A short unique value for the provider. This value will be added to each external link within Enterprise Architect (read-only when editing an existing provider)"');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'prefix'),'','','hidden name="prefix" title="A short unique value for the provider. This value will be added to each external link within Enterprise Architect" required');
	}
	else
	{
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'prefix'),'','','name="prefix" title="A short unique value for the provider. This value will be added to each external link within Enterprise Architect" required');
	}
	echo '</div>';
	echo '<div class="config-line auto-start-line">';
	WriteCheckbox('Auto Start','','','name="autostart" title="Automatically start the plugin when starting the Pro Cloud Service."', SafeGetArrayItem1Dim($aSbpiprovider, 'autostart', 't'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Port<span class="field-label-required">&nbsp;*</span>');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'port'),'','textfield-small','name="port" type="number" required title="The port the Integration Plugin is configured to listen to."');
	WriteLabel($sPortsInUse,'','', 'style="width:600px"');
	echo '</div>';
	echo '<div class="config-line supports-custom-dll" display:"none">';
	WriteLabel('DLL Path<span class="field-label-required">&nbsp;*</span>');
	WriteCombo('id="config-integration-customdllpath" name="customdllpath" class="textfield-large supports-custom-dll" '.$sCustomDLLVal, 'custom-dll-list', $aDLLList);
	echo '</div>';
	echo '</div>';
	$sTypeKey = SafeGetArrayItem1Dim($aSbpiprovider, 'typekey');
	$aCustomProps = [];
	$sCustomProps = html_entity_decode(SafeGetArrayItem1Dim($aSbpiprovider, 'customproperties'));
	if (!strIsEmpty($sCustomProps))
	{
	$aJSON = json_decode($sCustomProps);
	if ($aJSON !== null)
	{
	$iR = 0;
	foreach($aJSON as $key=>$data )
	{
	$aCustomProps[] =
	['status_icon' 	=> '',
	 'item' 	=> $key,
	 'value' 	=> $data,
	 'actions'	=> '<img alt="" class="config-icon customprop-actionbtn" title="Edit Custom Property" onclick="onClickButton(\'editsbpiprovidercustomprop\',\'' . ConvertStringToParameter($iR) . '\')" src="images/edit.png">' .
	   '<img alt="" class="config-icon customprop-actionbtn" title="Delete Custom Property" onclick="onClickButton(\'deletesbpiprovidercustomprop\',\'' . ConvertStringToParameter($iR) . '\')" src="images/delete.png">'
	];
	$iR = $iR+1;
	}
	}
	WriteTextField(htmlentities($sCustomProps),'','textfield-small','hidden id="customproperties" name="customproperties"');
	}
	else
	{
	WriteTextField('{}','','textfield-small','hidden id="customproperties" name="customproperties"');
	}
	$aHeader = [
	'<div></div>',
	'<div title="The custom property item name">Item</div>',
	'<div title="The custom property item value">Value</div>',
	'Actions'
	];
	$aFields = ['status_icon','item', 'value','actions'];
	$sTableAttr = 'id="custom-props-table" class="config-table"';
	$aCustomProps[] = ['status_icon' => '<img alt="" class="config-add-icon" src="images/add.png">', 'item' => '<a class="w3-link" onclick="onClickButton(\'addsbpiprovidercustomprop\',\'\') ">&lt;Add a Custom Property&gt;</a>'];
	echo '<div>&nbsp;</div>';
	echo '<div class="config-line supports-custom-props" display:"none">';
	WriteHeading('Custom Properties');
	WriteTable($sTableAttr, $aHeader, $aCustomProps, $aFields);
	echo '</div>';
	echo '<div class="provider-section">';
	WriteHeading('Provider Server');
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Provider URL<span class="field-label-required">&nbsp;*</span>');
	$aDropdown = ['http','https'];
	WriteDropdown($aDropdown,'config-sbpi-prov-protocol','config-dropdown prov-url-field',' name="rprotocol" onchange="onSelectProviderProtocol(this)" title="The transfer protocol that the Provider Server communicates on."', SafeGetArrayItem1Dim($aSbpiprovider, 'rprotocol'));
	$sServer = SafeGetArrayItem1Dim($aSbpiprovider, 'rserver');
	WriteTextField($sServer,'config-sbpi-prov-server','textfield-medium prov-url-field','required name="rserver" placeholder="Server" title="The server name (or IP) that hosts the Provider\'s system."');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'rport'),'config-sbpi-prov-port','textfield-small prov-url-field config-sbpiserver-port-legacy','name="rport" type="number" min="0" max="65535" title="The port the Provider\'s system is configured to listen to."');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'rbaseurl'),'config-sbpi-prov-folder','textfield-small prov-url-field','name="rbaseurl" placeholder="URL Folder" title="Some Data Providers include the capability of accessing multiple repositories on a given server, this setting targets an individual repository."');
	echo '</div>';
	echo '<div class="config-line config-sbpi-prov-ignoressl">';
	WriteCheckbox('Ignore SSL Errors','','','name="ignoressl" class="config-sbpi-prov-ignoressl" title="Indicates if Enterprise Architect clients should ignore all SSL errors while communicating to the Integration Server."', SafeGetArrayItem1Dim($aSbpiprovider, 'ignoressl'), false);
	echo '</div>';
	echo '<div class="config-line">';
	echo '<div id="edit-provider-url" title="The URL which will be used by the integration plugin (based on the fields entered above)">http://theserver:804/thebase</div>';
	echo '</div>';
	echo '<div class="config-line-padding"></div>';
	echo '<div id="config-integration-username" class="config-line">';
	WriteLabel('User Name');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'user'),'','','name="user" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title="The user name that will be used to access the Provider system. If this value is left empty the end user will be prompted for their Provider credentials."');
	echo '</div>';
	echo '<div id="config-integration-password" class="config-line">';
	WriteLabel('Password');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'pwd'),'','','name="pwd" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" type="password" title="The matching password that will be be used to access the Provider system."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Maximum Request Time (sec)');
	$aDropdown = ['60', '120', '180', '300'];
	WriteDropdown($aDropdown,'config-sbpi-timeout','config-dropdown timeout-field',' name="timeout" title="The maximum amount of time (in seconds) each call to the provider will wait before timing out."', SafeGetArrayItem1Dim($aSbpiprovider, 'timeout'));
	echo '</div>';
	echo '<div class="config-line-padding"></div>';
	echo '<div class="config-line">';
	WriteCheckbox('Create Items','','','name="createitems" title="A flag to indicate if Enterprise Architect users are allowed to create items in the Provider system."',SafeGetArrayItem1Dim($aSbpiprovider, 'createitems'), true);
	echo '&nbsp&nbsp&nbsp&nbsp';
	WriteCheckbox('Modify Items','','','name="modifyitems" title="A flag to indicate if Enterprise Architect users are allowed to modify items in the Provider system."',SafeGetArrayItem1Dim($aSbpiprovider, 'modifyitems'), true);
	echo '&nbsp&nbsp&nbsp&nbsp';
	WriteCheckbox('Post Discussions','','','name="postdiscuss" title="A flag to indicate if Enterprise Architect users are allowed to create discussions on items within the Provider system."',SafeGetArrayItem1Dim($aSbpiprovider, 'postdiscuss'), true);
	echo '</div>';
	echo '</div>';
	echo '</div>';
	WriteCollapsibleHeading('Logging');
	echo '<div class="config-section" style="display:none">';
	echo '<div class="config-line">';
	WriteLabel('File Count');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'logfilecnt'),'','textfield-small','name="logfilecnt" title="Defines the maximum number of log files that will be retained."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Max File Size (bytes)');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'logfilesize'),'','textfield-small','name="logfilesize" title="Defines the number of bytes a Provider\'s log files can be before a new file will be started."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Level');
	$aLogLevels = ['','OFF','FATAL','WARNING','INFO','SYSTEM','DEBUG','TRACE'];
	WriteDropdown($aLogLevels,'','','name="loglevel" title="Defines the level of logging for the given Provider. Each subsequent level is inclusive of all higher levels."',SafeGetArrayItem1Dim($aSbpiprovider, 'loglevel'));
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Log Directory');
	WriteValue(SafeGetArrayItem1Dim($aSbpiprovider, 'logdir'),'','textvalue-large','name="logdir" title="The physical location of all log files for the given Provider (read-only)."');
	echo '</div>';
	echo '</div>';
	WriteCollapsibleHeading('Proxy');
	echo '<div class="config-section" style="display:none">';
	echo '<div class="config-line">';
	WriteLabel('Server Name/IP');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'proxyserver'),'','textfield-large','name="proxyserver" title="The URL of the proxy server, including the port. ie 123.123.123.123:80"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Bypass');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'proxybypass'),'','textfield-large','name="proxybypass" title="URLs that should not be sent to the proxy."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('User Name');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'proxyuser'),'','','name="proxyuser" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title="The user authorized on the proxy server."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Password');
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'proxypwd'),'','','name="proxypwd" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" type="password" title="The password of the authorized user on the proxy server."');
	echo '</div>';
	echo '</div>';
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	echo '<div class="config-required-field-message">* Required Fields</div>';
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button"  onclick="loadConfigPage(\'integrations.php\')"');
	echo '</form>';
?>
<script>
	var bIsDirty = false;
	$(document).ready(function() {
	$('input, select').change(function() {
	bIsDirty = true;
	});
	$('.prov-url-field').keyup(function() {
	refreshURL();
	});
	$('.prov-url-field').change(function() {
	refreshURL();
	});
	refreshURL();
	});
	var sbpiJSON = $("#sbpi-json").html();
	var sbpiObj = JSON.parse(sbpiJSON);
	selection = $("[name=typekey]");
	if($("[name=guid]").val() == "")
	{
	onSelectProvider(selection, false, sbpiObj);
	}
	else
	{
	onSelectProvider(selection, true, sbpiObj);
	}
	selectedProtocol = $("[name=rprotocol]");
	onSelectProviderProtocol(selectedProtocol);
	function refreshURL()
	{
	var url = '';
	url += $("[name=rprotocol]").val();
	url += '://';
	url += $("[name=rserver]").val();
	if($("[name=rport]").val() !== '')
	{
	url += ':';
	url += $("[name=rport]").val();
	}
	if($("[name=rbaseurl]").val() !== '')
	{
	url += '/';
	url += $("[name=rbaseurl]").val();
	}
	if ($("[name=rserver]").val() !== '')
	{
	$("#edit-provider-url").html(url);
	}
	else
	{
	$("#edit-provider-url").html('');
	}
	}
</script>