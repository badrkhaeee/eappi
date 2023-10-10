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
	$sHeader = 'New License Request';
	WriteBreadcrumb($sHeader, 'model_repository/webconfig-new-license.html');
	WriteHeading($sHeader);
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('1) Purchase a Pro Cloud License. An Installation ID will be provided via email.','','label-large');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('2) Use the form below to create a License Request file.','','label-large');
	echo '</div>';
	echo '</div>';
	WriteLicenseRequestForm();
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('3) Send the License Request File to the Sparx Sales Team for verification.','','label-large');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('4) The Sparx Sales team will respond with your Pro Cloud License file. This can be added via the <a class="config-bc-link" onclick="loadConfigPage(\'pcs-license-add.php\')">Add License</a> page.','','label-large');
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="button" onclick="loadConfigPage(\'pcs-licenses.php\',null,null,true,true)"');
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
  OnLoad_DatePickerCtrls('config-lic-req-startdate|config-lic-req-startdate-img');
});
</script>