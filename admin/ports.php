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
	$sError = '';
	SafeStartSession();
	$sPCS_URL = SafeGetInternalArrayParameter($_SESSION , 'pcs_url');
	$sPCS_PORT = SafeGetInternalArrayParameter($_SESSION, 'pcs_port');
	$aDataPath = ['ports','row'];
	$aData = GetPostResults($sPCS_URL.'/config/getports/','', $sError, $aDataPath, true);
	$aDataPath = ['server-info'];
	$aServerData = GetPostResults($sPCS_URL.'/config/getserverinfo/','', $sError, $aDataPath);
	$sProEdition = SafeGetArrayItem1Dim($aServerData, 'pro-edition');
	WriteBreadcrumb('Ports', 'model_repository/webconfig_configure_ports.html');
	WriteHeading('Configure Ports');
	foreach ($aData as &$aRow)
	{
	if (empty($aRow['defaultmodel']))
	$aRow['defaultmodel'] = "";
	if ($aRow['requiresssl'] === '1')
	$aRow['requiresssl'] = "https";
	else
	$aRow['requiresssl'] = "http";
	if ($aRow['oslc'] === '1')
	{
	if ($sProEdition === 'Free')
	{
	$aRow['oslc'] = '<div><div class="table-icon-container"><img alt="" class="" style="vertical-align:top;" title="'. 'The OSLC option is enabled however the Pro Cloud Server is currently unlicensed. OSLC / WebEA functionality will not be available for this port.' .'" src="images/alert.png"></div></div>';
	}
	else
	{
	$aRow['oslc'] = '<div><div class="table-icon-container" title="OSLC / WebEA support is enabled"><img alt="" class="tick-icon" src="images/spriteplaceholder.png"></div></div>';
	}
	}
	else
	{
	$aRow['oslc'] = "";
	}
	if ($aRow['modelauth'] === '1')
	$aRow['authentication'] = 'Model';
	elseif ((!empty($aRow['globalauth'])) && ($aRow['modelauth'] === '0'))
	$aRow['authentication'] = 'Global - ' . $aRow['globalauth'];
	else
	$aRow['authentication'] = '';
	$aRow['actions'] = '<img alt="" class="config-icon" src="images/edit.png" title="Edit Port" onclick="loadConfigPage(\'port-add.php\',\'editport\', \''.$aRow['port'].'\')">';
	if($sPCS_PORT === $aRow['port'])
	{
	$aRow['actions'] .= '<img alt="" class="config-icon-disabled" src="images/delete.png" title="This Port cannot be deleted. It is currently in use by the Cloud Configuration Client (as defined in the settings.php file)" src="images/alert.png">';
	}
	else
	{
	$aRow['actions'] .= '<img alt="" class="config-icon" src="images/delete.png" title="Delete Port" onclick="onClickButton(\'deleteport\',\''.$aRow['port'].'\')">';
	}
	}
	usort($aData, "sort_ports");
	function sort_ports($a,$b) {
	  return mb_strtolower($a['port']) <=> mb_strtolower($b['port']);
	}
	$aData[] = ['port' => '<img alt="" class="config-add-icon" src="images/add.png">&nbsp<a class="w3-link" onclick="loadConfigPage(\'port-add.php\',\'addport\')">&lt;Add a Port&gt;</a>'];
	$aHeader = [
	'<div title="Port Number">Port</div>',
	'<div title="Protocol which can be used on this Port">Protocol</div>',
	'<div title="Indicates whether OSLC / WebEA support is enabled">OSLC Support</div>',
	'<div title="The type of authentication which is required for this Port (if any)">Authentication</div>',
	'Actions'
	];
	$aFields = ['port','requiresssl','oslc','authentication','actions'];
	$sTableAttr = 'id="ports-table" class="config-table"';
	WriteTable($sTableAttr, $aHeader, $aData, $aFields);
	WriteButton('OK','','button button-ok','onclick="loadConfigPage(\'home.php\')"');
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
</script>