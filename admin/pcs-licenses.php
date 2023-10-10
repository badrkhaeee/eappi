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
	$sError = '';
	$aDataPath = ['pcs-license-certs'];
	$aData = GetPostResults($sPCS_URL.'/config/getallpcslicensecerts/','', $sError, $aDataPath, true);
	$bTokenAllocationAllowed = SafeGetArrayItem1Dim($aData, 'token-allocation-allowed');
	$aDataPath = ['license-certs','license-cert'];
	$aData = GetData($aData, $aDataPath, true);
	foreach ($aData as &$aRow)
	{
	$sWarningIcon = '';
	if (SafeGetArrayItem1Dim($aRow, 'valid') === 'false')
	{
	$sReason = SafeGetArrayItem1Dim($aRow, 'reason');
	$sNotVerified = 'The License file could not be verified.,'. "\n";
	$sReason = str_replace($sNotVerified,'',$sReason);
	$sWarningIcon = '<div><div class="table-icon-container"><img alt="" class="" style="vertical-align:top;" title="'.$sReason .'" src="images/alert.png"></div></div>';
	$aRow['valid'] = $sWarningIcon;
	}
	else if (SafeGetArrayItem1Dim($aRow, 'valid') === 'true')
	{
	$aRow['valid'] = '<div><div class="table-icon-container" title="This license is currently valid and in use by the Pro Cloud Server."><img alt="" class="tick-icon" src="images/spriteplaceholder.png"></div></div>';
	}
	$sEdition = SafeGetArrayItem1Dim($aRow, 'edition');
	GetFriendlyEdition($sEdition);
	$sTokenLabel = '';
	if (SafeGetArrayItem1Dim($aRow, 'tokencount') !== '0')
	{
	$sEdition =   $aRow['tokencount'] . ' Tokens';
	}
	$aRow['edition'] = $sEdition;
	$sCompany = '<div class="license-tb-company" title="'.SafeGetArrayItem1Dim($aRow, 'company').'">' .SafeGetArrayItem1Dim($aRow, 'company') .'</div>';
	$aRow['company'] = $sCompany;
	$sEmail = '<div class="license-tb-email" title="'.SafeGetArrayItem1Dim($aRow, 'email').'">' .SafeGetArrayItem1Dim($aRow, 'email') .'</div>';
	$aRow['email'] = $sEmail;
	$aRow['actions'] ='<img alt="" class="config-icon" src="images/renew.png" title="Renew License" onclick="loadConfigPage(\'pcs-renew-license-request.php\',\'renewlicensecert\',\''.SafeGetArrayItem1Dim($aRow, 'key').'\')">';
	$aRow['actions'] .= '<img alt="" class="config-icon" src="images/delete.png" title="Delete License" onclick="onClickButton(\'deletelicensecertprompt\',\''.SafeGetArrayItem1Dim($aRow, 'key').'\')">';
	}
	$aData[] = ['edition' => '<img alt="" class="config-add-icon" src="images/add.png">&nbsp<a class="w3-link" onclick="loadConfigPage(\'pcs-license-add.php\')">&lt;Add License&gt;</a>'];
	$aHeader = ['<div title="License Type">License Type</div>',
	'<div title="Indicates whether the license is valid">Valid</div>',
	'<div title="License Start Date">Start Date</div>',
	'<div title="License End Date">Expiry Date</div>',
	'<div title="Company name associated with the license">Company</div>',
	'<div title="Email address associated with the license">Email</div>',
	'Actions'];
	$aFields = ['edition', 'valid' ,'startdate' ,'expirydate', 'company', 'email', 'actions'];
	$sTableAttr = 'id="pcs-license-table" class="config-table"';
	WriteBreadCrumb('Pro Cloud Server Licenses', 'model_repository/webconfig-pcs-licences.html');
	WriteHeading('Pro Cloud Server Licenses');
	WriteTable($sTableAttr, $aHeader, $aData, $aFields);
	WriteHeading('Tasks');
	echo '<div class="config-section config-section-license-tasks">';
	echo '<div class="config-line">';
	WriteButton('New License Request','','',' type="button" onclick="loadConfigPage(\'pcs-new-license-request.php\')" title="Create a new License Request file, to be send to Sparx Systems for validation"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteButton('Add License','','',' type="button" onclick="loadConfigPage(\'pcs-license-add.php\')" title="Add a License, using the License file provided by Sparx Systems"');
	echo '</div>';
	if (strIsTrue($bTokenAllocationAllowed))
	{
	echo '<div class="config-line">';
	WriteButton('Allocate Tokens','','',' type="button" onclick="loadConfigPage(\'allocate-tokens.php\')" title="Manage Pro Cloud Token Allocation"');
	echo '</div>';
	}
	echo '</div>';
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