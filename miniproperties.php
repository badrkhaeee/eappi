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
	if (!isset($webea_page_parent_mainview))
	{
	AllowedMethods('POST');
	}
	CheckAuthorisation();
	if ( isset($sObjectGUID)===false )
	{
	$sObjectGUID = SafeGetInternalArrayParameter($_POST, 'objectguid');
	}
	if (!isset($bShowingMainProps))
	{
	$bShowingMainProps = SafeGetInternalArrayParameter($_POST, 'bShowingMainProps');
	}
	$bShowingContents = SafeGetInternalArrayParameter($_POST, 'bShowingContents');
	if ( strIsEmpty($sObjectGUID) )
	{
	return;
	}
	$sSelectedFeatures = SafeGetInternalArrayParameter($_SESSION, 'selected_features');
	$aSelectedFeatures = str_getcsv($sSelectedFeatures);
	if (!isset($aCommonProps))
	{
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
	$sResourceFeaturesFilter = '';
	$sResourceFeaturesFilter = $sSelectedFeatures;
	$sResourceFeaturesFilter .= ',ld';
	if($sObjectGUID === 'root')
	$sObjectGUID = '';
	include('./data_api/get_properties.php');
	include('propertysections.php');
	}
	if ( count($aCommonProps) === 0 )
	{
	return;
	}
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
	$sObjClassName 	= SafeGetArrayItem1Dim($aCommonProps, 'classname');
	$sObjClassGUID 	= SafeGetArrayItem1Dim($aCommonProps, 'classguid');
	$sObjClassImageURL	= SafeGetArrayItem1Dim($aCommonProps, 'classimageurl');
	$sNotes = SafeGetArrayItem1Dim($aCommonProps, 'notes');
	echo '<div id="config-props-dialog">';
	echo '</div>';
	echo '<div class="mini-properties-name-section" style="min-height: 30px;padding-bottom: 12px;font-size: 0.8em;overflow-x: hidden;">';
	echo '<div class="propsview-config-button" onclick="ShowConfigProps(this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-cog" title="Feature Visibility"></div>';
	echo '<input id="current-props-view-guid" hidden value="'.$sObjectGUID.'">';
	$sObjectImageHTML = '<div class="mp-object-image">';
	if ( $sObjectType === 'Artifact' && $sObjectStereotype === 'Image')
	{
	$sImageBin =  SafeGetArrayItem1Dim($aCommonProps, 'imagepreview');
	if ( !strIsEmpty($sImageBin) )
	{
	$sObjectImageHTML .= '<img class="mp-object-image-asset" src="data:image/png;base64,' . _h($sImageBin) . '" alt="" title=""/>';
	}
	}
	else
	{
	$sObjectImageHTML .= '<img src="' . _h($sObjImageURL) . '" alt="" title="" height="32" width="32"/>';
	}
	$sObjectImageHTML .= '</div>';
	if (IsHyperlink($sObjectType, $sObjectName, $sObjectNType))
	{
	$sDisplayName = $sObjectName;
	if ( !strIsEmpty($sObjectAlias) )
	{
	$sDisplayName = $sObjectAlias;
	}
	$sDisplayName = GetPlainDisplayName($sDisplayName);
	echo $sObjectImageHTML;
	echo '<div class="object-name">' . _h($sDisplayName) . '</div>';
	echo '<div class="object-line2">';
	echo _glt('Hyperlink') . '&nbsp;';
	echo '</div>';
	}
	elseif ( $sObjectType === 'Text' && $sObjectStereotype === 'NavigationCell')
	{
	$sDisplayName = $sObjectAlias;
	$sDisplayName = GetPlainDisplayName($sDisplayName);
	echo $sObjectImageHTML;
	echo '<div class="object-name">' . _h($sDisplayName) . '</div>';
	echo '<div class="object-line2">';
	echo _glt('Navigation Cell') . '&nbsp;';
	echo '</div>';
	}
	elseif ( $sObjectType === 'Text' && strIsEmpty($sObjectStereotype) )
	{
	$sDisplayName = GetPlainDisplayName($sObjectName);
	echo $sObjectImageHTML;
	echo '<div class="object-name">' . _h($sDisplayName) . '</div>';
	echo '<div class="object-line2">';
	echo _glt('Text') . '&nbsp;';
	echo '</div>';
	}
	elseif ( $sObjectType === 'Note' && strIsEmpty($sObjectStereotype) )
	{
	$sDisplayName = GetPlainDisplayName('');
	echo $sObjectImageHTML;
	echo '<div class="object-name">' . _h($sDisplayName) . '</div>';
	echo '<div class="object-line2">';
	echo _glt('Note') . '&nbsp;';
	echo '</div>';
	}
	elseif (ShouldIgnoreName($sObjectType, $sObjectName, $sObjectNType))
	{
	$sDisplayObjType = $sObjectType;
	if ( !strIsEmpty($sObjClassGUID) )
	{
	$sDisplayObjType  = '<a class="w3-link" onclick="LoadObject(\'' . _j($sObjClassGUID) . '\',\'false\',\'\',\'\',\'' . _j($sObjectType) . '\',\'' . _j($sObjClassImageURL) . '\')">';
	$sDisplayObjType .= '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sObjClassImageURL) . '" alt="" style="float: none;">&nbsp;' . _h($sObjClassName) . '</a>';
	}
	else
	{
	$sDisplayObjType = _h(_glt($sDisplayObjType));
	}
	if (($sObjectType === 'Artifact') && ($sObjectStereotype === 'ExternalReference'))
	{
	$sObjImageURL16 = 'images/element16/document.png';
	}
	else
	{
	$sObjImageURL16 = AdjustImagePath($sObjImageURL, '16');
	}
	$sPrefix = substr($sObjectGUID,0,2);
	if ( $sPrefix !== 'mr' )
	{
	echo '<a class="w3-link"  style="color: #3777bf;" onclick="LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'props\',\'\',\'' . _j($sObjectName) . '\', \'' . _j($sObjImageURL16) . '\')">';
	echo $sObjectImageHTML . '&nbsp';
	echo '<div class="mp-object-name" title="">' . _h(GetPlainDisplayName($sObjectName)) . '&nbsp;';
	echo '</a>';
	}
	else
	{
	echo $sObjectImageHTML . '&nbsp';
	echo '<div class="mp-object-name" title="">' . _h(GetPlainDisplayName($sObjectName)) . '&nbsp;';
	echo '</a>';
	}
	echo '</div>';
	echo '<div class="object-line2">';
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
	$sDocType = SafeGetArrayItem1Dim($aDocument, 'type');
	if ( $sDocType === 'MDOC_HTML_CACHE' || $sDocType === 'MDOC_HTML_EDOC1' || $sDocType === 'ExtDoc' )
	{
	echo '<div class="object-document object-document-linked-doc">';
	if ( $sDocType === 'MDOC_HTML_EDOC1' || $sDocType === 'MDOC_HTML_CACHE' )
	{
	if ($sDocType === 'MDOC_HTML_EDOC1')
	{
	$sLoadObject = 'LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'encryptdoc\',\'\',\'' . _j($sObjectName) . '\', \'' . _j(AdjustImagePath($sObjImageURL, '16')) . '\')';
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName('images/element16/encrypteddoc.png') . '" src="images/spriteplaceholder.png" alt=""> ' . _glt('Linked Document') . ' ';
	echo '<br><div id="miniprops-linked-document-pwd"><input id="linked-document-pwd-field" placeholder="' . _glt('password') . '" type="password" onkeypress="return OnLinkDocPWDKeyDown(event,\''. _j($sObjectGUID) . '\',\'' . _j($sObjectName) . '\', \'' . _j(AdjustImagePath($sObjImageURL, '16')) . '\')" value="">';
	echo '<button class="linked-document-open-button webea-main-styled-button" onclick="' . $sLoadObject. '">' . _glt('Open Document') . '</button> </div>';
	}
	else
	{
	$sLoadObject = 'LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'document\',\'\',\'' . _j($sObjectName) . '\', \'' . _j(AdjustImagePath($sObjImageURL, '16')) . '\')';
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName('images/element16/document.png') . '" src="images/spriteplaceholder.png" alt=""> ' . _glt('Linked Document') . ' ';
	echo '<button class="linked-document-open-button webea-main-styled-button" onclick="' . $sLoadObject. '">' . _glt('Open Document') . '</button> ';
	}
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
	echo '<a onclick="DownloadFile(this)" guid="'.$sObjectGUID.'" download="' . _h($sFileName) . '"><button class="stored-document-download-button webea-main-styled-button">'  . _glt('Download') . '</button></a>';
	if (($sExtension === '.mp4') ||
	($sExtension === '.avi') ||
	($sExtension === '.wmv') ||
	($sExtension === '.mov') ||
	($sExtension === '.webm'))
	{
	echo '<a><button class="stored-document-watch-video-button webea-main-styled-button" guid="'._h($sObjectGUID).'" onclick="ShowVideoDialog(this)">'  . _glt('Watch Video') . '</button></a>';
	}
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
	echo '<a href="data_api/dl_model_image.php?objectguid=' . _h($sImageAssetGUID) . '" download="' . _h($sObjectName). '"><button class="stored-document-download-button webea-main-styled-button">'  . _glt('Download') . '</button></a>';
	echo '<a><button class="stored-document-view-image-button webea-main-styled-button" onclick="ShowImageViewerDialog()">'  . _glt('View Image') . '</button></a>';
	echo "</div>";
	WriteImageViewer($sObjectName, $sImageBin);
	}
	}
	}
	else
	{
	}
	echo '</div>';
	$sLinkType 	= SafeGetInternalArrayParameter($_POST, 'linktype');
	$bShowSummary	= true;
	if ($sObjectType !== 'ModelRoot')
	{
	if (($sObjectResType === 'Diagram') &&
	isset($sObjectGenerated) &&
	isset($sImageInSync))
	{
	WriteGenerateDiagramSection($sObjectResType, $sObjectGenerated, $sImageInSync, $sObjectGUID);
	}
	WriteSectionSummary($aCommonProps, $bShowSummary);
	$sNavButtonHTML = '';
	if (!isset($sObjImageURL16))
	$sObjImageURL16 = '';
	if (!isset($sPrefix))
	$sPrefix = '';
	if (!strIsTrue($bShowingMainProps))
	{
	$sNavButtonHTML .= '<button onclick="LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'props\',\'\',\'' . _j($sObjectName) . '\', \'' . _j($sObjImageURL16) . '\')" id="props-button-full-props" class="properties-tab" type="button"><img alt="" style="vertical-align: bottom;margin-right: 2px;" src="images/spriteplaceholder.png" class="propsprite-properties">Full Properties</button>';
	}
	if (($sPrefix === 'pk' || ($sPrefix === 'el' && $sHasChildren === 'true')) &&
	(!IsSessionSettingTrue('show_browser')))
	{
	if (!strIsTrue($bShowingContents))
	{
	if((!isset($_POST['linktype'])) ||
	(isset($_POST['linktype']) && $_POST['linktype']==='props'))
	{
	if ($sPrefix === 'pk')
	$sLabel = 'View Contents';
	else
	$sLabel = 'View Children';
	$sNavButtonHTML .= '<button onclick="LoadObject(\'' . _j($sObjectGUID) . '\',\'false\',\'child\',\'\',\'' . _j($sObjectName) . '\', \'' . _j($sObjImageURL16) . '\')" id="props-button-view-contents" class="properties-tab" type="button"><img alt="" style="vertical-align: bottom;margin-right: 2px;" src="images/spriteplaceholder.png" class="propsprite-viewcontents">' . _h($sLabel) . '</button>';
	}
	}
	}
	if (!strIsEmpty($sNavButtonHTML))
	{
	echo '<div style="padding: 0 0 8px 8px;">';
	echo $sNavButtonHTML;
	echo '</div>';
	}
	}
	if (IsSessionSettingTrue('prop_visible_location') )
	{
	WriteSectionLocation('location', _glt('Location'), '1', $aParentInfo, $aUsages, true);
	}
	if ($sObjectType !== 'ModelRoot')
	{
	echo '<div id="feature-mini-sections">';
	foreach ($aSelectedFeatures as $sID)
	{
	if ($sID === 'ts')
	{
	WriteSectionTest($aTests, $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'tv')
	{
	WriteSectionTaggedValues($aTaggedValues, true);
	}
	else if ($sID === 'lt')
	{
	WriteSectionRelationships('relationships', _glt('Relationships'), '1', $aRelationships, true);
	}
	else if ($sID === 'at')
	{
	WriteSectionAttributes($aAttributes, true);
	}
	else if ($sID === 'op')
	{
	WriteSectionOperations($aOperations, true);
	}
	else if ($sID === 'fl')
	{
	WriteSectionFiles($aFiles, true);
	}
	else if ($sID === 'ra')
	{
	WriteSectionResourceAllocs($aResAllocs, $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'mf')
	{
	WriteSectionChangeManagement1($aFeatures, 'feature', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'ch')
	{
	WriteSectionChangeManagement1($aChanges, 'change', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'dm')
	{
	WriteSectionChangeManagement1($aDocuments, 'document', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'df')
	{
	WriteSectionChangeManagement1($aDefects, 'defect', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'is')
	{
	WriteSectionChangeManagement1($aIssues, 'issue', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'tk')
	{
	WriteSectionChangeManagement1($aTasks, 'task', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'ev')
	{
	WriteSectionChangeManagement1($aEvents, 'event', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'dc')
	{
	WriteSectionChangeManagement1($aDecisions, 'decision', $sObjectGUID, $bObjLocked, $sObjectName, $sObjImageURL, $sLinkType, '', true);
	}
	else if ($sID === 'dr')
	{
	$bIsMini = true;
	$sResType = $sObjectResType;
	include('propertiesdiscussions.php');
	}
	else if ($sID === 'nt')
	{
	WriteSectionNotes($sNotes, $sObjectGUID, $sObjectName, $bObjLocked, $sObjImageURL, $sLinkType, false, true);
	}
	}
	echo '</div>';
	}
	echo BuildSystemOutputDataDIV();
	function WriteSelectFeatureMenu($sGUID, $sLabel)
	{
	echo '<div>';
	echo '<div id="select-feature-heading">' . _h($sLabel) . '</div>';
	echo '<div id="select-feature-button">';
	echo '<img alt="" id="select-feature-image" src="images/spriteplaceholder.png" class="mainsprite-hamburger24blue" title="Select Feature" onclick="ShowMenu(this)">&nbsp;';
	echo '<div id="select-feature-menu">';
	echo '<div class="contextmenu-header">' . _glt('Select Feature') . '</div>';
	echo '<div class="contextmenu-items">';
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'nt\',\'Notes\',\'' . _j($sGUID) . '\')"><img alt=""  style="top: 0px;" src="images/spriteplaceholder.png" class="propsprite-note"></img>' . _glt('Notes') . '</div>';
	if (IsSessionSettingTrue('prop_visible_taggedvalues'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'tv\',\'Tagged Values\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-taggedvalue"></img>' . _glt('Tagged Values') . '</div>';
	if (IsSessionSettingTrue('prop_visible_relationships'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'lt\',\'Relationships\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-relationship"></img>' . _glt('Relationships') . '</div>';
	if (IsSessionSettingTrue('prop_visible_attributes'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'at\',\'Attributes\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" style="left: 0px;" class="propsprite-attribute"></img>' . _glt('Attributes') . '</div>';
	if (IsSessionSettingTrue('prop_visible_operations'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'op\',\'Operations\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" style="left: 0px;" class="propsprite-operation"></img>' . _glt('Operations') . '</div>';
	if (IsSessionSettingTrue('prop_visible_files'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'fl\',\'Files\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" style="left: 0px;" class="propsprite-file"></img>' . _glt('Files') . '</div>';
	if (IsSessionSettingTrue('prop_visible_testing'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'ts\',\'Tests\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-test"></img>' . _glt('Tests') . '</div>';
	if (IsSessionSettingTrue('prop_visible_resourcealloc'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'ra\',\'Resources\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-resource"></img>' . _glt('Resources') . '</div>';
	if (IsSessionSettingTrue('prop_visible_features'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'mf\',\'Features\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-feature"></img>' . _glt('Features') . '</div>';
	if (IsSessionSettingTrue('prop_visible_changes'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'ch\',\'Changes\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-change"></img>' . _glt('Changes') . '</div>';
	if (IsSessionSettingTrue('prop_visible_documents'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'dm\',\'Documents\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-document"></img>' . _glt('Documents') . '</div>';
	if (IsSessionSettingTrue('prop_visible_defects'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'df\',\'Defects\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-defect"></img>' . _glt('Defects') . '</div>';
	if (IsSessionSettingTrue('prop_visible_issues'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'is\',\'Issues\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-issue"></img>' . _glt('Issues') . '</div>';
	if (IsSessionSettingTrue('prop_visible_tasks'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'tk\',\'Tasks\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-task"></img>' . _glt('Tasks') . '</div>';
	if (IsSessionSettingTrue('prop_visible_events'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'ev\',\'Events\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-event"></img>' . _glt('Events') . '</div>';
	if (IsSessionSettingTrue('prop_visible_decisions'))
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'dc\',\'Decisions\',\'' . _j($sGUID) . '\')"><img alt=""  src="images/spriteplaceholder.png" class="propsprite-decision"></img>' . _glt('Decisions') . '</div>';
	if (IsSessionSettingTrue('show_discuss'))
	{
	if (IsSessionSettingTrue('add_discuss'))
	{
	echo '<div class="contextmenu-item" onclick="SelectFeature(\'dr\',\'Discussions\',\'' . _j($sGUID) . '\')"><img alt="" style="margin-right: 8px;" src="images/spriteplaceholder.png" class="propsprite-discussion"></img>' . _glt('Discussions') . '</div>';
	}
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	}
?>
<script>
$( function() {
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
  } );
</script>