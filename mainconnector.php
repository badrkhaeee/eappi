<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	if  ( !isset($webea_page_parent_mainview) )
	{
	exit();
	}
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	require_once __DIR__ . '/htmlpurifier.php';
	SafeStartSession();
	CheckAuthorisation();
	$aCommonProps 	= array();
	$aSourceProps 	= array();
	$aTargetProps 	= array();
	include('./data_api/get_connectorproperties.php');
	if ($aCommonProps['guid'] === null)
	{
	echo '<div id="main-content-empty">';
	echo _glt('NoConnector') . g_csHTTPNewLine . g_csHTTPNewLine;
	echo '<a href="javascript:MoveToPrevItemInNavigationHistory()">' . _glt('Back') . '</a>';
	echo '</div>';
	exit();
	}
	$sConnectorName 	= SafeGetArrayItem1Dim($aCommonProps, 'name');
	$sConnectorType 	= SafeGetArrayItem1Dim($aCommonProps, 'type');
	$sConnectorStereotype = SafeGetArrayItem1Dim($aCommonProps, 'stereotype');
	$aConnectorStereotypes = SafeGetArrayItem1Dim($aCommonProps, 'stereotypes');
	$sConnectorGUID 	= SafeGetArrayItem1Dim($aCommonProps, 'guid');
	$sConnectorAlias 	= SafeGetArrayItem1Dim($aCommonProps, 'alias');
	$sConnectorNotes 	= SafeGetArrayItem1Dim($aCommonProps, 'notes');
	$sConnectorDirection = SafeGetArrayItem1Dim($aCommonProps, 'direction');
	$sConnectorImageURL	= SafeGetArrayItem1Dim($aCommonProps, 'imageurl');
	echo '<div class="main-connector-details">';
	echo '<div id="object-main-details">';
	echo '<div class="object-image">';
	echo '<img src="' . _h($sConnectorImageURL) . '" alt="" title="' . _h($sConnectorType) . '" height="64" width="64"/>';
	echo '</div>';
	echo '<div class="object-name">';
	$sStereoHTML = buildStereotypeDisplayHTML($sConnectorStereotype, $aConnectorStereotypes, false);
	if ( !strIsEmpty($sStereoHTML) )
	{
	echo ( '&nbsp;' . $sStereoHTML . '&nbsp;' );
	}
	echo _h($sConnectorType) . ' connector ' . _h($sConnectorName);
	echo '</div>';
	$sSrcObjName 	= SafeGetArrayItem1Dim($aSourceProps, 'name');
	$sSrcObjType 	= SafeGetArrayItem1Dim($aSourceProps, 'type');
	$sSrcObjResType 	= SafeGetArrayItem1Dim($aSourceProps, 'restype');
	$sSrcObjImageURL	= SafeGetArrayItem1Dim($aSourceProps, 'imageurl');
	$sSrcObjGUID 	= SafeGetArrayItem1Dim($aSourceProps, 'guid');
	$sTrgtObjName 	= SafeGetArrayItem1Dim($aTargetProps, 'name');
	$sTrgtObjType 	= SafeGetArrayItem1Dim($aTargetProps, 'type');
	$sTrgtObjResType 	= SafeGetArrayItem1Dim($aTargetProps, 'restype');
	$sTrgtObjImageURL	= SafeGetArrayItem1Dim($aTargetProps, 'imageurl');
	$sTrgtObjGUID 	= SafeGetArrayItem1Dim($aTargetProps, 'guid');
	$sSrcObjName	= GetPlainDisplayName($sSrcObjName);
	$sTrgtObjName 	= GetPlainDisplayName($sTrgtObjName);
	echo '<div class="object-line2">';
	echo _glt('from') . '&nbsp;&nbsp;';
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sSrcObjGUID) . '\',\'false\',\'\',\'\',\'' . _j($sSrcObjName) . '\',\'' . _j($sSrcObjImageURL) . '\')">';
	echo '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sSrcObjImageURL) . '" alt="" title="' . _h($sSrcObjResType) . '">&nbsp;' . _h($sSrcObjName) . '</a>';
	echo '&nbsp;&nbsp;' . _glt('to') . '&nbsp;&nbsp;';
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sTrgtObjGUID) . '\',\'false\',\'\',\'\',\'' . _j($sTrgtObjName) . '\',\'' . _j($sTrgtObjImageURL) . '\')">';
	echo '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sTrgtObjImageURL) . '" alt="" title="' . _h($sTrgtObjResType) . '">&nbsp;' . _h($sTrgtObjName) . '</a>';
	echo '</div>';
	echo '</div>';
	if ( !strIsEmpty($sConnectorNotes) )
	{
	echo '<div class="connector-props-section">';
	echo '<div class="connector-props-header">Notes</div>';
	echo '<div class="notes-note">' . _hRichText($sConnectorNotes) . '</div>';
	echo '</div>' . PHP_EOL;
	}
	if ((isset($aInformationItems)) && (!empty($aInformationItems)))
	{
	echo '<div class="connector-props-section">';
	echo '<div class="connector-props-header">Information Items</div>';
	foreach ($aInformationItems as $aInformationItem)
	{
	$sName	= SafeGetArrayItem1Dim($aInformationItem, 'title');
	$sGUID 	= SafeGetArrayItem1Dim($aInformationItem, 'identifier');
	$sResType 	= SafeGetArrayItem1Dim($aInformationItem, 'resourcetype');
	$sImageURL	= SafeGetArrayItem1Dim($aInformationItem, 'imageurl');
	echo '<div class="object-link-container">';
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sGUID) . '\',\'true\',\'\',\'\',\'' . _j($sName) . '\',\'' . _j($sImageURL) . '\')">';
	echo '<img alt="" title="' . _h($sResType) . '" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '">';
	echo _h($sName);
	echo '</a>';
	echo '</div>';
	}
	echo '</div>';
	}
	$sSrcObjRole 	= SafeGetArrayItem1Dim($aCommonProps, 'sourcerole');
	$sSrcObjAlias 	= SafeGetArrayItem1Dim($aCommonProps, 'sourcealias');
	$sSrcObjStereotype	= SafeGetArrayItem1Dim($aCommonProps, 'sourcestereotype');
	$aSrcObjStereotypes	= SafeGetArrayItem1Dim($aCommonProps, 'sourcestereotypes');
	$sSrcObjRoleDesc	= SafeGetArrayItem1Dim($aCommonProps, 'sourceroledesc');
	$sSrcObjMultiplicity= SafeGetArrayItem1Dim($aCommonProps, 'sourcemultiplicity');
	$sSrcObjOrdered	= SafeGetArrayItem1Dim($aCommonProps, 'sourceordered');
	$sSrcObjAccess	= SafeGetArrayItem1Dim($aCommonProps, 'sourceaccess');
	$sSrcObjAggregation	= SafeGetArrayItem1Dim($aCommonProps, 'sourceaggregation');
	$sSrcObjScope	= SafeGetArrayItem1Dim($aCommonProps, 'sourcescope');
	$sSrcObjMembertype	= SafeGetArrayItem1Dim($aCommonProps, 'sourcemembertype');
	$sSrcObjChangable	= SafeGetArrayItem1Dim($aCommonProps, 'sourcechangeable');
	$sSrcObjContainment = SafeGetArrayItem1Dim($aCommonProps, 'sourcecontainment');
	$sSrcObjNavigability= SafeGetArrayItem1Dim($aCommonProps, 'sourcenavigability');
	$sSrcObjDerived	= SafeGetArrayItem1Dim($aCommonProps, 'sourcederived');
	$sSrcObjDerivedUnion= SafeGetArrayItem1Dim($aCommonProps, 'sourcederivedunion');
	$sSrcObjOwned	= SafeGetArrayItem1Dim($aCommonProps, 'sourceowned');
	$sSrcObjAllowDup	= SafeGetArrayItem1Dim($aCommonProps, 'sourceallowduplicates');
	$sTrgtObjRole 	= SafeGetArrayItem1Dim($aCommonProps, 'targetrole');
	$sTrgtObjAlias 	= SafeGetArrayItem1Dim($aCommonProps, 'targetalias');
	$sTrgtObjRoleDesc	= SafeGetArrayItem1Dim($aCommonProps, 'targetroledesc');
	$sTrgtObjStereotype	= SafeGetArrayItem1Dim($aCommonProps, 'targetstereotype');
	$aTrgtObjStereotypes = SafeGetArrayItem1Dim($aCommonProps, 'targetstereotypes');
	$sTrgtObjMultiplicity = SafeGetArrayItem1Dim($aCommonProps, 'targetmultiplicity');
	$sTrgtObjOrdered	= SafeGetArrayItem1Dim($aCommonProps, 'targetordered');
	$sTrgtObjAccess	= SafeGetArrayItem1Dim($aCommonProps, 'targetaccess');
	$sTrgtObjAggregation= SafeGetArrayItem1Dim($aCommonProps, 'targetaggregation');
	$sTrgtObjScope	= SafeGetArrayItem1Dim($aCommonProps, 'targetscope');
	$sTrgtObjMembertype	= SafeGetArrayItem1Dim($aCommonProps, 'targetmembertype');
	$sTrgtObjChangable	= SafeGetArrayItem1Dim($aCommonProps, 'targetchangeable');
	$sTrgtObjContainment= SafeGetArrayItem1Dim($aCommonProps, 'targetcontainment');
	$sTrgtObjNavigability= SafeGetArrayItem1Dim($aCommonProps, 'targetnavigability');
	$sTrgtObjDerived	= SafeGetArrayItem1Dim($aCommonProps, 'targetderived');
	$sTrgtObjDerivedUnion= SafeGetArrayItem1Dim($aCommonProps, 'targetderivedunion');
	$sTrgtObjOwned	= SafeGetArrayItem1Dim($aCommonProps, 'targetowned');
	$sTrgtObjAllowDup	= SafeGetArrayItem1Dim($aCommonProps, 'targetallowduplicates');
	echo '<div class="connector-extend-prop-table-div">';
	echo '<table class="connector-extend-prop-table">';
	echo '<tr><th>&nbsp;</th><th>' . _glt('Source') . '</th><th>' . _glt('Target') . '</th></tr>';
	echo '<tr><td class="w3-grey-text">' . _glt('Role') . '</td><td class="cell-lrborders">' . _h($sSrcObjRole) . '</td><td>' . _h($sTrgtObjRole) . '</td></tr>';
	echo '<tr><td class="w3-grey-text">' . _glt('Comment') . '</td><td class="cell-lrborders">' . _h($sSrcObjRoleDesc) . '</td><td>' . _h($sTrgtObjRoleDesc) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-subheader" colspan="3">' . _glt('Multiplicity') . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Multiplicity') . '</td><td class="cell-lrborders">' . _h($sSrcObjMultiplicity) . '</td><td>' . _h($sTrgtObjMultiplicity) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Ordered') . '</td><td class="cell-lrborders">' . _h(ConvertBoolToText($sSrcObjOrdered)) . '</td><td>' . _h(ConvertBoolToText($sTrgtObjOrdered)) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Allow duplicates') . '</td><td class="cell-lrborders">' . _h(ConvertBoolToText($sSrcObjAllowDup)) . '</td><td>' . _h(ConvertBoolToText($sTrgtObjAllowDup)) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-subheader" colspan="3">' . _glt('Details') . '</td></tr>';
	$sSrcObjStereoHTML 	= buildStereotypeDisplayHTML($sSrcObjStereotype, $aSrcObjStereotypes, false);
	$sTrgtObjStereoHTML = buildStereotypeDisplayHTML($sTrgtObjStereotype, $aTrgtObjStereotypes, false);
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Stereotype') . '</td><td class="cell-lrborders">' . $sSrcObjStereoHTML . '</td><td>' . $sTrgtObjStereoHTML . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Alias') . '</td><td class="cell-lrborders">' . _h($sSrcObjAlias) . '</td><td>' . _h($sTrgtObjAlias) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Access') . '</td><td class="cell-lrborders">' . _h($sSrcObjAccess) . '</td><td>' . _h($sTrgtObjAccess) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Navigability') . '</td><td class="cell-lrborders">' . _h($sSrcObjNavigability) . '</td><td>' . _h($sTrgtObjNavigability) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Aggregation') . '</td><td class="cell-lrborders">' . _h($sSrcObjAggregation) . '</td><td>' . _h($sTrgtObjAggregation) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Scope') . '</td><td class="cell-lrborders">' . _h($sSrcObjScope) . '</td><td>' . _h($sTrgtObjScope) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-subheader" colspan="3">' . _glt('Advanced') . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Member type') . '</td><td class="cell-lrborders">' . _h($sSrcObjMembertype) . '</td><td>' . _h($sTrgtObjMembertype) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Changable') . '</td><td class="cell-lrborders">' . _h($sSrcObjChangable) . '</td><td>' . _h($sTrgtObjChangable) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Containment') . '</td><td class="cell-lrborders">' . _h($sSrcObjContainment) . '</td><td>' . _h($sTrgtObjContainment) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Derived') . '</td><td class="cell-lrborders">' . _h(ConvertBoolToText($sSrcObjDerived)) . '</td><td>' . _h(ConvertBoolToText($sTrgtObjDerived)) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Derived Union') . '</td><td class="cell-lrborders">' . _h(ConvertBoolToText($sSrcObjDerivedUnion)) . '</td><td>' . _h(ConvertBoolToText($sTrgtObjDerivedUnion)) . '</td></tr>';
	echo '<tr><td class="connector-extend-prop-table-firstcol w3-grey-text">' . _glt('Owned') . '</td><td class="cell-lrborders">' . _h(ConvertBoolToText($sSrcObjOwned)) . '</td><td>' . _h(ConvertBoolToText($sTrgtObjOwned)) . '</td></tr>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
?>