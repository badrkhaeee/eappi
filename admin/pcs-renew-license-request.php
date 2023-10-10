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
	$sKey = SafeGetInternalArrayParameter($_POST , 'id');
	$sLicID = '<license-cert-sig>'.$sKey.'</license-cert-sig>';
	$sError ='';
	$aDataPath = ['license-details' , 'license-cert'];
	$aData = GetPostResults($sPCS_URL . '/config/getpcslicensecertdetails/', $sLicID, $sError, $aDataPath);
	$sCompany = SafeGetArrayItem1Dim($aData, 'customer');
	$sEmail = SafeGetArrayItem1Dim($aData, 'email');
	$sExpiryDate = SafeGetArrayItem1Dim($aData, 'expirydate');
	$sRenewDate = date('Y-m-d', strtotime($sExpiryDate. ' + 1 days'));
	$sHeader = 'Renew License Request';
	WriteBreadcrumb($sHeader, 'model_repository/webconfig-renew-license.html');
	WriteHeading($sHeader);
	WriteLicenseRequestForm($sCompany, $sEmail, $sRenewDate, $sKey);
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