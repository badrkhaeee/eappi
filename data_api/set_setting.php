<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once __DIR__ . '/../security.php';
	require_once __DIR__ . '/../globals.php';
	$sVariableName = SafeGetInternalArrayParameter($_GET, 'varname');
	$sVariableValue = SafeGetInternalArrayParameter($_GET, 'varval');
	$sVarArray = SafeGetInternalArrayParameter($_GET, 'json_var_array');
	if(strIsEmpty($sVarArray))
	{
	$aSettings = [[$sVariableName, $sVariableValue]];
	}
	else
	{
	$aSettings = json_decode($sVarArray);
	}
	SafeStartSession();
	foreach ($aSettings as $aSetting)
	{
	$sVariableName = $aSetting[0];
	$sVariableValue = $aSetting[1];
	if (!in_array($sVariableName,
	array(
	'diagramlayout',
	'ios_scroll',
	'mainlayout',
	'propertylayout',
	'review_session',
	'review_session_name',
	'selected_features',
	'show_browser',
	'show_propertiesview',
	'show_system_output',
	'feature_order',
	'filter_properties',
	'mail_preview',
	'propsview_hide_empty',
	'search_settings',
	'add_object_selection',
	'diagram_zoom',
	'chat_name',
	'chat_type',
	'chat_monitor',
	'chat_days',
	'chat_expanded_groups',
	'comment_history_timeframe',
	'discussion_history_timeframe',
	'review_history_timeframe'
	)))
	{
	echo "Invalid parameter: " . _h($sVariableName);
	http_response_code(404);
	exit();
	}
	$_SESSION[$sVariableName] = $sVariableValue;
	$aCookieVariables = [
	'selected_features',
	'feature_order',
	'propsview_hide_empty',
	'search_settings',
	'add_object_selection'
	];
	if (in_array($sVariableName, $aCookieVariables))
	{
	$iRetentionDays = (int)SafeGetInternalArrayParameter($_SESSION, 'cookie_retention', '365');
	setcookie($sVariableName, $sVariableValue, time() + (86400 * $iRetentionDays), '/', null, null, true);
	}
	}
?>