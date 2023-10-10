<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	if  ( !isset($aDiscussions) )
	{
	exit();
	}
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	require_once 'propertysections.php';
	SafeStartSession();
	CheckAuthorisation();
	$bAddDiscussions = (IsSessionSettingTrue('add_discuss'));
	$sAuthor 	= SafeGetInternalArrayParameter($_SESSION, 'login_fullname', '');
	if ( strIsEmpty( $sAuthor ) )
	$sAuthor = 'Web User';
	$sLoginGUID = SafeGetInternalArrayParameter($_SESSION, 'login_guid', '');
	$sSessionReviewGUID = SafeGetInternalArrayParameter($_SESSION, 'review_session');
	$iReviewCount = 0;
	foreach ($aDiscussions as $disc)
	{
	if (!strIsEmpty($disc['reviewguid']))
	{
	$iReviewCount++;
	}
	}
	if ((isset($sObjectType)) === false || ($sObjectType != 'ModelRoot'))
	{
	if ((isset($sLinkType)) && ($sLinkType === 'props' || ($sLinkType === '')))
	{
	if(isset($bIsMini) && $bIsMini)
	echo '<div id="review-section" class="property-section">';
	else
	echo '<div id="review-section" class="property-section" style="'.GetSectionVisibility('discussion-section').'">';
	}
	else
	{
	echo '<div id="review-section" class="property-section" style="display:block">';
	}
	if (isset($sResType) && $sResType !== 'Diagram')
	{
	echo '<div class="properties-header">Reviews</div>';
	echo '<div class="properties-content review-content">';
	echo WriteReviewsSection($aDiscussions, $sObjectGUID, $bAddDiscussions, $sAuthor, $sLoginGUID, $sSessionReviewGUID);
	echo '</div>';
	}
	else
	{
	echo '<div class="properties-header">Reviews</div>';
	echo '<div class="properties-content review-content">';
	echo  '<div class="review-new-topic-message">';
	echo  _glt('Review Discussions are not supported for Diagrams');
	echo  '</div>';
	echo '</div>';
	}
	echo '</div>';
	if ((isset($sLinkType)) && ($sLinkType === 'props' || ($sLinkType === '')))
	{
	if(isset($bIsMini) && $bIsMini)
	echo '<div id="discussion-section" class="property-section">';
	else
	echo '<div id="discussion-section" class="property-section" style="'.GetSectionVisibility('discussion-section').'">';
	}
	else
	{
	echo '<div id="discussion-section" class="property-section" style="display:block">';
	}
	echo '<div class="properties-header" object-type="'.$sResType.'">Discussions</div>';
	echo '<div class="properties-content discussion-content">';
	echo WriteDiscussionsSection($aDiscussions, $sObjectGUID, $bAddDiscussions, $sAuthor, $sLoginGUID, $sSessionReviewGUID);
	echo '</div>';
	echo '</div>' . PHP_EOL;
	if (isset($aAvatars))
	{
	echo WriteAvatarCSS($aAvatars);
	}
	}
	echo BuildSystemOutputDataDIV();
?>
<script>
	DiscussionKeyPressEvents();
</script>