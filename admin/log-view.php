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
	$sFileName = SafeGetInternalArrayParameter($_POST, 'id','');
	$sError = '';
	$sPostBody = '';
	$sPostBody .= '<log-file>';
	$sPostBody .= '<file>'.$sFileName.'</file>';
	$sPostBody .= '</log-file>';
	$aDataPath = [];
	$xmlDoc = HTTPPostXMLRaw($sPCS_URL.'/config/getlog/',$sPostBody, $sError);
	echo '<div class="breadcrumb">';
	echo '<div class="heading-breadcrumb">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'home.php\',\'true\')">Home</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'logs.php\',\'true\')">Logs</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sFileName.'</a>';
	echo '</div>';
	echo '</div>';
	$iStart = strpos($xmlDoc,'<![CDATA[') + 9;
	$iEnd = strrpos($xmlDoc,']]>');
	$iLength = $iEnd - $iStart;
	$sLogText = substr($xmlDoc, $iStart, $iLength);
	$rows        = explode("\n", $sLogText);
	echo '<div class="config-section" style="white-space: pre;font-family: monospace;font-size: 12px;">';
	echo $sLogText;
	echo '</div>';
	WriteButton('OK','','button button-ok','type="button" onclick="loadConfigPage(\'logs.php\')"');
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
</script>
<style>
.main-contents {
	width:unset;
}
</style>