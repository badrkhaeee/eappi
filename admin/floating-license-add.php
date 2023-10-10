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
	WriteBreadcrumb('Add Floating Licenses', 'model_repository/webconfig_manage_floating_licenses.html');
	WriteHeading('Add Floating Licenses');
	echo '<form id="config-lic-add-form" role="form" onsubmit="onFormSubmit(event, \'#config-lic-add-form\', \'addflskey\')">';
	echo '<div class="config-section">';
	WriteLabel('Enter Floating License/s');
	WriteTextArea('', '','textarea','style="height:400px" name="key" title="Enter one or more EA floating license keys (one key per line)"');
	echo '<br>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'floating-licenses.php\')"');
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select, textarea').change(function() {
        bIsDirty = true;
   });
});
</script>