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
	if  ( !isset($webea_page_parent_mainview) )
	{
	AllowedMethods('POST');
	}
	SafeStartSession();
	CheckAuthorisation();
	if ( isset($sObjectGUID)===false )
	{
	$sObjectGUID = SafeGetInternalArrayParameter($_POST, 'objectguid');
	$sResType = SafeGetInternalArrayParameter($_POST, 'restype');
	$sLinkType = SafeGetInternalArrayParameter($_POST, 'linktype');
	$sObjectHyper = SafeGetInternalArrayParameter($_POST, 'hyper');
	}
	$g_sViewingMode	= '3';
	$aCommonProps 	= array();
	$aAttributes 	= array();
	$aOperations 	= array();
	$aConstraints 	= array();
	$aTaggedValues 	= array();
	$aExternalData 	= array();
	$aDiscussions 	= array();
	$aComments 	= array();
	$aReviewDiscuss = array();
	$aReviewDiagrams = array();
	$aReviewNoDiscuss = array();
	$aTests 	= array();
	$aResAllocs 	= array();
	$aScenarios 	= array();
	$aFiles	 	= array();
	$aRequirements 	= array();
	$aRunStates 	= array();
	$aUsages	 	= array();
	$aDocument	 	= array();
	$aParentInfo	= array();
	$aRelationships = array();
	$aFeatures	= array();
	$aChanges 	= array();
	$aDocuments	= array();
	$aDefects 	= array();
	$aIssues 	= array();
	$aTasks 	= array();
	$aEvents 	= array();
	$aDecisions 	= array();
	$aRisks 	= array();
	$aEfforts 	= array();
	$aMetrics 	= array();
	if ($sResType === 'Diagram')
	{
	$sResourceFeaturesFilter = 'dip,dr';
	}
	else
	{
	$sResourceFeaturesFilter = '';
	}
	include('./data_api/get_properties.php');
	include('propertysections.php');
	$sMainClass = '';
	$sMainClassEx = '';
	if (IsSessionSettingTrue('show_browser'))
	{
	$sMainClass = ' show-browser';
	$sMainClassEx = ' class="show-browser"';
	}
	$sOSLCErrorMsg = BuildOSLCErrorString();
	if ( strIsEmpty($sOSLCErrorMsg) )
	{
	if (SafeGetArrayItem1Dim($aCommonProps, 'type') === 'ModelRoot' || SafeGetArrayItem1Dim($aCommonProps, 'guid') === '')
	{
	$sShowBrowserMinProps = 'class=" main-view '.GetShowBrowserMinPropsStyleClasses().'"';
	echo '<div id="properties-container" ' . $sShowBrowserMinProps . '>';
	echo '</div>';
	return;
	}
	$sPropertyLayoutNo = '1';
	if (isset($_SESSION['propertylayout']))
	{
	$sPropertyLayoutNo = $_SESSION['propertylayout'];
	}
	$sShowBrowserMinProps = 'class=" main-view '.GetShowBrowserMinPropsStyleClasses().'"';
	echo '<div id="properties-container" ' . $sShowBrowserMinProps . '>';
	echo '<div id="properties-main" class="properties-main' . _h($sPropertyLayoutNo) . _h($sMainClass) . '">';
	$sObjectGUID 	= SafeGetArrayItem1Dim($aCommonProps, 'guid');
	$sObjectAlias 	= SafeGetArrayItem1Dim($aCommonProps, 'alias');
	$sObjectName 	= SafeGetArrayItem1Dim($aCommonProps, 'name');
	$sObjectType 	= SafeGetArrayItem1Dim($aCommonProps, 'type');
	$sObjectResType = SafeGetArrayItem1Dim($aCommonProps, 'restype');
	$sObjectStereotype = SafeGetArrayItem1Dim($aCommonProps, 'stereotype');
	$aObjectStereotypes = SafeGetArrayItem1Dim($aCommonProps, 'stereotypes');
	$sObjImageURL	= SafeGetArrayItem1Dim($aCommonProps, 'imageurl');
	$sHasChildren	= SafeGetArrayItem1Dim($aCommonProps, 'haschild');
	$sObjectAuthor	= SafeGetArrayItem1Dim($aCommonProps, 'author');
	$sObjectModified= SafeGetArrayItem1Dim($aCommonProps, 'modified');
	$sObjectStatus	= SafeGetArrayItem1Dim($aCommonProps, 'status');
	$sObjectVersion	= SafeGetArrayItem1Dim($aCommonProps, 'version');
	$sObjectPhase	= SafeGetArrayItem1Dim($aCommonProps, 'phase');
	$sObjectNType	= SafeGetArrayItem1Dim($aCommonProps, 'ntype');
	$bObjLocked	= strIsTrue(SafeGetArrayItem1Dim($aCommonProps, 'locked'));
	$sObjLockedType	= SafeGetArrayItem1Dim($aCommonProps, 'lockedtype');
	$sNotes 	= SafeGetArrayItem1Dim($aCommonProps, 'notes');
	$sObjClassName 	= SafeGetArrayItem1Dim($aCommonProps, 'classname');
	$sObjClassGUID 	= SafeGetArrayItem1Dim($aCommonProps, 'classguid');
	$sObjClassImageURL	= SafeGetArrayItem1Dim($aCommonProps, 'classimageurl');
	if((strIsTrue($bObjLocked)) && ($sObjLockedType=== 'Security_RULTE_NoLock'))
	{
	$bObjLocked = false;
	}
	$sReviewStatus	= '';
	$sReviewStartDate = '';
	$sReviewEndDate	= '';
	$bIsReviewElement = false;
	if ( ($sObjectResType === 'Element' && $sObjectType === 'Artifact' && $sObjectStereotype === 'EAReview')  )
	{
	$bIsReviewElement = true;
	$sReviewStatus	= GetTaggedValue($aTaggedValues, 'Status', 'EAReview::Status');
	$sReviewStartDate = GetTaggedValue($aTaggedValues, 'StartDate', 'EAReview::StartDate');
	$sReviewEndDate	= GetTaggedValue($aTaggedValues, 'EndDate', 'EAReview::EndDate');
	}
	echo '<div id="object-main-details">';
	echo '<div class="object-image">';
	if ($sHasChildren === 'true' && $bObjLocked)
	{
	$sImageLink = '<img style="background:url(' . _h($sObjImageURL) . '); background-size:100%;" src="images/element64/lockedhaschildoverlay.png" alt="" title="' . _glt('View child objects for locked') . ' ' . _h($sObjectResType) . '" height="64" width="64"/>';
	}
	elseif ($sHasChildren === 'true' && !$bObjLocked )
	{
	if ($sMainClass !== ' show-browser')
	{
	$sImageLink = '<img style="background:url(' . _h($sObjImageURL) . '); background-size:100%;" src="images/element64/haschildoverlay.png" alt="" title="' . _glt('View child objects') . '" height="64" width="64"/>';
	}
	else
	{
	$sImageLink = '<img style="background:url(' . _h($sObjImageURL) . '); background-size:100%;" src="images/element64/haschildoverlay.png" alt="" height="64" width="64"/>';
	}
	}
	elseif ($sHasChildren === 'false' && $bObjLocked)
	{
	$sImageLink = '<img style="background:url(' . _h($sObjImageURL) . '); background-size:100%;" src="images/element64/lockedoverlay.png" alt="" title="' . _h($sObjectResType) . ' ' . _glt('is locked') . '" height="64" width="64"/>';
	}
	else
	{
	$sImageLink = '<img src="' . _h($sObjImageURL) . '" alt="" title="" height="64" width="64"/>';
	}
	if ( ($sObjectResType === 'ModelRoot' || $sObjectResType === 'Package' || $sHasChildren === 'true') && $sMainClass !== ' show-browser')
	{
	if ( $sObjectResType === 'Element' && $sHasChildren === 'true' )
	{
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'child\',\'\',\'' . _j($sObjectName) . '\', \'' . _j($aCommonProps['imageurl']) . '\')">';
	}
	else
	{
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sObjectGUID) . '\',\'true\',\'\',\'\',\'' . _j($sObjectName) . '\', \'' . _j($aCommonProps['imageurl']) . '\')">';
	}
	echo $sImageLink;
	echo '</a>';
	}
	elseif ( $sObjectResType === 'Diagram' )
	{
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'\',\'\',\'' . _j($sObjectName) . '\', \'' . _j($aCommonProps['imageurl']) . '\')">';
	echo $sImageLink;
	echo '</a>';
	}
	elseif ( $sObjectType === 'Artifact' && $sObjectStereotype === 'Image')
	{
	$sImageBin =  SafeGetArrayItem1Dim($aCommonProps, 'imagepreview');
	if ( !strIsEmpty($sImageBin) )
	{
	$sImageLink = '<div class="object-image-asset-div"><img class="object-image-asset" src="data:image/png;base64,' . _h($sImageBin) . '" alt="" title=""/></div>';
	echo $sImageLink;
	}
	}
	else
	{
	echo $sImageLink;
	}
	echo '</div>';
	if (IsHyperlink($sObjectType, $sObjectName, $sObjectNType))
	{
	$sDisplayName = $sObjectName;
	if ( !strIsEmpty($sObjectAlias) )
	{
	$sDisplayName = $sObjectAlias;
	}
	$sDisplayName 	= GetPlainDisplayName($sDisplayName);
	echo '<div class="object-name">' . _h($sDisplayName) . '</div>';
	echo '<div class="object-line2">';
	echo _glt('Hyperlink') . '&nbsp;';
	echo '</div>';
	echo '</div>';
	}
	elseif (ShouldIgnoreName($sObjectType, $sObjectName, $sObjectNType))
	{
	$sDisplayObjType = $sObjectType;
	if ( !strIsEmpty($sObjClassGUID) )
	{
	$sDisplayObjType  = '<a class="w3-link" onclick="LoadObject(\'' . _j($sObjClassGUID) . '\',\'false\',\'\',\'\',\'' . _j($sObjClassName) . '\',\'' . _j($sObjClassImageURL) . '\')">';
	$sDisplayObjType .= '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sObjClassImageURL) . '" alt="" style="float: none;">&nbsp;' . _h($sObjClassName) . '</a>';
	}
	else
	{
	$sDisplayObjType = _h(_glt($sDisplayObjType));
	}
	echo '<div class="object-name" title="' . _h(GetPlainDisplayName($sObjectName)) . '">' . _h(GetPlainDisplayName($sObjectName)) . '</div>';
	echo '<div class="object-line2" style="padding: 0 0 12px 84px;">';
	if(!strIsEmpty($sObjectAlias))
	{
	echo '<div class="object-alias">' . _h($sObjectAlias) . '</div>';
	}
	echo ( strIsEmpty($sDisplayObjType)?'': $sDisplayObjType . '&nbsp;');
	$sStereoHTML = buildStereotypeDisplayHTML($sObjectStereotype, $aObjectStereotypes, false);
	if ( !strIsEmpty($sStereoHTML) )
	{
	echo ( '&nbsp;' . $sStereoHTML . '&nbsp;&nbsp;' );
	}
	echo '</div>';
	echo '</div>';
	$sDocType = SafeGetArrayItem1Dim($aDocument, 'type');
	if ( $sDocType === 'MDOC_HTML_CACHE' || $sDocType === 'MDOC_HTML_EDOC1' || $sDocType === 'ExtDoc' )
	{
	echo '<div class="object-document">';
	if ( $sDocType === 'MDOC_HTML_EDOC1' || $sDocType === 'MDOC_HTML_CACHE' )
	{
	if ($sDocType === 'MDOC_HTML_EDOC1')
	{
	$sLoadObject = 'LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'encryptdoc\',\'\',\'' . _j($sObjectName) . '\', \'' . _j(AdjustImagePath($sObjImageURL, '16')) . '\')';
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName('images/element16/encrypteddoc.png') . '" src="images/spriteplaceholder.png" alt=""> ' . _glt('Linked Document') . ' ';
	echo '<input id="linked-document-pwd-field" placeholder="' . _glt('password') . '" type="password" onkeypress="return OnLinkDocPWDKeyDown(event,\''. _j($sObjectGUID) . '\',\'' . _j($sObjectName) . '\', \'' .  _j(AdjustImagePath($sObjImageURL, '16')) . '\')" value="">';
	}
	else
	{
	$sLoadObject = 'LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'document\',\'\',\'' . _j($sObjectName) . '\', \'' . _j(AdjustImagePath($sObjImageURL, '16')) . '\')';
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName('images/element16/document.png') . '" src="images/spriteplaceholder.png" alt=""> ' . _glt('Linked Document') . ' ';
	}
	echo '<button class="linked-document-open-button webea-main-styled-button" onclick="' . $sLoadObject. '">' . _glt('Open Document') . '</button> ';
	}
	elseif ( $sDocType === 'ExtDoc' )
	{
	$sDocContent = SafeGetArrayItem1Dim($aDocument, 'content');
	$sExtension = SafeGetArrayItem1Dim($aDocument, 'extension');
	$sFileName = $sObjectName;
	$iExtLength = strlen($sExtension);
	$sObjectNameEnd = substr($sObjectName, -$iExtLength);
	if($sExtension !== $sObjectNameEnd)
	{
	$sFileName = $sObjectName . $sExtension;
	}
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName('images/element16/document.png') . '" src="images/spriteplaceholder.png" alt=""> ' . _glt('Stored Document') . ' ';
	echo '<div class="artifact-buttons">';
	echo '<a onclick="DownloadFile(this)" guid="'.$sObjectGUID.'" download="' . _h($sFileName) . '"><button class="stored-document-download-button webea-main-styled-button">'  . _glt('Download') . '</button></a>';
	if( ($sExtension === '.mp4') ||
	($sExtension === '.avi') ||
	($sExtension === '.wmv') ||
	($sExtension === '.mov') ||
	($sExtension === '.webm'))
	{
	echo '<a><button class="stored-document-watch-video-button webea-main-styled-button" guid="'._h($sObjectGUID).'" onclick="ShowVideoDialog(this)">'  . _glt('Watch Video') . '</button></a>';
	}
	echo '</div>';
	}
	echo "</div>";
	}
	elseif ( $sObjectNType==='32' )
	{
	$sImageAssetGUID	= SafeGetArrayItem1Dim($aCommonProps, 'imageasset');
	if ( !strIsEmpty($sImageAssetGUID) )
	{
	echo '<div class="object-document">';
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName('images/element16/imageasset.png') . '" src="images/spriteplaceholder.png" alt=""> ' . _glt('Image Asset') . ' ';
	echo '<a href="data_api/dl_model_image.php?objectguid=' . _h($sImageAssetGUID) . '" download="' . _h($sObjectName) . '"><button class="stored-document-download-button webea-main-styled-button">'  . _glt('Download') . '</button></a>';
	echo '<a><button class="stored-document-view-image-button webea-main-styled-button" onclick="ShowImageViewerDialog()">'  . _glt('View Image') . '</button></a>';
	echo "</div>";
	WriteImageViewer($sObjectName, $sImageBin);
	}
	}
	}
	else
	{
	echo '</div>';
	}
	$aProps = array();
	if ($bIsReviewElement)
	array_push($aProps ,'summary');
	if (($sObjectStereotype === 'Decision') && ($sObjectType === 'Activity') ||
	(($sObjectStereotype === 'BusinessKnowledgeModel') && ($sObjectType === 'Activity')))
	{
	array_push($aProps ,'DMN Expression');
	}
	array_push($aProps ,'notes');
	if (IsSessionSettingTrue('prop_visible_location') )
	array_push($aProps ,'location');
	if (count($aTaggedValues)> 0 && IsSessionSettingTrue('prop_visible_taggedvalues') )
	array_push($aProps ,'tagged values');
	if (count($aRelationships)> 0 && IsSessionSettingTrue('prop_visible_relationships') )
	array_push($aProps ,'relationships');
	if (count($aAttributes)> 0 && IsSessionSettingTrue('prop_visible_attributes') )
	array_push($aProps ,'attributes');
	if (count($aOperations)> 0 && IsSessionSettingTrue('prop_visible_operations') )
	array_push($aProps ,'operations');
	if (count($aRunStates)> 0 && IsSessionSettingTrue('prop_visible_runstates') )
	array_push($aProps ,'run states');
	if ( count($aRequirements)>0 )
	array_push($aProps ,'requirements');
	if ( count($aConstraints)>0 )
	array_push($aProps ,'constraints');
	if ( count($aScenarios)>0 )
	array_push($aProps ,'scenarios');
	if ( count($aFiles)>0 && IsSessionSettingTrue('prop_visible_files'))
	array_push($aProps ,'files');
	if (count($aTests)> 0 && IsSessionSettingTrue('prop_visible_testing') )
	array_push($aProps ,'tests');
	if (count($aResAllocs)> 0 && IsSessionSettingTrue('prop_visible_resourcealloc') )
	array_push($aProps ,'resources');
	if (count($aFeatures)> 0 && IsSessionSettingTrue('prop_visible_features') )
	array_push($aProps ,'features');
	if (count($aChanges)> 0 && IsSessionSettingTrue('prop_visible_changes') )
	array_push($aProps ,'changes');
	if (count($aDocuments)> 0 && IsSessionSettingTrue('prop_visible_documents') )
	array_push($aProps ,'documents');
	if (count($aDefects)> 0 && IsSessionSettingTrue('prop_visible_defects') )
	array_push($aProps ,'defects');
	if (count($aIssues)> 0 && IsSessionSettingTrue('prop_visible_issues') )
	array_push($aProps ,'issues');
	if (count($aTasks)> 0 && IsSessionSettingTrue('prop_visible_tasks') )
	array_push($aProps ,'tasks');
	if (count($aEvents)> 0 && IsSessionSettingTrue('prop_visible_events') )
	array_push($aProps ,'events');
	if (count($aDecisions)> 0 && IsSessionSettingTrue('prop_visible_decisions') )
	array_push($aProps ,'decisions');
	if (count($aEfforts)> 0 && IsSessionSettingTrue('prop_visible_efforts') )
	array_push($aProps ,'efforts');
	if (count($aRisks)> 0 && IsSessionSettingTrue('prop_visible_risks') )
	array_push($aProps ,'risks');
	if (count($aMetrics)> 0 && IsSessionSettingTrue('prop_visible_metrics') )
	array_push($aProps ,'metrics');
	if (IsSessionSettingTrue('show_discuss'))
	{
	if (count($aDiscussions)> 0 || IsSessionSettingTrue('add_discuss'))
	{
	array_push($aProps ,'reviews');
	array_push($aProps ,'discussions');
	}
	}
	if (IsSessionSettingTrue('show_comments'))
	{
	if (count($aComments)> 0)
	{
	array_push($aProps ,'comments');
	}
	}
	$sObjectGenerated = SafeGetArrayItem1Dim($aCommonProps, 'generated');
	$sImageInSync = SafeGetArrayItem1Dim($aCommonProps, 'imageinsync');
	$sPCSEdition = SafeGetInternalArrayParameter($_SESSION, 'pro_cloud_license');
	$bShowingMiniProps = SafeGetInternalArrayParameter($_SESSION, 'show_propertiesview');
	$bShowSummary = false;
	if(!strIsTrue($bShowingMiniProps))
	{
	if ($sObjectResType === 'Diagram' && (!IsSessionSettingTrue('readonly_model') && $sPCSEdition !== 'Express') )
	{
	WriteGenerateDiagramSection($sObjectResType, $sObjectGenerated, $sImageInSync, $sObjectGUID);
	}
	$bShowSummary = true;
	}
	WriteSectionSummary($aCommonProps, $bShowSummary);
	if ( $bIsReviewElement )
	{
	$sPartInReviews = SafeGetInternalArrayParameter($_SESSION, 'participate_in_reviews');
	if ( strIsTrue($sPartInReviews) )
	{
	$sReviewSession = SafeGetInternalArrayParameter($_SESSION, 'review_session');
	$bJoin  = false;
	$bLeave = false;
	$sJoinTitle = '';
	$sLeaveTitle = '';
	if ( strIsEmpty( $sReviewSession ) )
	{
	$bJoin = true;
	}
	else
	{
	if ( $sReviewSession === $sObjectGUID )
	{
	$bLeave = true;
	}
	}
	echo '<div class="review-session-section"><div class="review-session-actions">';
	echo '<input class="review-session-action-button" name="join" id="review-session-join-button" value="' . _glt('Join Review') . '" type="button" title="' . _h($sJoinTitle) . '" onclick="OnJoinLeaveReviewSession(\'' . _j($sObjectGUID) . '\', \'' . _j($sObjectName) . '\', \'' . _j($sObjectGUID) . '\')" ' . ($bJoin ? '>' : 'disabled="">');
	echo '<input class="review-session-action-button" name="leave" id="review-session-leave-button" value="' . _glt('Leave Review') . '" type="button" onclick="OnJoinLeaveReviewSession(\'\', \'\', \'' . _j($sObjectGUID) . '\')" ' . ($bLeave ? '>' : 'disabled="">');
	echo '</div></div>';
	}
	}
	$bShowingMainProps = true;
	$bFilterProperties = SafeGetInternalArrayParameter($_SESSION, 'filter_properties', 'true');
	if (strIsTrue($bFilterProperties))
	{
	$sFilterLabel = 'Single';
	$sTitle = 'Displaying single property section based on selection';
	}
	else
	{
	$sFilterLabel = 'All&nbsp';
	$sTitle = 'Displaying all properties sections';
	}
	echo '<button id="properties-filter-button" onclick="FilterProperties(this)" filterprops="'.$bFilterProperties.'" title="'.$sTitle.'" style="float:right; margin-right:8px;position: relative;top: 6px;" class="properties-tab" value="discussions" type="button" style=""><img alt="" src="images/spriteplaceholder.png" id="properties-filter-icon">'.$sFilterLabel.'</button>';
	WritePropertiesTabs($aProps, $sObjectGUID, $sObjectType, $sObjectStereotype, $sObjectResType, $bObjLocked);
	echo '<div id="property-sections">';
	$sDefaultSection = 'notes';
	if ($bIsReviewElement)
	{
	$sDefaultSection = 'review';
	WriteSectionReview($sObjectGUID, $sObjectName,$aTaggedValues, $aReviewDiscuss, $aReviewDiagrams, $aReviewNoDiscuss);
	}
	if (($sObjectStereotype === 'Decision') && ($sObjectType === 'Activity') ||
	(($sObjectStereotype === 'BusinessKnowledgeModel') && ($sObjectType === 'Activity')))
	{
	$sDefaultSection = 'DMN Expression';
	echo WriteSectionDMNExpression($aTaggedValues);
	}
	WriteSectionNotes($sNotes, $sObjectGUID, $sObjectName, $bObjLocked, $sObjImageURL, $sLinkType, $sDefaultSection);
	if (IsSessionSettingTrue('prop_visible_location') )
	{
	WriteSectionLocation('location', _glt('Location'), '1', $aParentInfo, $aUsages);
	}
	if (count($aTaggedValues)> 0 && IsSessionSettingTrue('prop_visible_taggedvalues') )
	{
	WriteSectionTaggedValues($aTaggedValues);
	}
	if (count($aRelationships)> 0 && IsSessionSettingTrue('prop_visible_relationships') )
	{
	WriteSectionRelationships('relationships', _glt('Relationships'), '1', $aRelationships);
	}
	if (count($aAttributes)> 0 && IsSessionSettingTrue('prop_visible_attributes') )
	{
	WriteSectionAttributes($aAttributes);
	}
	if (count($aOperations)> 0 && IsSessionSettingTrue('prop_visible_operations') )
	{
	WriteSectionOperations($aOperations);
	}
	if (count($aRunStates)> 0 && IsSessionSettingTrue('prop_visible_runstates') )
	{
	WriteSectionRunStates($aRunStates, 'runstate');
	}
	if ( count($aRequirements)>0 )
	{
	WriteSectionRequirements($aRequirements);
	}
	if ( count($aConstraints)>0 )
	{
	WriteSectionConstraints($aConstraints);
	}
	if ( count($aScenarios)>0 )
	{
	WriteSectionScenarios($aScenarios);
	}
	if ( count($aFiles)>0 && IsSessionSettingTrue('prop_visible_files'))
	{
	WriteSectionFiles($aFiles, false);
	}
	if (count($aTests)> 0 && IsSessionSettingTrue('prop_visible_testing') )
	{
	WriteSectionTest($aTests, $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aResAllocs)> 0 && IsSessionSettingTrue('prop_visible_resourcealloc') )
	{
	WriteSectionResourceAllocs($aResAllocs, $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aFeatures)> 0 && IsSessionSettingTrue('prop_visible_features') )
	{
	WriteSectionChangeManagement1($aFeatures, 'feature', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aChanges)> 0 && IsSessionSettingTrue('prop_visible_changes') )
	{
	WriteSectionChangeManagement1($aChanges, 'change', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aDocuments)> 0 && IsSessionSettingTrue('prop_visible_documents') )
	{
	WriteSectionChangeManagement1($aDocuments, 'document', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aDefects)> 0 && IsSessionSettingTrue('prop_visible_defects') )
	{
	WriteSectionChangeManagement1($aDefects, 'defect', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aIssues)> 0 && IsSessionSettingTrue('prop_visible_issues') )
	{
	WriteSectionChangeManagement1($aIssues, 'issue', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aTasks)> 0 && IsSessionSettingTrue('prop_visible_tasks') )
	{
	WriteSectionChangeManagement1($aTasks, 'task', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aEvents)> 0 && IsSessionSettingTrue('prop_visible_events') )
	{
	WriteSectionChangeManagement1($aEvents, 'event', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aDecisions)> 0 && IsSessionSettingTrue('prop_visible_decisions') )
	{
	WriteSectionChangeManagement1($aDecisions, 'decision', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aEfforts)> 0 && IsSessionSettingTrue('prop_visible_efforts') )
	{
	WriteSectionChangeManagement2($aEfforts, 'effort', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aRisks)> 0 && IsSessionSettingTrue('prop_visible_risks') )
	{
	WriteSectionChangeManagement2($aRisks, 'risk', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aMetrics)> 0 && IsSessionSettingTrue('prop_visible_metrics') )
	{
	WriteSectionChangeManagement2($aMetrics, 'metric', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, $sObjectHyper);
	}
	if (count($aComments)> 0 && IsSessionSettingTrue('show_comments'))
	{
	WriteSectionComments('comments', _glt('Comments'), '1', $aComments, false, $sObjectGUID, $sCommentID);
	}
	if(IsSessionSettingTrue('show_discuss'))
	{
	include('propertiesdiscussions.php');
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
	else
	{
	if (SafeGetArrayItem1Dim($aCommonProps, 'guid') === '')
	{
	echo '<div id="main-content-empty" ' . $sMainClassEx . '>';
	$s = _glt('NoElementLine1'). g_csHTTPNewLine . g_csHTTPNewLine;
	$sMessage = '';
	if ( !strIsEmpty($s) && $s!=='NoElementLine1' )
	{
	$sMessage .=  $s . g_csHTTPNewLine . g_csHTTPNewLine;
	}
	$s = _glt('NoElementLine2');
	if ( !strIsEmpty($s) && $s!=='NoElementLine2' )
	{
	$sMessage .=  $s . g_csHTTPNewLine . g_csHTTPNewLine;
	}
	$sMessage .= '<a href="javascript:MoveToPrevItemInNavigationHistory()">' . _glt('Back') . '</a>';
	echo $sMessage;
	echo '</div>';
	exit();
	}
	else
	{
	echo '<div id="main-content-empty">' . $sOSLCErrorMsg . '</div>';
	}
	}
	function WritePropertiesTabs($aProps, $sGUID, $sObjectType, $sObjectStereotype, $sObjectResType, $bObjLocked)
	{
	$bFilterProperties = SafeGetInternalArrayParameter($_SESSION, 'filter_properties', 'true');
	$sDefaultTab = '';
	if (($sObjectStereotype === 'Decision') && ($sObjectType === 'Activity') ||
	(($sObjectStereotype === 'BusinessKnowledgeModel') && ($sObjectType === 'Activity')))
	{
	$sDefaultTab = 'DMN Expression';
	}
	else if (($sObjectStereotype === 'EAReview') && ($sObjectType === 'Artifact'))
	{
	$sDefaultTab = 'summary';
	}
	else
	{
	$sDefaultTab = 'notes';
	}
	echo '<div id="properties-tabs">';
	if ( $sObjectResType === 'Package' || $sObjectResType === 'Element' )
	{
	if (((CanAddObjects() && IsSessionSettingTrue('login_perm_element')) ||
	(IsSessionSettingTrue('add_object_features')))
	&&
	(!$bObjLocked))
	{
	$sObjectName = '';
	$sObjectGUID = $sGUID;
	include('elementhamburger.php');
	}
	}
	foreach ($aProps as $prop)
	{
	if ($prop !== "location")
	$propSingular = substr($prop, 0, -1);
	else
	$propSingular = $prop;
	$propSingular = str_replace(' ', '', $propSingular);
	if ($prop === 'summary')
	{
	$propSingular = 'review';
	}
	$sButtonID = preg_replace('/\s+/', '', $prop);
	if (strIsTrue($bFilterProperties))
	{
	if ($prop === $sDefaultTab)
	{
	$sStyle = 'style="background-color: rgb(238, 238, 238);"';
	}
	else
	{
	$sStyle = '';
	}
	}
	else
	{
	$sStyle = '';
	}
	echo '<button onclick="ShowSection(\'' . _j($prop) . '\')" id="props-tab-' . _h($sButtonID) . '" class="properties-tab" value="' . _h($prop) . '" onclick="" ' . $sStyle . ' type="button">';
	echo '<img alt="" style="vertical-align: bottom;margin-right: 2px;" src="images/spriteplaceholder.png" class="propsprite-' . _h($propSingular) . '">';
	if ($prop === 'location')
	{
	echo _h('Usage');
	}
	else
	{
	echo _h(ucfirst($prop));
	}
	echo '</button>';
	}
	echo '</div>';
	}
?>
<script>
</script>