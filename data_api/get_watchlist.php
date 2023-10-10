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
	SafeStartSession();
	CheckAuthorisation();
	if  ( !isset($webea_page_parent_mainview) ||
	   isset($sWatchlistOptions) === false)
	{
	exit();
	}
	BuildOSLCConnectionString();
	$sOSLC_URL = $g_sOSLCString . 'wlcount/';
	$aWatchList = array();
	$sPostData = '';
	if ( !strIsEmpty($sWatchlistOptions) )
	{
	$aWLOptions = explode(';', $sWatchlistOptions);
	if ($aWLOptions)
	{
	$sLoginGUID  = SafeGetInternalArrayParameter($_SESSION, 'login_guid');
	$sPostData .= 'useridentifier=' . $sLoginGUID . ';';
	foreach ($aWLOptions as $sWLOption)
	{
	$sWLOption = trim($sWLOption);
	if ( !strIsEmpty($sWLOption) )
	{
	list($sOptionName, $sOptionValue) = explode('=', $sWLOption);
	$sOptionName = trim($sOptionName);
	$sOptionValue = trim($sOptionValue);
	if ($sOptionName==='period')
	{
	$sPostData .= $sOptionName .'=' . $sOptionValue . ';';
	}
	elseif (strIsTrue($sOptionValue))
	{
	if ( $sOptionName === 'recentdiscuss' )
	$sPostData .= 'recentdiscussions=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'recentdiag' )
	$sPostData .= 'recentdiagrams=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'recentelem' )
	$sPostData .= 'recentelements=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'recentreview' )
	$sPostData .= 'recentreviews=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'resallocactive' )
	$sPostData .= 'activeresourceallocations=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'resalloctoday' )
	$sPostData .= 'duetodayresourceallocations=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'resallocoverdue' )
	$sPostData .= 'overdueresourceallocations=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'testrecentpass' )
	$sPostData .= 'recentpassedtests=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'testrecentfail' )
	$sPostData .= 'recentfailedtests=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'testrecentdefer' )
	$sPostData .= 'recentdeferredtests=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'testrecentnotchk' )
	$sPostData .= 'recentnotcheckedtests=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'testnotrun' )
	$sPostData .= 'notruntests=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'featureverified' )
	$sPostData .= 'verifiedmfeatures=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'featurerequested' )
	$sPostData .= 'recentrequestedmfeatures=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'featurecompleted' )
	$sPostData .= 'recentcompletedmfeatures=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'featurenew' )
	$sPostData .= 'recentmfeatures=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'featureincomplete' )
	$sPostData .= 'incompletemfeatures=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'changeverified' )
	$sPostData .= 'verifiedchanges=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'changerequested' )
	$sPostData .= 'recentrequestedchanges=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'changecompleted' )
	$sPostData .= 'recentcompletedchanges=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'changenew' )
	$sPostData .= 'recentchanges=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'changeincomplete' )
	$sPostData .= 'incompletechanges=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'documentverified' )
	$sPostData .= 'verifieddocuments=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'documentrequested' )
	$sPostData .= 'recentrequesteddocuments=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'documentcompleted' )
	$sPostData .= 'recentcompleteddocuments=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'documentnew' )
	$sPostData .= 'recentdocuments=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'documentincomplete' )
	$sPostData .= 'incompletedocuments=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'defectverified' )
	$sPostData .= 'verifieddefects=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'defectrequested' )
	$sPostData .= 'recentrequesteddefects=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'defectcompleted' )
	$sPostData .= 'recentcompleteddefects=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'defectnew' )
	$sPostData .= 'recentdefects=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'defectincomplete' )
	$sPostData .= 'incompletedefects=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'issueverified' )
	$sPostData .= 'verifiedissues=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'issuerequested' )
	$sPostData .= 'recentrequestedissues=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'issuecompleted' )
	$sPostData .= 'recentcompletedissues=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'issuenew' )
	$sPostData .= 'recentissues=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'issueincomplete' )
	$sPostData .= 'incompleteissues=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'taskverified' )
	$sPostData .= 'verifiedtasks=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'taskrequested' )
	$sPostData .= 'recentrequestedtasks=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'taskcompleted' )
	$sPostData .= 'recentcompletedtasks=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'tasknew' )
	$sPostData .= 'recenttasks=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'taskincomplete' )
	$sPostData .= 'incompletetasks=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'eventrequested' )
	$sPostData .= 'recentrequestedevents=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'eventcompleted' )
	$sPostData .= 'recentcompletedevents=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'eventnew' )
	$sPostData .= 'recentevents=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'eventincomplete' )
	$sPostData .= 'incompleteevents=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'decisionverified' )
	$sPostData .= 'verifieddecisions=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'decisionrequested' )
	$sPostData .= 'recentrequesteddecisions=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'decisioncompleted' )
	$sPostData .= 'recentcompleteddecisions=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'decisionnew' )
	$sPostData .= 'recentdecisions=' . $sOptionValue . ';';
	elseif ( $sOptionName === 'decisionincomplete' )
	$sPostData .= 'incompletedecisions=' . $sOptionValue . ';';
	}
	}
	}
	$xmlRespDoc = null;
	$xmlRespDoc = HTTPPostXML($sOSLC_URL, $sPostData);
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	if ($xmlRespDoc != null)
	{
	$xnRoot = $xmlRespDoc->documentElement;
	$xnObj = GetXMLFirstChild($xnRoot);
	if ($xnObj != null && $xnObj->childNodes != null)
	{
	foreach ($xnObj->childNodes as $xnObjProp)
	{
	GetXMLNodeValue($xnObjProp, 'ss:recentdiscussions', $aWatchList['recentdiscuss']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdiagrams', $aWatchList['recentdiag']);
	GetXMLNodeValue($xnObjProp, 'ss:recentelements', $aWatchList['recentelem']);
	GetXMLNodeValue($xnObjProp, 'ss:recentreviews', $aWatchList['recentreview']);
	GetXMLNodeValue($xnObjProp, 'ss:activeresourceallocations', $aWatchList['resallocactive']);
	GetXMLNodeValue($xnObjProp, 'ss:duetodayresourceallocations', $aWatchList['resalloctoday']);
	GetXMLNodeValue($xnObjProp, 'ss:overdueresourceallocations', $aWatchList['resallocoverdue']);
	GetXMLNodeValue($xnObjProp, 'ss:recentpassedtests', $aWatchList['testrecentpass']);
	GetXMLNodeValue($xnObjProp, 'ss:recentfailedtests', $aWatchList['testrecentfail']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdeferredtests', $aWatchList['testrecentdefer']);
	GetXMLNodeValue($xnObjProp, 'ss:recentnotcheckedtests', $aWatchList['testrecentnotchk']);
	GetXMLNodeValue($xnObjProp, 'ss:notruntests', $aWatchList['testnotrun']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedmfeatures', $aWatchList['featureverified']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedmfeatures', $aWatchList['featurerequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedmfeatures', $aWatchList['featurecompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recentmfeatures', $aWatchList['featurenew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletemfeatures', $aWatchList['featureincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedchanges', $aWatchList['changeverified']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedchanges', $aWatchList['changerequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedchanges', $aWatchList['changecompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recentchanges', $aWatchList['changenew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletechanges', $aWatchList['changeincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:verifieddocuments', $aWatchList['documentverified']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequesteddocuments', $aWatchList['documentrequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompleteddocuments', $aWatchList['documentcompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdocuments', $aWatchList['documentnew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletedocuments', $aWatchList['documentincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:verifieddefects', $aWatchList['defectverified']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequesteddefects', $aWatchList['defectrequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompleteddefects', $aWatchList['defectcompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdefects', $aWatchList['defectnew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletedefects', $aWatchList['defectincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedissues', $aWatchList['issueverified']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedissues', $aWatchList['issuerequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedissues', $aWatchList['issuecompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recentissues', $aWatchList['issuenew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompleteissues', $aWatchList['issueincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedtasks', $aWatchList['taskverified']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedtasks', $aWatchList['taskrequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedtasks', $aWatchList['taskcompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recenttasks', $aWatchList['tasknew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletetasks', $aWatchList['taskincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedevents', $aWatchList['eventrequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedevents', $aWatchList['eventcompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recentevents', $aWatchList['eventnew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompleteevents', $aWatchList['eventincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:verifieddecisions', $aWatchList['decisionverified']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequesteddecisions', $aWatchList['decisionrequested']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompleteddecisions', $aWatchList['decisioncompleted']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdecisions', $aWatchList['decisionnew']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletedecisions', $aWatchList['decisionincomplete']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdiscussionsclause', $aWatchList['recentdiscusscrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentreviewsclause', $aWatchList['recentreviewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdiagramsclause', $aWatchList['recentdiagcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentelementsclause', $aWatchList['recentelemcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:activeresourceallocationsclause', $aWatchList['resallocactivecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:duetodayresourceallocationsclause', $aWatchList['resalloctodaycrit']);
	GetXMLNodeValue($xnObjProp, 'ss:overdueresourceallocationsclause', $aWatchList['resallocoverduecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentpassedtestsclause', $aWatchList['testrecentpasscrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentfailedtestsclause', $aWatchList['testrecentfailcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdeferredtestsclause', $aWatchList['testrecentdefercrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentnotcheckedtestsclause', $aWatchList['testrecentnotchkcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:notruntestsclause', $aWatchList['testnotruncrit']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedmfeaturesclause', $aWatchList['featureverifiedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedmfeaturesclause', $aWatchList['featurerequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedmfeaturesclause', $aWatchList['featurecompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentmfeaturesclause', $aWatchList['featurenewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletemfeaturesclause', $aWatchList['featureincompletecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedchangesclause', $aWatchList['changeverifiedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedchangesclause', $aWatchList['changerequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedchangesclause', $aWatchList['changecompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentchangesclause', $aWatchList['changenewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletechangesclause', $aWatchList['changeincompletecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:verifieddocumentsclause', $aWatchList['documentverifiedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequesteddocumentsclause', $aWatchList['documentrequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompleteddocumentsclause', $aWatchList['documentcompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdocumentsclause', $aWatchList['documentnewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletedocumentsclause', $aWatchList['documentincompletecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:verifieddefectsclause', $aWatchList['defectverifiedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequesteddefectsclause', $aWatchList['defectrequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompleteddefectsclause', $aWatchList['defectcompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdefectsclause', $aWatchList['defectnewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletedefectsclause', $aWatchList['defectincompletecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedissuesclause', $aWatchList['issueverifiedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedissuesclause', $aWatchList['issuerequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedissuesclause', $aWatchList['issuecompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentissuesclause', $aWatchList['issuenewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompleteissuesclause', $aWatchList['issueincompletecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:verifiedtasksclause', $aWatchList['taskverifiedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedtasksclause', $aWatchList['taskrequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedtasksclause', $aWatchList['taskcompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recenttasksclause', $aWatchList['tasknewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletetasksclause', $aWatchList['taskincompletecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequestedeventsclause', $aWatchList['eventrequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompletedeventsclause', $aWatchList['eventcompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recenteventsclause', $aWatchList['eventnewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompleteeventsclause', $aWatchList['eventincompletecrit']);
	GetXMLNodeValue($xnObjProp, 'ss:verifieddecisionsclause', $aWatchList['decisionverifiedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentrequesteddecisionsclause', $aWatchList['decisionrequestedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentcompleteddecisionsclause', $aWatchList['decisioncompletedcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:recentdecisionsclause', $aWatchList['decisionnewcrit']);
	GetXMLNodeValue($xnObjProp, 'ss:incompletedecisionsclause', $aWatchList['decisionincompletecrit']);
	}
	}
	}
	}
	}
	}
?>