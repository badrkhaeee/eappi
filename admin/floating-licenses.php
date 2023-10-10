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
	$aDataPath = ['KeystoreConfiguration'];
	$sPostBody = '';
	$sPostBody .= '<RetrieveServerProperties>';
	$sPostBody .= 	'<AutoCheckIn />';
	$sPostBody .= '<TimeoutPeriod />';
	$sPostBody .= '</RetrieveServerProperties>';
	$aConfig = GetPostResults($sPCS_URL.'/config/saveflsconfig/',$sPostBody, $sError, $aDataPath, true);
	if(empty($aConfig))
	{
	echo 'Failed to retrieve keystore data. Please log out, restart the Pro Cloud Service, then try again.';
	exit();
	}
	$aConfig['AutoCheckIn'] = $aConfig['AutoCheckIn']['@attributes']['value'];
	$aTimeOutDays = $aConfig['TimeoutPeriod']['@attributes']['days'];
	if ($aTimeOutDays % 7 == 0) {
	$aConfig['TimeoutPeriod'] = ($aConfig['TimeoutPeriod']['@attributes']['days'] / 7);
	$aConfig['PeriodType'] = 'Weeks';
	}
	else
	{
	$aConfig['TimeoutPeriod'] = $aConfig['TimeoutPeriod']['@attributes']['days'];
	$aConfig['PeriodType'] = 'Days';
	}
	$aDataPath =['ManagementKeyList'];
	$aData = GetPostResults($sPCS_URL.'/config/getallflskeys/','', $sError, $aDataPath, true);
	$aAllKeys = [];
	$aUsedKeys = $aData['UsedKeys'];
	SafeGetArray($aUsedKeys, 'Key');
	$aAvailableKeys = $aData['AvailableKeys'];
	SafeGetArray($aAvailableKeys, 'Key');
	foreach($aUsedKeys as $aUsedKey)
	{
	$aAllKeys[] = $aUsedKey['@attributes'];
	}
	foreach($aAvailableKeys as $aAvailableKey)
	{
	$aAllKeys[] = $aAvailableKey['@attributes'];
	}
	$sKeyCount = count($aAllKeys);
	$aProducts = [];
	foreach ($aAllKeys as &$aKey)
	{
	$sComputer = SafeGetArrayItem1Dim($aKey, 'computer');
	$sUser = SafeGetArrayItem1Dim($aKey, 'user');
	if($aKey['academic'] === '1')
	{
	$aKey['product'] = 'Academic';
	}
	if($sComputer !== '')
	{
	$aKey['assigned_to'] = SafeGetArrayItem1Dim($aKey, 'computer') .'\\' . SafeGetArrayItem1Dim($aKey, 'user');
	}
	if(!in_array($aKey['product'], $aProducts))
	{
	$aProducts[] = $aKey['product'];
	}
	}
	$aProductSummary = [];
	$dateNow = new DateTime("now");
	foreach ($aProducts as &$aProduct)
	{
	$iAvailable = 0;
	$iExpired = 0;
	$iCheckedOut = 0;
	$iTotal = 0;
	foreach ($aAllKeys as $akey)
	{
	if($akey['product'] === $aProduct)
	{
	if(!isset($akey['assigned_to']))
	{
	$iAvailable++;
	$iTotal++;
	}
	else
	{
	$dateExires = new DateTime(($akey['expires']));
	if ($dateExires > $dateNow)
	{
	$iCheckedOut++;
	}
	else
	{
	$iExpired++;
	$iTotal++;
	}
	}
	}
	}
	$aProductSummary[]= ['product' => $aProduct, 'available' => $iAvailable, 'expired' => $iExpired, 'checked_out' => $iCheckedOut, 'total_available' => $iTotal];
	}
	if (empty($aProductSummary))
	{
	$aProductSummary[] = ['product' => htmlspecialchars('<no keys>'), 'available' => '', 'expired' => '', 'checked_out' => '', 'total_available' => ''];
	}
	WriteBreadcrumb('Manage EA Floating Licenses', 'model_repository/webconfig_manage_floating_licenses.html');
	WriteHeading('License Server Settings');
	echo '<div class="fls-settings-section">';
	echo '<form id="config-flsconfig-form" role="form" onsubmit="onFormSubmit(event, \'#config-flsconfig-form\' , \'saveflsconfig\')">';
	echo '<div class="config-section" style="background-color: #f7f7f7;border-radius: 4px;padding: 12px;border: 1px solid #f1f1f1; margin-bottom:24px;">';
	echo '<div class="config-line" title="Total number of keys.">';
	WriteLabel('Total Key Count');
	WriteValue($sKeyCount);
	echo '</div>';
	echo '<div class="config-line" title="Determines when a checked out key is automatically returned to the keystore.">';
	WriteLabel('Key leases expire after');
	WriteTextField(SafeGetArrayItem1Dim($aConfig, 'TimeoutPeriod'),'','textfield-small','name="timeoutperiod"');
	echo '&nbsp&nbsp&nbsp&nbsp';
	$aDropdown = ['Days','Weeks'];
	WriteDropdown($aDropdown,'','','name="periodtype"',SafeGetArrayItem1Dim($aConfig, 'PeriodType'));
	echo '</div>';
	echo '<div class="config-line" title="When enabled checked out keys are automatically returned when the user closes EA.">';
	WriteCheckbox('Auto Checkin','','','name="autocheckin"',SafeGetArrayItem1Dim($aConfig, 'AutoCheckIn'));
	echo '</div>';
	WriteButton('Apply','','button button-apply');
	echo '</div>';
	echo '</form>';
	echo '</div>';
	echo '<div class="fls-settings-section-right">';
	WriteButton('Configure Groups','','button','type="button" title="Manage Floating License Group Configuration" onclick="loadConfigPage(\'fls-groups.php\')"');
	echo '</div>';
	WriteHeading('Key Summary');
	$aHeader = [
	'<div title="Name of the Product">Product</div>',
	'<div title="Number of checked in keys">Available</div>',
	'<div title="Number of keys which are checked out but available because their lease has expired">Expired Lease (Available)</div>',
	'<div title="Number of keys which are checked out">Checked Out</div>',
	'<div title="Total number of available keys for this product type">Total Available</div>'
	];
	$aFields = ['product', 'available', 'expired', 'checked_out', 'total_available'];
	$sTableAttr = 'id="key-summary-table" class="config-table"';
	WriteTable($sTableAttr, $aHeader, $aProductSummary, $aFields);
	WriteHeading('Keys');
	foreach ($aAllKeys as &$aRow)
	{
	if (isset($aRow['assigned_to']))
	{
	$sCheckInButton = '<img alt="" class="config-icon" src="images/checkinkey.png" title="Check In Key" onclick="onClickButton(\'checkinflskey\',\''.ConvertStringToParameter($aRow['key']).'\')">';
	}
	else
	{
	$sCheckInButton = '<div class="config-icon-blank"></div>';
	}
	$aRow['remove_button'] = $sCheckInButton . '<img alt="" class="config-icon" src="images/delete.png" title="Delete Key" onclick="onClickButton(\'deleteflskey\',\''.ConvertStringToParameter($aRow['key']).'\')">';
	}
	$aAllKeys[] = ['key' => '<img alt="" class="config-add-icon" src="images/add.png">&nbsp<a class="w3-link" onclick="loadConfigPage(\'floating-license-add.php\')">&lt;Add Keys&gt;</a>'];
	$aHeader = [
	'<div title="Product Key">Key</div>',
	'<div title="Product Name">Product</div>',
	'<div title="Who currently has this key checked out">Assigned To</div>',
	'<div title="When this key will automatically returned to the key store">Lease Expires</div>',
	'Actions'];
	$aFields = ['key','product','assigned_to','expires','remove_button'];
	$sTableAttr = 'id="keys-table" class="config-table"';
	WriteTable($sTableAttr, $aHeader, $aAllKeys, $aFields);
	WriteButton('OK','','button button-ok','onclick="loadConfigPage(\'home.php\')"');
	function GetKeystore($sUrl)
	{
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_PORT => "805",
	CURLOPT_URL => $sUrl,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_SSL_VERIFYHOST => 0,
	  CURLOPT_HTTPHEADER => array(
	"Authorization: Basic YWRtaW46cGFzc3dvcmQ=",
	"Cache-Control: no-cache",
	"Postman-Token: f9107aa1-10ab-4fd8-ab3a-d4193904bd05"
	  ),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	return $response;
	}
	}
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
});
</script>