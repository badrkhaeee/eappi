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
	$aDataPath = ['license-allocs'];
	$aData = GetPostResults($sPCS_URL.'/config/getlicenseallocs/','', $sError, $aDataPath);
	$sProEdition = SafeGetArrayItem1Dim($aData, 'licenseedition');
	$iTotaltokencount = SafeGetArrayItem1Dim($aData,'totaltokencount');
	$iTotalallocated = SafeGetArrayItem1Dim($aData,'totalreserved');
	$iTotalfloating = SafeGetArrayItem1Dim($aData,'totalunreserved');
	$sIntegrationCost = '1';
	$bIntegrationEnabled = SafeGetArrayItem1Dim($aData,'sbpienabled');
	$sIntegrationInUse = SafeGetArrayItem1Dim($aData,'sbpiinuse');
	$iSBPIAllocated = SafeGetArrayItem1Dim($aData,'sbpiallocated');
	$sSBPIProvidersMarked =  SafeGetArrayItem1Dim($aData,'sbpiprovidersmarked');
	$sSBPIProvidersEnabled =  SafeGetArrayItem1Dim($aData,'sbpiprovidersenabled');
	$aSBPIProvidersMarked = str_getcsv($sSBPIProvidersMarked,',');
	$aDataPath = ['provider-types','provider-type'];
	$aProviderTypes = GetPostResults($sPCS_URL.'/config/getsbpiprovidertypes/','', $sError, $aDataPath, true);
	$aIntegrations = [];
	foreach ($aProviderTypes as &$aProviderType)
	{
	$sCheckedState = "";
	foreach ($aProviderType as &$aProviderValue)
	{
	if (is_array($aProviderValue) && empty($aProviderValue))
	{
	$aProviderValue = "";
	}
	}
	if(in_array($aProviderType['key'],$aSBPIProvidersMarked))
	{
	$sCheckedState = "checked";
	}
	$aIntegrations[] = [$aProviderType['name'],$aProviderType['key'],$sCheckedState];
	}
	$sWebEACost = SafeGetArrayItem1Dim($aData,'webeacost');
	$sWebEAInUse = SafeGetArrayItem1Dim($aData,'webeainuse');
	$sWebEAReserved = SafeGetArrayItem1Dim($aData,'webeaallocated');
	$sWebEAMax = SafeGetArrayItem1Dim($aData,'webeamax');
	$sProlabCost = SafeGetArrayItem1Dim($aData,'prolabcost');
	$sProlabInUse = SafeGetArrayItem1Dim($aData,'prolabinuse');
	$sProlabReserved = SafeGetArrayItem1Dim($aData,'prolaballocated');
	$sProlabMax = SafeGetArrayItem1Dim($aData,'prolabmax');
	$sOSLCCost = SafeGetArrayItem1Dim($aData,'oslccost');
	$sOSLCInUse = SafeGetArrayItem1Dim($aData,'oslcinuse');
	$sOSLCReserved = SafeGetArrayItem1Dim($aData,'oslcallocated');
	$sOSLCMax = SafeGetArrayItem1Dim($aData,'oslcmax');
	$iSharedTokenCount = $iTotaltokencount - $iSBPIAllocated;
	$sInvalidAllocWarning = SafeGetArrayItem1Dim($aData,'invalidtokenallocwarning');
	$sExceedReserveWarning = SafeGetArrayItem1Dim($aData,'exceededreservewarning');
	if ($sWebEAMax === '-1')
	{
	$sWebEAMax = '';
	}
	if ($sProlabMax === '-1')
	{
	$sProlabMax = '';
	}
	if ($sOSLCMax === '-1')
	{
	$sOSLCMax = '';
	}
	echo '<form id="config-license-allocations-form" role="form" onsubmit="onFormSubmit(event, \'#config-license-allocations-form\' , \'savelicenseallocs\')">';
	WriteBreadcrumb('Manage Allocations', 'model_repository/webconfig-allocate-tokens.html');
	if ($iTotalfloating < 0)
	{
	$iTotalfloating = '';
	}
	$sWarningIcon = '';
	if(!strIsEmpty($sExceedReserveWarning))
	{
	$sWarningIcon = '<img alt="" id="reserved-exceeded" class="" style="vertical-align: top; height:16px" title="'.$sExceedReserveWarning.'" src="images/alert.png">';
	}
	echo '<div class="token-summary-container">';
	WriteHeading('Summary');
	echo '<div class="config-section config-section-token-summary">';
	echo '<table class="table-allocation-summary">';
	echo '<tbody>';
	echo '<tr>';
	echo '<td style="font-weight:bold">Total Tokens</td><td id="total-tokens" title="Total Number of Tokens, based on the current license.">' . $iTotaltokencount . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>&nbsp&nbspExplicit</td><td title="Number of Tokens which are reserved for specific features.">' . $iSBPIAllocated . '</td><td>' . $sWarningIcon . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>&nbsp&nbspShared</td><td title="Number of Tokens which are not reserved a specific feature.">' . $iSharedTokenCount . '</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
	WriteHeading('Explicit Token Allocations');
	echo '<div class="config-section" style="padding-bottom:32px;">';
	echo '<table class="table-explicit-allocations">';
	echo '<tbody>';
	echo '<tr id="token-alloc-integration-row">';
	echo '<td>';
	echo 'Integrations';
	echo '<td style="width: 300px;">';
	echo '<div id="token-alloc-integration-list">';
	foreach ($aIntegrations as $aIntegration)
	{
	echo '<div>';
	echo '<input type="checkbox" ' . $aIntegration[2]. ' id="enable-integration" name="'.$aIntegration[1].'" value="1">';
	echo '<div class="token-alloc-provider-name">' . $aIntegration[0] .'</div>';
	echo '</div>';
	}
	echo '</div>';
	echo '<td></td>';
	echo '</tr>';
	echo '<tr style="height: 16px;"></tr>';
	echo '<tr>';
	echo '<td>';
	echo 'Used Tokens';
	echo '</td>';
	echo '<td>';
	echo $iSBPIAllocated;
	echo '</td>';
	echo '<td>';
	echo '</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '<input name="sbpiprovidersmarked" value="'.$sSBPIProvidersMarked .'" hidden>';
	echo '</div>';
	WriteHeading('Shared Tokens for Web Access');
	echo '<div class="config-section" style="padding-bottom:32px;">';
	echo '<table class="table-allocate-tokens">';
	echo '<tbody>';
	echo '<tr>';
	echo '<td>';
	echo 'Shared Tokens';
	echo '</td>';
	echo '<td>';
	echo $iSharedTokenCount;
	echo '<td>';
	echo '</td>';
	echo '</tr>';
	echo '<tr style="height: 16px;"></tr>';
	echo '<tr>';
	echo '<td>';
	echo 'Reserved for WebEA';
	echo '</td>';
	echo '<td>';
	WriteTextField($sWebEAReserved,'','textfield-token-allocated','name="webeaallocated" type="text" onchange="onChangeTokens(this)" title="The number of tokens reserved for WebEA users (each concurrent user consumes 1 token)"');
	echo '<td>';
	WriteTextField($sWebEAMax,'','textfield-token-max','name="webeamax" type="text" onchange="onChangeTokens(this)" title="The maximum number of tokens which can be used by WebEA users (each concurrent user consumes 1 token). To apply no maximum, leave this field empty."');
	echo 'Max Allowed';
	echo '<a></a>';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>';
	echo 'Reserved for Prolaborate';
	echo '</td>';
	echo '<td>';
	WriteTextField($sProlabReserved,'','textfield-token-allocated','name="prolaballocated" type="text" onchange="onChangeTokens(this)" title="The number of tokens reserved for Prolaborate users (each concurrent user consumes 1 token)"');
	echo '</td>';
	echo '<td>';
	WriteTextField($sProlabMax,'','textfield-token-max','name="prolabmax" type="text" onchange="onChangeTokens(this)" title="The maximum number of tokens which can be used by Prolaborate users (each concurrent user consumes 1 token). To apply no maximum, leave this field empty."');
	echo 'Max Allowed';
	echo '<a></a>';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>';
	echo 'Reserved for OSLC';
	echo '</td>';
	echo '<td>';
	WriteTextField($sOSLCReserved,'','textfield-token-allocated','name="oslcallocated" type="text" onchange="onChangeTokens(this)" title="The number of tokens reserved for OSLC users (each concurrent user consumes 1 token)"');
	echo '</td>';
	echo '<td>';
	WriteTextField($sOSLCMax,'','textfield-token-max','name="oslcmax" type="text" onchange="onChangeTokens(this)" title="The maximum number of tokens which can be used by OSLC users (each concurrent user consumes 1 token). To apply no maximum, leave this field empty."');
	echo 'Max Allowed';
	echo '<a></a>';
	echo '</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	WriteCollapsibleHeading('Token Usage');
	echo '<div class="token-usage-container" style="display:none">';
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Total Tokens');
	WriteValue($iTotaltokencount);
	echo '</div>';
	echo '</div>';
	echo '<div class="config-section config-section-token-usage">';
	echo '<table class="table-token-usage">';
	echo '<tbody>';
	echo '<tr>';
	echo '<th></th><th title="Number of tokens which are reserverd for use by this feature only.">Reserved</th><th title="Number of reserved tokens which are currently in use by this feature.">Reserved<br>In Use</th><th title="Number of unreserved tokens which are currently in use by this feature.">Unreserved<br>In Use</th><th title="Total Number of tokens which are consumed by this feature (the number reserved, plus the number of unreserved tokens which are current being used).">Consumed</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>';
	echo 'Integrations';
	echo '</td>';
	echo '<td>';
	echo '<a title="' . $sIntegrationInUse . ' tokens reserved by Integrations being Enabled">' . $sIntegrationInUse . '</a>';
	echo '</td>';
	echo '<td>';
	echo '<a title="Enabling Integrations consumes tokens immediately.">N/A</a>';
	echo '</td>';
	echo '<td>';
	echo '<a title="Enabling Integrations consumes tokens immediately.">N/A</a>';
	echo '</td>';
	echo '<td>';
	echo '<a title="' . $sIntegrationInUse . ' tokens consumed by Integrations being Enabled">' . $sIntegrationInUse . '</a>';
	echo '<a></a>';
	echo '</td>';
	echo '</tr>';
	echo '<tr style="height: 16px;"></tr>';
	echo '<tr>';
	echo '<td>';
	echo 'WebEA Users';
	echo '</td>';
	echo '<td>';
	echo WriteTokensReserved($sWebEAReserved);
	echo '</td>';
	echo '<td>';
	echo WriteTokensReservedInUse($sWebEAInUse, $sWebEAReserved);
	echo '</td>';
	echo '<td>';
	echo WriteTokensFloating($sWebEAInUse, $sWebEAReserved);
	echo  '<a></a>';
	echo '</td>';
	echo '<td>';
	echo WriteTokensConsumed($sWebEAInUse, $sWebEAReserved);
	echo '<a></a>';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>';
	echo 'Prolaborate Users';
	echo '</td>';
	echo '<td>';
	echo WriteTokensReserved($sProlabReserved);
	echo '</td>';
	echo '<td>';
	echo WriteTokensReservedInUse($sProlabInUse, $sProlabReserved);
	echo '</td>';
	echo '<td>';
	echo WriteTokensFloating($sProlabInUse, $sProlabReserved);
	echo  '<a></a>';
	echo '</td>';
	echo '<td>';
	echo WriteTokensConsumed($sProlabInUse, $sProlabReserved);
	echo '<a></a>';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>';
	echo 'OSLC Users';
	echo '</td>';
	echo '<td>';
	echo WriteTokensReserved($sOSLCReserved);
	echo '</td>';
	echo '<td>';
	echo WriteTokensReservedInUse($sOSLCInUse, $sOSLCReserved);
	echo '</td>';
	echo '<td>';
	echo WriteTokensFloating($sOSLCInUse, $sOSLCReserved);
	echo  '<a></a>';
	echo '</td>';
	echo '<td>';
	echo WriteTokensConsumed($sOSLCInUse, $sOSLCReserved);
	echo '<a></a>';
	echo '</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Available');
	$iTotalConsumed = $sIntegrationInUse + WriteTokensConsumed($sWebEAInUse, $sWebEAReserved, true) + WriteTokensConsumed($sProlabInUse, $sProlabReserved, true) + WriteTokensConsumed($sOSLCInUse, $sOSLCReserved, true);
	$iAvailable = $iTotaltokencount - $iTotalConsumed;
	WriteValue($iAvailable);
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<input id="reload" value="0" hidden>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button" onclick="loadConfigPage(\'pcs-licenses.php\')"');
	WriteButton('Apply','config-token-apply-button','button button-ok','type="button" onclick="applyTokens()"');
	echo '</form>';
	function WriteTokensConsumed($sInUse, $sReserved, $bValueOnly = false)
	{
	if ($sInUse < $sReserved)
	{
	if ($bValueOnly)
	{
	$sInUse = $sReserved;
	}
	else
	{
	$sInUse = '<div title="' . $sReserved . ' Reserved (' . $sInUse . ' currently being used).">' . $sReserved . '</div>';
	}
	}
	else if ($sInUse > $sReserved)
	{
	$sFloatingUsed = $sInUse - $sReserved;
	if(!$bValueOnly)
	{
	$sInUse = '<div title="All ' . $sReserved . ' Reserved Tokens are in use, plus ' . $sFloatingUsed . ' floating tokens.">' . $sInUse . '</div>';
	}
	}
	else if ($sInUse === $sReserved)
	{
	if(!$bValueOnly)
	{
	if ($sInUse !== '0')
	{
	$sInUse = '<div title="' . $sReserved . ' Reserved (All currently being used).">' . $sReserved . '</div>';
	}
	else
	{
	$sInUse = '<div title="' . $sInUse . ' tokens consumed.">' . $sInUse . '</div>';
	}
	}
	}
	return $sInUse;
	}
	function WriteTokensReserved($sReserved)
	{
	$sReserved = '<div title="'.$sReserved.' reserved">' . $sReserved . '</div>';
	return $sReserved;
	}
	function WriteTokensReservedInUse($sInUse, $sReserved)
	{
	if ($sInUse >= $sReserved)
	{
	$sProReserveUsed = '<div title="'.$sReserved.' of '. $sReserved .' reserved tokens are currently in use.">' . $sReserved . '</div>';
	}
	else
	{
	$sProReserveUsed = '<div title="'.$sInUse.' of '. $sReserved .' reserved tokens are currently in use.">' . $sInUse . '</div>';
	}
	return $sProReserveUsed;
	}
	function WriteTokensFloating($sInUse, $sReserved)
	{
	if ($sInUse >= $sReserved)
	{
	$sProFloatingUsed = $sInUse - $sReserved;
	$sProFloatingUsed = '<div title="' . $sProFloatingUsed . ' floating tokens are currently in use.">' . $sProFloatingUsed . '</div>';
	}
	else
	{
	$sProFloatingUsed = '0';
	$sProFloatingUsed = '<div title="' . $sProFloatingUsed . ' floating tokens are currently in use.">' . $sProFloatingUsed . '</div>';
	}
	return $sProFloatingUsed;
	}
?>
<script>
var bIsDirty = false;
if($("#reserved-exceeded").length)
{
	bIsDirty = true;
}
$(document).ready(function() {
   $('input, select').change(function() {
        bIsDirty = true;
   });
   onChangeTokens($("[name=promodelsallocated]"));
   onChangeTokens($("[name=promodelsmax]"));
   onChangeTokens($("[name=webeaallocated]"));
   onChangeTokens($("[name=webeamax]"));
   onChangeTokens($("[name=prolaballocated]"));
   onChangeTokens($("[name=prolabmax]"));
});
function applyTokens()
{
	$("#reload").val(1);
	onFormSubmit('', '#config-license-allocations-form' , 'savelicenseallocs');
}
</script>