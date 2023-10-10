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
	CheckAuthorisation();
	$sObjectGUID 	= SafeGetInternalArrayParameter($_POST, 'objectguid');
	$filedata = $sObjectGUID;
	$webea_page_parent_mainview = true;
	$sResourceFeaturesFilter ='';
	$aCommonProps 	= array();
	$aAttributes 	= array();
	$aOperations 	= array();
	$aFiles 	= array();
	$aConstraints 	= array();
	$aTaggedValues 	= array();
	$aExternalData 	= array();
	$aDiscussions 	= array();
	$aReviewDiscuss = array();
	$aReviewDiagrams = array();
	$aReviewNoDiscuss = array();
	$aTests 	= array();
	$aResAllocs 	= array();
	$aScenarios 	= array();
	$aRequirements 	= array();
	$aRunStates 	= array();
	$aUsages	 	= array();
	$aDocument	 	= array();
	$aParentInfo	= array();
	$aRelationships = array();
	$aFeatures	= array();
	$aChanges 	= array();
	$aDocuments 	= array();
	$aDefects 	= array();
	$aIssues 	= array();
	$aTasks 	= array();
	$aEvents 	= array();
	$aDecisions 	= array();
	$aRisks 	= array();
	$aEfforts 	= array();
	$aMetrics 	= array();
	$bIncludeArtifacts = true;
	include('./data_api/get_properties.php');
	$sObjectName = SafeGetArrayItem1Dim($aCommonProps, 'name');
	$sDocContent = SafeGetArrayItem1Dim($aDocument, 'content');
	WriteVideoPlayer($sObjectName, $sDocContent);
?>