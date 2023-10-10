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
	$aDataPath = ['log-file-configuration','logfiles','logfile'];
	$aData = GetPostResults($sPCS_URL.'/config/getlogs/','', $sError , $aDataPath, true);
	foreach ($aData as &$aRow)
	{
	$aRow['filesize'] = round($aRow['filesize'] / 1024) . ' KB';
	$aRow['view_button'] = '<img alt="" class="config-icon" src="images/viewlog.png" title="View Log" onclick="loadConfigPage(\'log-view.php\',\'view\',\''. ConvertStringToParameter($aRow['filename']) .'\')">';
	}
	$aHeader = [
	'<div title="Log file name">File Name</div>',
	'<div title="File size (in KB)">File Size</div>',
	'<div title="Date/Time the file was last modified">Modified Date</div>',
	'Actions'
	];
	$aFields = ['filename','filesize','modifydate','view_button'];
	$sTableAttr = 'id="logs-table" class="config-table"';
	WriteBreadcrumb('Logs', 'model_repository/webconfig_home_screen.html');
	WriteHeading('View Logs');
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