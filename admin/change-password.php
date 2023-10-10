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
	echo '<form id="config-server-pwd-form" role="form" onsubmit="onFormSubmit(event, \'#config-server-pwd-form\' , \'saveserverpwd\')">';
	WriteBreadcrumb('Change Password', 'model_repository/webconfig_server_settings.html');
	WriteHeading('Change Pro Cloud Server Password');
	echo '<br>';
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('New Password');
	WriteTextField('','','','name="pwd1" type="password" required');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Repeat New Password');
	WriteTextField('','','','name="pwd2" type="password" required');
	echo '</div>';
	echo '<br>';
	echo '<div class="config-line">';
	WriteLabel('Note: You will be prompted to login after the password change','','','style="width:600px;"');
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'pcs-config.php\')"');
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