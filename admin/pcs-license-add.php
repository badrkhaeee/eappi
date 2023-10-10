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
	echo '<form id="config-add-license-form" role="form" onsubmit="onFormSubmit(event, \'#config-add-license-form\', \'addlicensecert\')">';
	WriteBreadcrumb('Add Pro Cloud Server License', 'model_repository/webconfig-add-license.html');
	WriteHeading('Add Pro Cloud Server License');
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Use the Browse button below to select your Pro Cloud License file, then click the OK button.','','label-large');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('If you do not have a Pro Cloud License file, start with a <a class="config-bc-link" onclick="loadConfigPage(\'pcs-new-license-request.php\')">New License Request</a>.','','label-large');
	echo '</div>';
	echo '</div>';
	echo '<div class="config-section-license-file">';
	echo '<div class="config-line">';
	WriteLabel('License File','','label-small');
	echo '<input type="file" id="file" name="file" accept=".lic,.crt" enctype="multipart/form-data" />';
	echo '<textarea name="licensefile" hidden></textarea>';
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'pcs-licenses.php\')"');
	echo '</form>';
?>
<script>
document.getElementById('file').addEventListener('change', readFile, false);
function readFile (evt) {
	var files = evt.target.files;
	var file = files[0];
	if (file.size > 20000)
	{
	webea_error_message('File Max Size Exceeded. Please select a valid .LIC or .CRT file.');
	}
	else
	{
	var reader = new FileReader();
	reader.onload = function(event) {
	$("[name=licensefile]").val(event.target.result);
	}
	reader.readAsText(file)
	}
}
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
</script>