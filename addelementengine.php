<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	SafeStartSession();
	AllowedMethods('POST');
	CheckAuthorisation();
	$sName  	= SafeGetInternalArrayParameter($_POST, 'name');
	$sElementType 	= SafeGetInternalArrayParameter($_POST, 'elementtype');
	$sParentGUID 	= SafeGetInternalArrayParameter($_POST, 'parentguid');
	$sParentName	= SafeGetInternalArrayParameter($_POST, 'parentname');
	$sStereotype	= SafeGetInternalArrayParameter($_POST, 'stereotype');
	$sNotes 	= SafeGetInternalArrayParameter($_POST, 'notes');
	$sKeywords 	= SafeGetInternalArrayParameter($_POST, 'keywords');
	$sAlias 	= SafeGetInternalArrayParameter($_POST, 'alias');
	$sStatus 	= SafeGetInternalArrayParameter($_POST, 'status');
	$sComplexity	= SafeGetInternalArrayParameter($_POST, 'complexity');
	$sPhase 	= SafeGetInternalArrayParameter($_POST, 'phase');
	$sVersion 	= SafeGetInternalArrayParameter($_POST, 'version');
	$sDiagramType 	= SafeGetInternalArrayParameter($_POST, 'diagramtype');
	$sAuthor 	= SafeGetInternalArrayParameter($_SESSION, 'login_fullname');
	$sLoginGUID 	= SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	$sMissingField = '';
	if (strIsEmpty($sElementType))
	$sMissingField .= _glt('Object Type') . ', ';
	if (strIsEmpty($sName))
	$sMissingField .= _glt('Name') . ', ';
	if (strIsEmpty($sParentGUID))
	$sMissingField .= _glt('Container') . ', ';
	if ( $sElementType==='diagram' && strIsEmpty($sDiagramType) )
	$sMissingField .= _glt('Diagram type') . ', ';
	if ( $sElementType==='element' && strIsEmpty($sElementType) )
	$sMissingField .= _glt('Element type') . ', ';
	$sElementType = mb_ucfirst($sElementType);
	if ($sElementType==='Usecase')
	$sElementType = 'UseCase';
	if (!strIsEmpty($sMissingField))
	{
	$sMissingField = substr($sMissingField, 0, strlen($sMissingField)-2 );
	$sErrorMsg = str_replace('%FIELDS%', $sMissingField, _glt('The mandatory fields missing'));
	setResponseCode(400, $sErrorMsg);
	return $sErrorMsg;
	}
	$sReturn = '';
	$sNotes	= ConvertHTMLToEANote($sNotes);
	$xmlRespDoc = null;
	include('./data_api/add_element.php');
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	$sSuccessAttrib = GetOSLCSuccess($xmlRespDoc);
	if (strpos($sSuccessAttrib, '/oslc/am/completeresource/')!==false ||
	strpos($sSuccessAttrib, '/oslc/am/resource/')!==false)
	{
	$sName	= _h($sName);
	$sParentName	= _h($sParentName);
	$sType = $sElementType;
	if ( $sElementType === 'diagram')
	$sType = 'Diagram';
	elseif ( $sElementType === 'package')
	$sType = 'Package';
	$sReturn = 'success: ' . str_replace(array('%OBJTYPE%','%OBJNAME%','%PARENT%'), array($sType, $sName, $sParentName), _glt('Object added'));
	}
	}
	else
	{
	$sReturn = $sOSLCErrorMsg;
	}
	echo $sReturn;
?>