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
	$sAction = SafeGetInternalArrayParameter($_POST, 'action');
	$xmlDoc = '';
	$sPostError = '';
	if ($sAction === 'login')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/login/';
	$sPWD = GetArrayParameterAsXML($_POST, 'pwd');
	$sPWDEnc = EncryptDecrypt($sPWD, true);
	$sPostBody = '';
	$sPostBody .= '<login>';
	$sPostBody .= '<uid>'.GetArrayParameterAsXML($_POST, 'uid').'</uid>';
	$sPostBody .= '<pwd>'.$sPWDEnc.'</pwd>';
	$sPostBody .= '</login>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	if(PostSuccessful($xmlDoc))
	{
	ReplaceSuccessMessage($xmlDoc, 'Login Successful');
	$_SESSION['pcsa'] = $sPWDEnc;
	}
	}
	else if (($sAction === 'saveserverinfo') || ($sAction === 'loadchangepwd') || ($sAction === 'loadpcslicense'))
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/saveserverinfo/';
	$sPostBody = '<server-settings>';
	$sPostBody .= '<logging-level>'.GetArrayParameterAsXML($_POST, 'logging-level').'</logging-level>';
	$sPostBody .= '<default-max-sim-queries>'. GetArrayParameterAsXML($_POST, 'default-max-sim-queries') .'</default-max-sim-queries>';
	$sPostBody .= '<admin-white-list>'. GetArrayParameterAsXML($_POST, 'admin-white-list') .'</admin-white-list>';
	$sPostBody .= '</server-settings>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Server Settings');
	}
	else if ($sAction === 'saveserverpwd')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/saveserverinfo/';
	$pwd1 = GetArrayParameterAsXML($_POST, 'pwd1');
	$pwd2 = GetArrayParameterAsXML($_POST, 'pwd2');
	if ($pwd1 === $pwd2)
	{
	$sPWDEnc = EncryptDecrypt($pwd1, true);
	$sPostBody = '<server-settings>';
	$sPostBody .= '<pcsa>' . $sPWDEnc . '</pcsa>';
	$sPostBody .= '</server-settings>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Changed Password');
	}
	else
	{
	$xmlDoc = ReturnXMLError('Both Password fields do not match. Password was not changed');
	}
	}
	else if ($sAction === 'savepcslicense')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/savepcslicense/';
	$sPostBody = '';
	$sPostBody .= '<pcs-license>';
	$sPostBody .= '<license>' . SafeGetInternalArrayParameter($_POST, 'pcskey') . '</license>';
	$sPostBody .= '</pcs-license>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Added License');
	}
	else if ($sAction === 'deletepcslicense')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/deletepcslicense/';
	$sPostBody = '';
	$sPostBody .= '<pcs-license>';
	$sPostBody .= '<license>' . SafeGetInternalArrayParameter($_POST, 'data') . '</license>';
	$sPostBody .= '</pcs-license>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Deleted License');
	}
	else if ($sAction === 'deletelicensecert')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/deletelicensecert/';
	$sPostBody = '';
	$sPostBody .= '<license-cert-sig>' . SafeGetInternalArrayParameter($_POST, 'data') . '</license-cert-sig>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Deleted License');
	}
	else if (($sAction === 'addport') || ($sAction === 'saveport'))
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/'.$sAction.'/';
	$bModelAuth = '0';
	$bGloablAuth = '';
	if (SafeGetInternalArrayParameter($_POST, 'authtype') === 'Model')
	{
	$bModelAuth = '1';
	}
	else if (SafeGetInternalArrayParameter($_POST, 'authtype') === 'Global')
	{
	$bGloablAuth = SafeGetInternalArrayParameter($_POST, 'globalauth');
	}
	if(GetArrayParameterAsXML($_POST, 'protocol') === 'https')
	$sProtocol = '1';
	else
	$sProtocol = '0';
	$sPostBody = '<port-details>';
	$sPostBody .= '<row>';
	$sPostBody .= '<port>'.GetArrayParameterAsXML($_POST, 'port').'</port>';
	$sPostBody .= '<requiresssl>'.$sProtocol.'</requiresssl>';
	$sPostBody .= '<oslc>'.GetArrayParameterAsXML($_POST, 'oslc').'</oslc>';
	$sPostBody .= '<minibuild></minibuild>';
	$sPostBody .= '<maxbuild></maxbuild>';
	$sPostBody .= '<getpage></getpage>';
	if ($sProtocol === '1')
	{
	$sPostBody .= '<tlsv1-3>'.GetArrayParameterAsXML($_POST, 'tlsv1-3').'</tlsv1-3>';
	$sPostBody .= '<tlsv1-2>'.GetArrayParameterAsXML($_POST, 'tlsv1-2').'</tlsv1-2>';
	$sPostBody .= '<tlsv1-1>'.GetArrayParameterAsXML($_POST, 'tlsv1-1').'</tlsv1-1>';
	$sPostBody .= '<tlsv1-0>'.GetArrayParameterAsXML($_POST, 'tlsv1-0').'</tlsv1-0>';
	$sPostBody .= '<sslv3>'.GetArrayParameterAsXML($_POST, 'sslv3').'</sslv3>';
	}
	$sPostBody .= '<modelauth>'.$bModelAuth.'</modelauth>';
	$sPostBody .= '<globalauth>'.$bGloablAuth.'</globalauth>';
	$sPostBody .= '</row>';
	$sPostBody .= '</port-details>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Port');
	}
	else if ($sAction === 'deleteport')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/deleteport/';
	$sPostBody = '';
	$sPostBody .= '<port-details>';
	$sPostBody .= '<port>' . SafeGetInternalArrayParameter($_POST, 'data') . '</port>';
	$sPostBody .= '</port-details>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Deleted Port');
	}
	else if ($sAction === 'addmodelconnection')
	{
	$sConnectionString = GetArrayParameterAsXML($_POST, 'connection-string');
	$sDBType = GetArrayParameterAsXML($_POST, 'db-type');
	$sDriver = '';
	if ($sDBType === 'FIREBIRD')
	{
	$ext = pathinfo($sConnectionString, PATHINFO_EXTENSION);
	$ext = mb_strtolower($ext);
	if ($sConnectionString !== '')
	{
	if (($sDBType === 'FIREBIRD') && (($ext !== 'feap') && ($ext !== 'fdb')))
	{
	$sConnectionString .= '.feap';
	}
	if (!strIsEmpty($ext) &&
	(($ext !== 'feap') && ($ext !== 'fdb')))
	{
	$bInvalidExtension=true;
	}
	else
	{
	$bInvalidExtension=false;
	}
	}
	}
	else
	{
	$sAlias = GetArrayParameterAsXML($_POST, 'dbms-alias');
	$sDriver = GetArrayParameterAsXML($_POST, 'config-connection-driver');
	$sServer = GetArrayParameterAsXML($_POST, 'server');
	$sUser = GetArrayParameterAsXML($_POST, 'user');
	$sPassword = GetArrayParameterAsXML($_POST, 'password');
	$sDatabase = GetArrayParameterAsXML($_POST, 'database');
	$sPort = GetArrayParameterAsXML($_POST, 'port');
	if (($sDBType === 'sqlserver1') || ($sDBType === 'sqlserver2'))
	{
	$sDBType = 'SQLSVR';
	$sConnectionString = 'Provider='.$sDriver.';Password='.$sPassword.';Persist Security Info=True;User ID='.$sUser.';Initial Catalog='.$sDatabase.';Data Source='.$sServer.';DMAlias='.$sAlias.';';
	}
	else if ($sDBType === 'mysql')
	{
	$sDBType = 'MYSQL';
	$sConnectionString = 'Provider=MSDASQL.1;DRIVER={'.$sDriver.'};SERVER='.$sServer.';DATABASE='.$sDatabase.';UID='.$sUser.';PWD='.$sPassword.';DMAlias='.$sAlias.';';
	}
	else if ($sDBType === 'postgresql')
	{
	$sDBType = 'POSTGRES';
	$sConnectionString = 'Provider=MSDASQL.1;DRIVER={'.$sDriver.'};SERVER='.$sServer.';DATABASE='.$sDatabase.';UID='.$sUser.';PWD='.$sPassword.';DMAlias='.$sAlias.';SSLmode=disable;XaOpt=1;GssAuthUseGSS=0;LowerCaseIdentifier=0;UseServerSidePrepare=0;ByteaAsLongVarBinary=1;BI=0;TrueIsMinus1=0;DisallowPremature=1;UpdatableCursors=1;LFConversion=1;ExtraSysTablePrefixes=dd_;CancelAsFreeStmt=0;Parse=0;BoolsAsChar=0;UnknownsAsLongVarchar=1;TextAsLongVarchar=1;UseDeclareFetch=1;Ksqo=1;Optimizer=0;CommLog=0;Debug=0;MxLongVarcharSize=1000000;MaxVarcharSize=1024;UnknownSizes=0;Socket=4096;Fetch=100;ConnSettings=;ShowSystemTables=0;RowVersioning=0;ShowOidColumn=0;FakeOidIndex=0;Protool=7.4-1;ReadOnly=0;';
	}
	else if ($sDBType === 'oracle-odbc')
	{
	$sDBType = 'ORACLE';
	$sConnectionString = 'Provider=MSDASQL.1;DRIVER={'.$sDriver.'};SERVER='.$sServer.';DBQ='.$sServer.';UID='.$sDatabase.';PWD='.$sPassword.';DMAlias='.$sAlias.';ODA=F;MLD=0;TLO=O;FBS=60000;FWC=F;CSR=F;MDI=Me;MTS=T;DPM=F;NUM=NLS;BAM=IfAllSuccessful;BNF=F;BTD=F;RST=T;LOB=T;FDL=10;FRC=10;QTO=T;FEN=T;XSM=Default;EXC=F;APA=T;DBA=W;';
	}
	else if ($sDBType === 'oracle-oledb')
	{
	$sDBType = 'ORACLE';
	$sConnectionString = 'Provider='.$sDriver.';Password='.$sPassword.';Persist Security Info=True;User ID='.$sUser.';Data Source='.$sDatabase.';DMAlias='.$sAlias.';';
	}
	}
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/addmodelconnection/';
	$sPostBody = '<model-connection>';
	$sPostBody .= '<db-type>'.$sDBType.'</db-type>';
	$sPostBody .= '<connection-string>'.EncryptDecrypt($sConnectionString, true).'</connection-string>';
	$sPostBody .= '</model-connection>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Added Model Connection');
	}
	else if ($sAction === 'deletemodelconnection')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/deletemodelconnection/';
	$sPostBody = '';
	$sPostBody .= '<model-connection>';
	$sPostBody .= '<key>' . SafeGetInternalArrayParameter($_POST, 'data') . '</key>';
	$sPostBody .= '</model-connection>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Deleted Model Connection');
	}
	else if ($sAction === 'savemodelconnection')
	{
	$sPortProtocol = GetArrayParameterAsXML($_POST, 'worker-connection-port-protocol');
	$sWorkerPort = '';
	$sWorkerProtocol = '';
	if(!strIsEmpty($sPortProtocol))
	{
	$sPortProtocol = rtrim($sPortProtocol,")");
	$aPortProtocol = explode(" (", $sPortProtocol);
	$sWorkerPort = $aPortProtocol[0];
	$sWorkerProtocol = $aPortProtocol[1];
	}
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/savemodelconnection/';
	$sPostBody = '';
	$sPostBody .= '<model-connection>';
	$sPostBody .= '<key>'. GetArrayParameterAsXML($_POST, 'key').'</key>';
	$sPostBody .= '<accept-queries>'. GetArrayParameterAsXML($_POST, 'accept-queries') .'</accept-queries>';
	$sPostBody .= '<max-sim-queries>'. GetArrayParameterAsXML($_POST, 'max-sim-queries') .'</max-sim-queries>';
	$sPostBody .= '<alias>'. GetArrayParameterAsXML($_POST, 'alias') .'</alias>';
	$sPostBody .= '<read-only>'. GetArrayParameterAsXML($_POST, 'read-only') .'</read-only>';
	$sPostBody .= '<secure-only>'. GetArrayParameterAsXML($_POST, 'secure-only') .'</secure-only>';
	$sPostBody .= '<run-scheduled-tasks>'. GetArrayParameterAsXML($_POST, 'run-scheduled-tasks') .'</run-scheduled-tasks>';
	$sPostBody .= '<chartgen-interval>'. GetArrayParameterAsXML($_POST, 'chartgen-interval') .'</chartgen-interval>';
	$sPostBody .= '<chartgen-retries>'. GetArrayParameterAsXML($_POST, 'chartgen-retries') .'</chartgen-retries>';
	$sPostBody .= '<chartgen-start>'. GetArrayParameterAsXML($_POST, 'chartgen-start') .'</chartgen-start>';
	$sPostBody .= '<pro-features>'. GetArrayParameterAsXML($_POST, 'pro-features') .'</pro-features>';
	$sPostBody .= '<access-code>'. GetArrayParameterAsXML($_POST, 'access-code') .'</access-code>';
	$sPostBody .= '<min-ea-build>'. GetArrayParameterAsXML($_POST, 'min-ea-build') .'</min-ea-build>';
	$sPostBody .= '<is-worker-enabled>'. GetArrayParameterAsXML($_POST, 'is-worker-enabled') .'</is-worker-enabled>';
	$sPostBody .= '<worker-update-period>'. GetArrayParameterAsXML($_POST, 'worker-update-period') .'</worker-update-period>';
	$sPostBody .= '<worker-connection-port>'. $sWorkerPort .'</worker-connection-port>';
	$sPostBody .= '<worker-connection-protocol>'. $sWorkerProtocol .'</worker-connection-protocol>';
	$sPostBody .= '<worker-connection-user>'. GetArrayParameterAsXML($_POST, 'worker-connection-user') .'</worker-connection-user>';
	$sPostBody .= '<worker-connection-pwd>'. GetArrayParameterAsXML($_POST, 'worker-connection-pwd') .'</worker-connection-pwd>';
	$sPostBody .= '<worker-logging-level>'. GetArrayParameterAsXML($_POST, 'worker-logging-level') .'</worker-logging-level>';
	$sPostBody .= '<min-internal-build></min-internal-build>';
	$sPostBody .= '<host-address></host-address>';
	$sPostBody .= '</model-connection>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Model Connection');
	}
	else if ($sAction === 'savesbpiprovider')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/savesbpiprovider/';
	$sPostBody = '';
	$sPostBody .= '<sbpi-provider-details>';
	$sPostBody .= '<id>'. GetArrayParameterAsXML($_POST, 'guid') .'</id>';
	$sPostBody .= '<sbpiprovider>';
	$sPostBody .= '<enabled>'. GetArrayParameterAsXML($_POST, 'enabled') .'</enabled>';
	$sPostBody .= '<autostart>'. GetArrayParameterAsXML($_POST, 'autostart') .'</autostart>';
	$sPostBody .= '<editmode>'. GetArrayParameterAsXML($_POST, 'editmode') .'</editmode>';
	$sPostBody .= '<name>'. GetArrayParameterAsXML($_POST, 'name') .'</name>';
	$sPostBody .= '<type>'. GetArrayParameterAsXML($_POST, 'type') .'</type>';
	$sPostBody .= '<group>'. GetArrayParameterAsXML($_POST, 'group') .'</group>';
	$sPostBody .= '<typekey>'. GetArrayParameterAsXML($_POST, 'typekey') .'</typekey>';
	$sPostBody .= '<prefix>'. GetArrayParameterAsXML($_POST, 'prefix') .'</prefix>';
	$sPostBody .= '<protocol>'. GetArrayParameterAsXML($_POST, 'protocol') .'</protocol>';
	$sPostBody .= '<server>'. GetArrayParameterAsXML($_POST, 'server') .'</server>';
	$sPostBody .= '<port>'. GetArrayParameterAsXML($_POST, 'port') .'</port>';
	$sPostBody .= '<path>'. GetArrayParameterAsXML($_POST, 'path') .'</path>';
	$sPostBody .= '<customdllpath>'. GetArrayParameterAsXML($_POST, 'customdllpath') .'</customdllpath>';
	$sPostBody .= '<customproperties>'. GetArrayParameterAsXML($_POST, 'customproperties') .'</customproperties>';
	$sPostBody .= '<plugignoressl>'. GetArrayParameterAsXML($_POST, 'plugignoressl') .'</plugignoressl>';
	$sPostBody .= '<rprotocol>'. GetArrayParameterAsXML($_POST, 'rprotocol') .'</rprotocol>';
	$sPostBody .= '<rserver>'. GetArrayParameterAsXML($_POST, 'rserver') .'</rserver>';
	$sPostBody .= '<rport>'. GetArrayParameterAsXML($_POST, 'rport') .'</rport>';
	$sPostBody .= '<rbaseurl>'. GetArrayParameterAsXML($_POST, 'rbaseurl') .'</rbaseurl>';
	$sPostBody .= '<linkbase>'. GetArrayParameterAsXML($_POST, 'linkbase') .'</linkbase>';
	$sPostBody .= '<user>'. GetArrayParameterAsXML($_POST, 'user') .'</user>';
	$sPostBody .= '<pwd>'. GetArrayParameterAsXML($_POST, 'pwd') .'</pwd>';
	$sPostBody .= '<timeout>'. GetArrayParameterAsXML($_POST, 'timeout') .'</timeout>';
	$sPostBody .= '<createitems>'. GetArrayParameterAsXML($_POST, 'createitems') .'</createitems>';
	$sPostBody .= '<modifyitems>'. GetArrayParameterAsXML($_POST, 'modifyitems') .'</modifyitems>';
	$sPostBody .= '<postdiscuss>'. GetArrayParameterAsXML($_POST, 'postdiscuss') .'</postdiscuss>';
	$sPostBody .= '<ignoressl>'. GetArrayParameterAsXML($_POST, 'ignoressl') .'</ignoressl>';
	$sPostBody .= '<logfilecnt>'. GetArrayParameterAsXML($_POST, 'logfilecnt') .'</logfilecnt>';
	$sPostBody .= '<logfilesize>'. GetArrayParameterAsXML($_POST, 'logfilesize') .'</logfilesize>';
	$sPostBody .= '<loglevel>'. GetArrayParameterAsXML($_POST, 'loglevel') .'</loglevel>';
	$sPostBody .= '<logdir>'. GetArrayParameterAsXML($_POST, 'logdir') .'</logdir>';
	$sPostBody .= '<proxyserver>'. GetArrayParameterAsXML($_POST, 'proxyserver') .'</proxyserver>';
	$sPostBody .= '<proxybypass>'. GetArrayParameterAsXML($_POST, 'proxybypass') .'</proxybypass>';
	$sPostBody .= '<proxyuser>'. GetArrayParameterAsXML($_POST, 'proxyuser') .'</proxyuser>';
	$sPostBody .= '<proxypwd>'. GetArrayParameterAsXML($_POST, 'proxypwd') .'</proxypwd>';
	$sPostBody .= '</sbpiprovider>';
	$sPostBody .= '</sbpi-provider-details>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Integration Provider');
	}
	else if ($sAction === 'deletesbpiprovider')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/deletesbpiprovider/';
	$sPostBody = '';
	$sPostBody .= '<sbpi-provider>';
	$sPostBody .= '<plugin>' . SafeGetInternalArrayParameter($_POST, 'data') . '</plugin>';
	$sPostBody .= '</sbpi-provider>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Deleted Provider');
	}
	else if ($sAction === 'savesbpibindings')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/savesbpibinding/';
	$aNewBindings = [];
	foreach ($_POST as $key => $value)
	{
	if (substr($key,0,8) === 'BINDING_')
	$aNewBindings[] = $value;
	}
	$sGUID = SafeGetInternalArrayParameter($_POST, 'guid');
	$aPreviousBindings = [];
	$sPostBody = '';
	$sPostBody .= '<sbpi-bindings>';
	$sPostBody .= '<plugin>' . $sGUID . '</plugin>';
	$sPostBody .= '</sbpi-bindings>';
	$aDataPath = ['sbpiconfiguration','sbpibindings','sbpibinding'];
	$aPreviousBindings = GetBindings($sPCS_URL, $sPostBody, $sError,  $aDataPath);
	$aPreviousBindingsList = [];
	if (is_array($aPreviousBindings))
	{
	foreach ($aPreviousBindings as $aPreviousBinding)
	{
	if (in_array($aPreviousBinding['dbalias'], $aNewBindings))
	{
	}
	else
	{
	$sPostBody = '';
	$sPostBody .= '<sbpi-binding-details>';
	$sPostBody .= '<sbpibinding>';
	$sPostBody .= '<bindingid>'.GetArrayParameterAsXML($aPreviousBinding, 'bindingid').'</bindingid>';
	$sPostBody .= '<dbalias>'.GetArrayParameterAsXML($aPreviousBinding, 'dbalias').'</dbalias>';
	$sPostBody .= '<pluginid>'.$sGUID.'</pluginid>';
	$sPostBody .= '</sbpibinding>';
	$sPostBody .= '</sbpi-binding-details>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Bindings');
	}
	$aPreviousBindingsList[] = SafeGetInternalArrayParameter($aPreviousBinding, 'dbalias');
	}
	}
	foreach ($aNewBindings as $sNewBinding)
	{
	if (!in_array($sNewBinding, $aPreviousBindingsList))
	{
	$sPostBody = '';
	$sPostBody .= '<sbpi-binding-details>';
	$sPostBody .= '<sbpibinding>';
	$sPostBody .= '<bindingid></bindingid>';
	$sPostBody .= '<dbalias>'.$sNewBinding.'</dbalias>';
	$sPostBody .= '<pluginid>'.$sGUID.'</pluginid>';
	$sPostBody .= '</sbpibinding>';
	$sPostBody .= '</sbpi-binding-details>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Bindings');
	}
	}
	}
	else if ($sAction === 'savesbpi')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/savesbpi/';
	$sPostBody = '';
	$sPostBody .= '<sbpi-server-details>';
	$sPostBody .= '<sbpiserver>';
	$sPostBody .= '<enabled>'. GetArrayParameterAsXML($_POST, 'enabled') .'</enabled>';
	$sPostBody .= '<uselegacy>'. GetArrayParameterAsXML($_POST, 'uselegacy') .'</uselegacy>';
	$sPostBody .= '<localport>'. GetArrayParameterAsXML($_POST, 'localport') .'</localport>';
	$sPostBody .= '<path>'. GetArrayParameterAsXML($_POST, 'path') .'</path>';
	$sPostBody .= '<protocol>'. GetArrayParameterAsXML($_POST, 'protocol') .'</protocol>';
	$sPostBody .= '<server>'. GetArrayParameterAsXML($_POST, 'server') .'</server>';
	$sPostBody .= '<port>'. GetArrayParameterAsXML($_POST, 'port') .'</port>';
	$sPostBody .= '<timeout>'. GetArrayParameterAsXML($_POST, 'timeout') .'</timeout>';
	$sPostBody .= '<ignoressl>'. GetArrayParameterAsXML($_POST, 'ignoressl') .'</ignoressl>';
	$sPostBody .= '<useproxy>'. GetArrayParameterAsXML($_POST, 'useproxy') .'</useproxy>';
	$sPostBody .= '<attemptautodiscovery>'. GetArrayParameterAsXML($_POST, 'attemptautodiscovery') .'</attemptautodiscovery>';
	$sPostBody .= '<clientprotocol>'. GetArrayParameterAsXML($_POST, 'clientprotocol') .'</clientprotocol>';
	$sPostBody .= '<clientserver>'. GetArrayParameterAsXML($_POST, 'clientserver') .'</clientserver>';
	$sPostBody .= '<clientport>'. GetArrayParameterAsXML($_POST, 'clientport') .'</clientport>';
	$sPostBody .= '</sbpiserver>';
	$sPostBody .= '</sbpi-server-details>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Integration Settings');
	}
	else if ($sAction === 'saveflsconfig')
	{
	if (GetArrayParameterAsXML($_POST, 'periodtype') === 'Weeks')
	{
	$sDays = GetArrayParameterAsXML($_POST, 'timeoutperiod') * 7;
	}
	else
	{
	$sDays = GetArrayParameterAsXML($_POST, 'timeoutperiod');
	}
	$sPostError = '';
	$sURL = $sPCS_URL . '/config/saveflsconfig/';
	$sPostBody = '';
	$sPostBody .= '<AssignServerProperties>';
	$sPostBody .= '<AutoCheckIn value="'. GetArrayParameterAsXML($_POST, 'autocheckin') .'"/>';
	$sPostBody .= '<TimeoutPeriod  days="'.$sDays .'" hours="0" minutes="0" seconds="0"/>';
	$sPostBody .= '</AssignServerProperties>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Applied Settings');
	}
	else if ($sAction === 'addflskey')
	{
	$sPostError = '';
	$sURL = $sPCS_URL . '/config/addflskey/';
	$aEnteredKeys = preg_split("/\\r\\n|\\r|\\n/", GetArrayParameterAsXML($_POST, 'key'));
	$sPostBody = '';
	$sPostBody .= '<NewKeys>';
	foreach ($aEnteredKeys as $sKey)
	{
	if (!strIsEmpty($sKey))
	$sPostBody .= '<Key key="'.$sKey.'"/>';
	}
	$sPostBody .= '</NewKeys>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	$aData = json_decode(json_encode(simplexml_load_string($xmlDoc)), true);
	$aResults = SafeGetArrayItem1Dim($aData, 'results');
	$aAddedKeys = SafeGetArrayItem1Dim($aResults, 'AddedKeys');
	$aKey =  SafeGetArrayItem1Dim($aAddedKeys, 'Key');
	if (strIsEmpty($aKey))
	{
	$aKey = $aAddedKeys;
	}
	$sKeyResponse = '';
	foreach ($aKey as $aKeyResp)
	{
	$aKeyAtt = SafeGetArrayItem1Dim($aKeyResp, '@attributes');
	$sKey = SafeGetArrayItem1Dim($aKeyAtt, 'key');
	$sError = SafeGetArrayItem1Dim($aKeyAtt, 'error');
	if (strIsEmpty($sError))
	{
	$sError = 'Added Key';
	}
	$sKeyResponse .= $sKey. ' - ' . $sError  . '<br>';
	}
	ReplaceSuccessMessage($xmlDoc, htmlspecialchars($sKeyResponse));
	}
	else if ($sAction === 'deleteflskey')
	{
	$sPostError = '';
	$sURL = $sPCS_URL . '/config/deleteflskey/';
	$sPostBody = '';
	$sPostBody .= '<RemoveKeys>';
	$sPostBody .= '<Key key="'. GetArrayParameterAsXML($_POST, 'data') .'"/>';
	$sPostBody .= '</RemoveKeys>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Deleted Key');
	}
	else if ($sAction === 'checkinflskey')
	{
	$sPostError = '';
	$sURL = $sPCS_URL . '/config/checkinflskey/';
	$sPostBody = '';
	$sPostBody .= '<KeystoreServerCheckinRequest>';
	$sPostBody .= '<ReturnedKeys>';
	$sPostBody .= '<ReturnedKey key="'. GetArrayParameterAsXML($_POST, 'data') .'"/>';
	$sPostBody .= '</ReturnedKeys>';
	$sPostBody .= '</KeystoreServerCheckinRequest>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Checked In Key');
	}
	else if ($sAction === 'logout')
	{
	if (isset($_SESSION['pcsa']))
	{
	$_SESSION = array();
	setcookie(session_name(), '', time()-60, '/' );
	}
	$xmlDoc = '<?xml version="1.0" encoding="UTF-8"?><response><results></results><return><return-code>0</return-code><return-message>Logged Off</return-message><exceptionxml></exceptionxml></return></response>';
	}
	else if ($sAction === 'createlicenserequest')
	{
	$sPostError = '';
	$sURL = $sPCS_URL . '/config/createlicenserequest/';
	$sPostBody = '';
	$sPostBody .= '<license-request>';
	$sPostBody .= '<company>' . GetArrayParameterAsXML($_POST, 'company') . '</company>';
	$sPostBody .= '<email>' . GetArrayParameterAsXML($_POST, 'email') . '</email>';
	$sPostBody .= '<ponumber>' . GetArrayParameterAsXML($_POST, 'ponumber') . '</ponumber>';
	$sPostBody .= '<startdate>' . GetArrayParameterAsXML($_POST, 'startdate') . '</startdate>';
	$sPostBody .= '<comment>' . GetArrayParameterAsXML($_POST, 'comment') . '</comment>';
	$sPostBody .= '<key>' . GetArrayParameterAsXML($_POST, 'key') . '</key>';
	$sPostBody .= '</license-request>';
	$xmlDoc = HTTPPostXMLRaw($sURL, $sPostBody, $sPostError);
	if (PostSuccessful($xmlDoc))
	{
	$xml = simplexml_load_string($xmlDoc);
	$sCert = $xml->results->{'certificate-request'}->__toString();
	$_SESSION['certificate'] = $sCert;
	}
	}
	else if ($sAction === 'exportconfig')
	{
	$sPostError = '';
	$sURL = $sPCS_URL . '/config/getallserversettings/';
	$sPostBody = '';
	$xmlDoc = HTTPPostXMLRaw($sURL, $sPostBody, $sPostError);
	$xml = simplexml_load_string($xmlDoc);
	$sConfig = '';
	foreach ($xml->results->children() as $child) {
	$sConfig .= $child->asXML();
	}
	$sConfig = '<?xml version="1.0" encoding="UTF-8"?><server-config>' . $sConfig . '</server-config>';
	$_SESSION['config_export'] = $sConfig;
	}
	else if ($sAction === 'savelicenseallocs')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/savelicenseallocs/';
	$sSBPIEnabled = GetArrayParameterAsXML($_POST, 'sbpienabled');
	$sWebEAMax = GetArrayParameterAsXML($_POST, 'webeamax');
	$sProlabMax = GetArrayParameterAsXML($_POST, 'prolabmax');
	$sOSLCMax = GetArrayParameterAsXML($_POST, 'oslcmax');
	if ($sWebEAMax === '')
	{
	$sWebEAMax = '-1';
	}
	if ($sProlabMax === '')
	{
	$sProlabMax = '-1';
	}
	if ($sOSLCMax === '')
	{
	$sOSLCMax = '-1';
	}
	$sPostBody = '<license-alloc>';
	$sPostBody .= '<sbpiprovidersmarked>'.GetArrayParameterAsXML($_POST, 'sbpiprovidersmarked').'</sbpiprovidersmarked>';
	$sPostBody .= '<webeaallocated>'.GetArrayParameterAsXML($_POST, 'webeaallocated').'</webeaallocated>';
	$sPostBody .= '<webeamax>'.$sWebEAMax.'</webeamax>';
	$sPostBody .= '<prolaballocated>'.GetArrayParameterAsXML($_POST, 'prolaballocated').'</prolaballocated>';
	$sPostBody .= '<prolabmax>'.$sProlabMax.'</prolabmax>';
	$sPostBody .= '<oslcallocated>'.GetArrayParameterAsXML($_POST, 'oslcallocated').'</oslcallocated>';
	$sPostBody .= '<oslcmax>'.$sOSLCMax.'</oslcmax>';
	$sPostBody .= '</license-alloc>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Saved Token Allocations');
	}
	else if ($sAction === 'addlicensecert')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/addlicensecert/';
	$sPostBody = '';
	$sPostBody .= '<license-cert><![CDATA['.GetArrayParameterAsXML($_POST, 'licensefile').']]></license-cert>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Added License');
	}
	else if ($sAction === 'addsbpiprovidercustomprop')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/addlicensecert/';
	$sPostBody = '';
	$sPostBody .= '<license-cert><![CDATA['.GetArrayParameterAsXML($_POST, 'licensefile').']]></license-cert>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Added License');
	}
	else if (($sAction === 'addflsgroup') || ($sAction === 'saveflsgroup'))
	{
	$sIsManager = SafeGetArrayItem1Dim($_POST, 'is-manager');
	if($sIsManager === '1')
	{
	$sIsManager = 'true';
	}
	else
	{
	$sIsManager = 'false';
	}
	$sGrpPWD = SafeGetArrayItem1Dim($_POST, 'grp-pwd');
	$sGrpPWDEnc = EncryptDecrypt($sGrpPWD, true);
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/'.$sAction.'/';
	$sPostBody = '';
	$sPostBody .= '<?xml version="1.0" encoding="UTF-8"?>';
	$sPostBody .= '<request>';
	if ($sAction === 'saveflsgroup')
	{
	$sPostBody .= '<old-fls-group>';
	$sPostBody .= '<username>'.GetArrayParameterAsXML($_POST, 'old-username').'</username>';
	$sPostBody .= '</old-fls-group>';
	}
	$sPostBody .= '<new-fls-group>';
	$sPostBody .= '<username>'.GetArrayParameterAsXML($_POST, 'username').'</username>';
	$sPostBody .= '<description>'.GetArrayParameterAsXML($_POST, 'description').'</description>';
	$sPostBody .= '<is-manager>' . $sIsManager . '</is-manager>';
	$sPostBody .= '<grp-pwd>' . $sGrpPWDEnc . '</grp-pwd>';
	$sPostBody .= '<start-date>' . GetArrayParameterAsXML($_POST, 'start-date') . '</start-date>';
	$sPostBody .= '<end-date>' . GetArrayParameterAsXML($_POST, 'end-date') . '</end-date>';
	$sPostBody .= '<activation>' . GetArrayParameterAsXML($_POST, 'activation') . '</activation>';
	$sPostBody .= '<ad-groups>' . GetArrayParameterAsXML($_POST, 'ad-groups') . '</ad-groups>';
	$sPostBody .= '<entitlements>';
	$sPostBody .= SafeGetArrayItem1Dim($_POST, 'entitlements');
	$sPostBody .= '</entitlements>';
	$sPostBody .= '</new-fls-group>';
	$sPostBody .= '</request>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	if ($sAction === 'saveflsgroup')
	{
	ReplaceSuccessMessage($xmlDoc, 'Updated Group');
	}
	else
	{
	ReplaceSuccessMessage($xmlDoc, 'Added Group');
	}
	}
	else if ($sAction === 'deleteflsgroup')
	{
	$sPostError = '';
	$sURL = $sPCS_URL.'/config/'.$sAction.'/';
	$sPostBody = '';
	$sPostBody .= '<?xml version="1.0" encoding="UTF-8"?>';
	$sPostBody .= '<request>';
	$sPostBody .= '<old-fls-group>';
	$sPostBody .= '<username>'.GetArrayParameterAsXML($_POST, 'data').'</username>';
	$sPostBody .= '</old-fls-group>';
	$sPostBody .= '</request>';
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	ReplaceSuccessMessage($xmlDoc, 'Deleted Group');
	}
	else
	{
	}
	if (!strIsEmpty($sPostError))
	{
	echo ReturnXMLError($sPostError);
	}
	else
	{
	echo $xmlDoc;
	}
	function ReplaceSuccessMessage(&$xmlDoc, $sNewMessage)
	{
	$xmlDoc = str_replace('<return-message>OK</return-message>', '<return-message>'.$sNewMessage.'</return-message>', $xmlDoc);
	}
	function ReturnXMLError($sErrorText)
	{
	$xmlDoc = '';
	$xmlDoc .= '<?xml version="1.0" encoding="UTF-8"?>';
	$xmlDoc .= '<response>';
	$xmlDoc .= '<results></results>';
	$xmlDoc .= '<return>';
	$xmlDoc .= '<return-code>999</return-code>';
	$xmlDoc .= '<return-message>'.$sErrorText.'</return-message>';
	$xmlDoc .= '<exceptionxml></exceptionxml>';
	$xmlDoc .= '</return>';
	$xmlDoc .= '</response>';
	return $xmlDoc;
	}
	function PostSuccessful($xmlDoc)
	{
	$pos = strpos($xmlDoc, '<return-code>0</return-code>');
	if($pos === false)
	{
	return false;
	}
	else
	{
	return true;
	}
	}
?>