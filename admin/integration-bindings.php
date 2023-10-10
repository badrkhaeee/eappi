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
	$sGUID = SafeGetInternalArrayParameter($_POST, 'id','');
	$aDataPath = ['model-connections','model-connection'];
	$aModelConnections = GetPostResults($sPCS_URL.'/config/getmodelconnections/','', $sError, $aDataPath, true);
	$sError = '';
	$sPostBody = '';
	$sPostBody .= '<sbpi-provider>';
	$sPostBody .= '<plugin>' . $sGUID . '</plugin>';
	$sPostBody .= '</sbpi-provider>';
	$aDataPath = ['sbpiconfiguration','sbpiprovider'];
	$aSbpiprovider = GetPostResults($sPCS_URL.'/config/getsbpiprovider/', $sPostBody, $sError, $aDataPath);
	$sHeaderText = 'Edit Bindings';
	$sPostBody = '';
	$sPostBody .= '<sbpi-bindings>';
	$sPostBody .= '<plugin>' . $sGUID . '</plugin>';
	$sPostBody .= '</sbpi-bindings>';
	$aDataPath = ['sbpiconfiguration','sbpibindings','sbpibinding'];
	$aSbpibindings = GetBindings($sPCS_URL, $sPostBody, $sError,  $aDataPath);
	WriteBreadcrumb($sHeaderText, 'model_repository/webconfig_edit_model_bindings.html');
	echo '<form id="config-save-sbpi-bindings-form" role="form" onsubmit="onFormSubmit(event, \'#config-save-sbpi-bindings-form\', \'savesbpibindings\')">';
	WriteHeading('Provider');
	echo '<div class="config-section">';
	WriteTextField(SafeGetArrayItem1Dim($aSbpiprovider, 'guid'),'','textfield-small','hidden name="guid"');
	echo '<div class="config-line">';
	WriteLabel('Provider Name');
	WriteValue(SafeGetArrayItem1Dim($aSbpiprovider, 'name'),'','textvalue-large');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Provider Type');
	WriteValue(SafeGetArrayItem1Dim($aSbpiprovider, 'typename'),'','textvalue-large');
	echo '</div>';
	echo '</div>';
	WriteHeading('Model Bindings');
	echo '<div class="config-section">';
	$i = 0;
	$bNoProModels = true;
	foreach ($aModelConnections as $aModel)
	{
	if (SafeGetArrayItem1Dim($aModel, 'pro-features-enabled') === 'True')
	{
	$bNoProModels = false;
	$bBindingEnabled = 'false';
	$sModelAlias = SafeGetArrayItem1Dim($aModel, 'alias');
	$sBindingID = '';
	if (is_array($aSbpibindings))
	{
	foreach ($aSbpibindings as $aBinding)
	{
	$sBindingAlias = SafeGetArrayItem1Dim($aBinding, 'dbalias');
	if ($sModelAlias === $sBindingAlias)
	{
	$bBindingEnabled = 'true';
	$sBindingID = SafeGetArrayItem1Dim($aBinding, 'bindingid');
	}
	}
	}
	$i++;
	$sBindingName = 'name = "BINDING_'. $i . '"';
	$sBindingValue = 'value="'.htmlspecialchars($sModelAlias) . '"';
	$sBindingAttributes = $sBindingName . ' ' . $sBindingValue;
	echo '<div class="config-line">';
	WriteBindingCheckBox($sModelAlias ,'','',$sBindingAttributes,$bBindingEnabled);
	echo '</div>';
	}
	}
	if ($bNoProModels)
	{
	WriteLabel(htmlspecialchars('<There are no Model Connections with Pro Features enabled>'),'','label-large');
	}
	WriteLabel('<br>Note: Bindings can only be applied to Model Connections which have Pro Features Enabled','','label-large');
	echo '</div>';
	WriteButton('OK','','button button-ok','type="submit"');
	WriteButton('Cancel','','button button-cancel','type="button"  onclick="loadConfigPage(\'integrations.php\')"');
	echo '</form>';
	function WriteBindingCheckBox($sText, $sID = '', $sClass = '', $sAttributes = '', $bIsChecked = false, $bCheckboxFirst = false)
	{
	$sChecked = '';
	if (($bIsChecked === 'true') || ($bIsChecked === 't') || ($bIsChecked === 'True')|| ($bIsChecked === '1'))
	$sChecked = 'checked';
	$sName = mb_strtolower($sText);
	$sName= str_replace(' ', '-', $sName);
	$sName= str_replace('(', '', $sName);
	$sName= str_replace(')', '', $sName);
	$sName = $sName . '_checkbox';
	if ($bCheckboxFirst === true)
	{
	echo '<input type="checkbox" title="'.$sText.'" '.$sAttributes . ' ' .  $sChecked.'> '.$sText;
	}
	else
	{
	WriteLabel($sText);
	echo '<input type="checkbox" title="'.$sText.'" '.$sAttributes. ' ' .$sChecked.'>';
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