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
	$sUsername = SafeGetInternalArrayParameter($_POST, 'id','');
	if ($sAction === 'flsgroupedit')
	{
	$sHeading = 'Edit Floating License Group';
	$sBreadCrumb = 'Edit Group';
	$sSubmitAction = 'saveflsgroup';
	$sPCS_URL = $_SESSION['pcs_url'];
	$aDataPath = ['fls-group'];
	$sPostBody = '<request><username>' . $sUsername . '</username></request>';
	$aGroup = GetPostResults($sPCS_URL.'/config/getflsgroup/',$sPostBody, $sError, $aDataPath, true);
	$sUsername = SafeGetArrayItem1Dim($aGroup, 'username');
	$sDescription = SafeGetArrayItem1Dim($aGroup, 'description');
	$sPassword = SafeGetArrayItem1Dim($aGroup, 'pwd');
	$sDecrPwd = '';
	if (!strIsEmpty($sPassword))
	{
	$sDecrPwd = EncryptDecrypt($sPassword, false);
	}
	$sIsAdmin = SafeGetArrayItem1Dim($aGroup, 'is-manager');
	$sStartDate = SafeGetArrayItem1Dim($aGroup, 'start-date');
	$sEndDate = SafeGetArrayItem1Dim($aGroup, 'end-date');
	$sActivation = SafeGetArrayItem1Dim($aGroup, 'activation');
	$sADGroup = SafeGetArrayItem1Dim($aGroup, 'ad-groups');
	$sOpenIDGroup = SafeGetArrayItem1Dim($aGroup, 'openid-group');
	$aEntitlements = SafeGetArrayItem1Dim($aGroup, 'entitlements');
	$aEntitlements = SafeGetArrayItem1Dim($aEntitlements, 'entitlement');
	ConvertToChildArrayItem('productid', $aEntitlements);
	foreach ($aEntitlements as &$aEntitlement)
	{
	if ($aEntitlement['isacademic'] === 'false')
	{
	$aEntitlement['license'] = 'Full';
	}
	else
	{
	$aEntitlement['license'] = 'Academic';
	}
	if ($aEntitlement['limit'] === '-1')
	{
	$aEntitlement['limit'] = '';
	}
	}
	}
	else
	{
	$sHeading = 'Add Floating License Group';
	$sBreadCrumb = 'Add Group';
	$sSubmitAction = 'addflsgroup';
	$sUsername = '';
	$sDescription = '';
	$sDecrPwd ='';
	$sIsAdmin = '';
	$sStartDate = '';
	$sEndDate = '';
	$sActivation = '';
	$sADGroup = '';
	$sOpenIDGroup = '';
	$aEntitlements = [];
	}
	$i = 1;
	$sRowID = '';
	foreach ($aEntitlements as &$aRow)
	{
	$sRowID = 'ent-row-' . ConvertStringToParameter($aRow['productname']).'-'.ConvertStringToParameter($aRow['license']);
	$aRow['tr_attributes'] = 'id="'.$sRowID.'"';
	$aRow['actions'] = '<img alt="" class="config-icon" src="images/edit.png" title="Edit Entitlement" onclick="ShowFLSGroupEntitlementDialog(\'edit\',\'#'.$sRowID.'\')">';
	$aRow['actions'] .= '<img alt="" class="config-icon" src="images/delete.png" title="Delete Entitlement" onclick="FLSGroupDeleteEntitlement(\'#'.$sRowID.'\')">';
	$i++;
	}
	$aEntitlements[] = ['productname' => '<img alt="" class="config-add-icon" src="images/add.png">&nbsp<a id="fls-add-group-ent-button" class="w3-link" title="Add a Group Entitlement (disabled for \'Is Admin\' groups)" onclick="ShowFLSGroupEntitlementDialog(\'add\',\'\')">&lt;Add&gt;</a>'];
	WriteBreadcrumb($sBreadCrumb, 'model_repository/webconfig_manage_floating_licenses.html');
	echo '<form id="config-add-fls-group-form" role="form" onsubmit="onFormSubmit(event, \'#config-add-fls-group-form\', \''.$sSubmitAction.'\')">';
	WriteHeading($sHeading);
	echo '<div class="config-section" style="padding-top:8px;">';
	echo '<div class="config-line">';
	WriteLabel('User Name<span class="field-label-required">&nbsp;*</span>');
	WriteTextField($sUsername,'','','name="username" title="The User Name of the current group. This value is entered in Enterprise Architect when connecting to the Floating License Server" required');
	if ($sAction === 'flsgroupedit')
	{
	echo '<input hidden name="old-username" value="'.$sUsername.'">';
	}
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Description<span class="field-label-required">&nbsp;*</span>');
	WriteTextField($sDescription,'','textfield-large','name="description" title="A description of the current group" required');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Password');
	WriteTextField($sDecrPwd,'','','name="grp-pwd" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" type="password" title="The Password of the current group. This value is entered in Enterprise Architect when connecting to the Flosting License Server"');
	echo '</div>';
	echo '<div class="config-line">';
	WriteCheckbox('Is Admin','','','name="is-manager" onchange="onSelectIsAdmin(this)" title="Defines if the current group is exclusively for managing the Floating License Server\'s configuration. If true, then some fields will be disabled."',$sIsAdmin, 'true');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Active Between');
	WriteTextField('','config-fls-group-startdate','','name="start-date" value="'.$sStartDate.'" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'config-fls-group-startdate\')" title="(Optional) Defines the first date that a user can log in using the credentials of the current group."');
	echo '<div style="display: none;"><img id="config-fls-group-startdate-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png"></div>';
	echo '<span class="active-between-date-seperator">and:</span>';
	WriteTextField('','config-fls-group-enddate','','name="end-date" value="'.$sEndDate.'" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'config-lic-req-start-date\')" title="(Optional) Defines the last date that a user can log in using the credentials of the current group."');
	echo '<div style="display: none;"><img id="config-fls-group-enddate-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png"></div>';
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Activation Code');
	WriteTextField($sActivation,'','textfield-small','name="activation" title="Defines the Enterprise Architect Activation Code that is associated with the current group. A value defined in this field saves individual users logged in as the current group from needing to specify an Activation Code."');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Active Directory Groups');
	WriteTextArea($sADGroup, '','textarea','style="height:64px" name="ad-groups" title="Defines the Active Directory Security groups that are associated with the current group, as a CSV. Each group should be defined in the format: WinNT://DOMAINNAME/ADGroup"');
	echo '<br>';
	echo '</div>';
	echo '<div class="config-line" style="padding-top:24px;">';
	echo '<div class="label" style="padding-top: 10px;">License Entitlements</div>';
	echo '<div style="width: 600px;display: inline-block;">';
	WriteTable('id="entitlements-table" class="config-table"', ['Product','License','Limit','Action'], $aEntitlements, ['productname','license','limit','actions']);
	echo '</div>';
	echo '<input hidden name="entitlements" value="'.'">';
	echo '</div>';
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button"  onclick="loadConfigPage(\'fls-groups.php\')"');
	echo '</form>';
	WriteAddEntitlementDialog();
	function GetFLSProductNames()
	{
	$sPCS_URL = $_SESSION['pcs_url'];
	$aDataPath =['fls-products','fls-product'];
	$aAllProducts = GetPostResults($sPCS_URL.'/config/getflsallproducts/','', $sError, $aDataPath, true);
	$aAllProductNames = [];
	foreach ($aAllProducts as $aProduct)
	{
	$aAllProductNames[] = $aProduct['name'];
	}
	return $aAllProductNames;
	}
	function WriteAddEntitlementDialog()
	{
	$sIsAcademic = 'false';
	$aProductNames = GetFLSProductNames();
	$sLimit = '';
	echo '<div id="fls-entitlements-dialog">';
	echo '<div class="webea-messagebox-title">';
	echo '<div id="webea-prompt-title-text">Add Group License Entitlement</div>';
	echo '<input class="webea-dialog-close-button" type="button" onclick="OnClickClosePopupDialog(\'#fls-entitlements-dialog\')">';
	echo '</div>';
	echo '<div class="webea-messagebox-body">';
	echo '<div class="config-section" style="padding-top:24px;">';
	echo '<div class="config-line">';
	WriteLabel('Product','','label fls-add-grp-label');
	$aDropdown = $aProductNames;
	$sDefaultEntitlement = 'Desktop';
	WriteDropdown($aDropdown,'','','name="product" title="Select the level of information to be written to the log file. System provides the highest level of logging. Each level incudles all logging from the lower levels also."', $sDefaultEntitlement);
	echo '<input hidden name="row-id" value="">';
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Academic','','label fls-add-grp-label');
	WriteCheckbox('','','','name="isacademic" title="Defines if the current group is exclusively for managing the Floating License Server\'s configuration. If true, then some fields will be disabled."',$sIsAcademic, true);
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Limit','','label fls-add-grp-label');
	WriteTextField($sLimit,'','textfield-small','name="limit" title="Defines the Enterprise Architect Activation Code that is associated with the current group. A value defined in this field saves individual users logged in as the current group from needing to specify an Activation Code."');
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-prompt-buttons">';
	echo '<div class="webea-prompt-buttons">';
	echo '<button class="button button-ok" id="add-ip-ok-button" onclick="FLSGroupAddEntitlement()">OK</button>';
	echo '<button class="button button-cancel" onclick="OnClickClosePopupDialog(\'#webea-prompt-dialog\')">Cancel</button>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
?>
<script>
var bIsDirty = false;
$(document).ready(function() {
    bIsDirty = true;
    OnLoad_DatePickerCtrls('config-fls-group-startdate|config-fls-group-startdate-img');
	OnLoad_DatePickerCtrls('config-fls-group-enddate|config-fls-group-enddate-img');
	UpdateEntitlementsXMLFromTable();
	isAdminSelection = $("[name=is-manager]");
	onSelectIsAdmin(isAdminSelection);
});
</script>