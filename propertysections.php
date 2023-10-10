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
	require_once __DIR__ . '/htmlpurifier.php';
	SafeStartSession();
	if (!isset($webea_page_parent_mainview))
	{
	AllowedMethods('POST');
	}
	CheckAuthorisation();
	function WriteArrayLabelValueProperty($aProps, $sItemName, $sLabel, $sTDLabelClass, $sTDValueClass, $bEchoResult=true)
	{
	$sReturn = '';
	$sValue = SafeGetArrayItem1Dim($aProps, $sItemName);
	if ( !strIsEmpty($sValue) )
	{
	$sReturn .= '<tr>';
	$sReturn .= '<td class="' . $sTDLabelClass . '">' . $sLabel . '</td>';
	$sReturn .= '<td class="' . $sTDValueClass . '">' . _h($sValue) . '</td>';
	$sReturn .= '</tr>';
	}
	if ($bEchoResult)
	{
	echo $sReturn;
	}
	return $sReturn;
	}
	function WriteLabelValueProperty($sLabel, $sValue, $sTDLabelID, $sTDValueID, $bEchoResult=true)
	{
	$sReturn = '';
	if ( !strIsEmpty($sValue) )
	{
	$sReturn .= '<tr>';
	$sReturn .= '<td id=' . $sTDLabelID . '>' . $sLabel . '</td>';
	$sReturn .= '<td id=' . $sTDValueID . '>' . _h($sValue) . '</td>';
	$sReturn .= '</tr>';
	}
	if ($bEchoResult)
	{
	echo $sReturn;
	}
	return $sReturn;
	}
	function WriteSectionAttributes($a, $bIsMini=false)
	{
	$sHeading = _glt('Attributes');
	if(empty($a))
	{
	echo WriteSectionEmpty($sHeading);
	return;
	}
	if ($bIsMini)
	{
	$sSectionID = 'attribute-mini-section';
	echo '<div id="' . _h($sSectionID) . '" class="propsview-section" style="display:block">';
	echo '<div class="miniprops-header">' . $sHeading . '</div>';
	echo '<div>';
	}
	else
	{
	$sSectionID = 'attribute-section';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">' . $sHeading . '</div>';
	echo '<div class="properties-content">';
	}
	usort($a, 'SortByPostion');
	$iCnt = count($a);
	for ($i=0; $i<$iCnt; $i++)
	{
	$sName 	= SafeGetArrayItem2Dim($a, $i, 'name');
	$sGUID 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	$sType 	= SafeGetArrayItem2Dim($a, $i, 'type');
	$sScope 	= SafeGetArrayItem2Dim($a, $i, 'scope');
	$sDefault 	= SafeGetArrayItem2Dim($a, $i, 'default');
	$sAlias 	= SafeGetArrayItem2Dim($a, $i, 'alias');
	$sStereotype 	= SafeGetArrayItem2Dim($a, $i, 'sterotype');
	$sClassName 	= SafeGetArrayItem2Dim($a, $i, 'classname');
	$sClassGUID 	= SafeGetArrayItem2Dim($a, $i, 'classguid');
	$sClassImageURL	= SafeGetArrayItem2Dim($a, $i, 'classimageurl');
	$sNotNull	= SafeGetArrayItem2Dim($a, $i, 'allowdups');
	$sIsOrdered	= SafeGetArrayItem2Dim($a, $i, 'isordered');
	$sIsCollection	= SafeGetArrayItem2Dim($a, $i, 'iscollection');
	$sLength	= SafeGetArrayItem2Dim($a, $i, 'length');
	$sPrecision	= SafeGetArrayItem2Dim($a, $i, 'precision');
	$sScale	= SafeGetArrayItem2Dim($a, $i, 'scale');
	$sPostion	= SafeGetArrayItem2Dim($a, $i, 'position');
	$bIsColumn = false;
	if ($sStereotype === 'column')
	$bIsColumn = true;
	if (substr($sDefault,0,26)=='<Image type="EAShapeScript')
	$sDefault = '<EAShapeScript>';
	$sStereotypeHTML = formatWithStereotypeChars($sStereotype);
	echo '<div class="attribute-item property-section-item">';
	$s = mb_strtolower($sScope);
	$s = ($s==='private' || $s==='protected' || $s==='public' || $s==='package' ) ? 'propsprite-attribute' . $s : 'propsprite-attribute';
	if ( !strIsEmpty($sClassGUID) )
	{
	$sTypeHTML  = '<a class="w3-link" onclick="LoadObject(\'' . _j($sClassGUID) . '\',\'false\',\'\',\'\',\'' . _j($sType) . '\',\'' . _j($sClassImageURL) . '\')">';
	$sTypeHTML .= '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sClassImageURL) . '" style="float: none;">&nbsp;' . _h($sClassName) . '</a>';
	}
	else
	{
	$sTypeHTML = _h($sType);
	}
	if ($sLength !== '0')
	{
	$sTypeHTML = $sTypeHTML.'('.$sLength.')';
	}
	else if (( $sPrecision !== '0') )
	{
	if ($sScale !== '0')
	$sScale = ','.$sScale;
	else
	$sScale = '';
	$sTypeHTML = $sTypeHTML.'(' . $sPrecision . $sScale . ')';
	}
	$aAttribItemHdr  = '<div class="attribute-item-hdr">';
	$aAttribItemHdr .= '<img alt="" src="images/spriteplaceholder.png" class="' . $s . '">';
	$aAttribItemHdr .= '<div class="attribute-name">';
	if ( !strIsEmpty($sAlias) )
	{
	$aAttribItemHdr .= '(' . _h($sAlias) . ') ';
	}
	$aAttribItemHdr .= _h($sName);
	$aAttribItemHdr .= WriteValueInSentence($sStereotypeHTML, '&nbsp;', '', false);
	$aAttribItemHdr .= WriteValueInSentence($sTypeHTML, ':&nbsp;', '', false);
	$aAttribItemHdr .= WriteValueInSentence(_h($sDefault), '&nbsp;=&nbsp;', '', false);
	$aAttribItemHdr .= '</div>';
	if ($bIsColumn)
	{
	$aAttributeProps = '';
	if ( $sIsOrdered === 'True' )
	{
	$aAttributeProps .= '<div class="attribute-property">';
	$aAttributeProps .= '<span class="attribute-property-label">Primary Key</span>';
	$aAttributeProps .= '</div>';
	}
	if ( $sIsCollection === 'True' )
	{
	$aAttributeProps .= '<div class="attribute-property">';
	$aAttributeProps .= '<span class="attribute-property-label">Foreign Key</span>';
	$aAttributeProps .= '</div>';
	}
	if ( $sNotNull === 'True' )
	{
	$aAttributeProps .= '<div class="attribute-property">';
	$aAttributeProps .= '<span class="attribute-property-label">Not Null</span>';
	$aAttributeProps .= '</div>';
	}
	if(!strIsEmpty($aAttributeProps))
	{
	$aAttribItemHdr .= '<div class="attribute-properties">' . $aAttributeProps . '</div>';
	}
	}
	$aAttribItemHdr .= '</div>';
	$sNotes 	= SafeGetArrayItem2Dim($a, $i, 'notes');
	$sMultiplicity 	= SafeGetArrayItem2Dim($a, $i, 'multiplicity');
	$sIsStatic 	= SafeGetArrayItem2Dim($a, $i, 'isstatic');
	$sContainment 	= SafeGetArrayItem2Dim($a, $i, 'containment');
	$sAttribExInfo = '';
	if ( !strIsEmpty($sNotes) || !strIsEmpty($sMultiplicity) || ( !strIsEmpty($sIsStatic) && $sIsStatic!=='False') ||
	(!strIsEmpty($sContainment) && $sContainment!=='Not Specified' && $sContainment!=='False') ||
	 array_key_exists(("taggedvalues"), $a[$i]) )
	{
	if ( !strIsEmpty($sNotes) )
	{
	$sAttribExInfo .= '<div class="attribute-notes">' . _hRichText($sNotes) . '</div>';
	}
	if ( !strIsEmpty($sMultiplicity) ||
	(!strIsEmpty($sIsStatic) && $sIsStatic!=="False") ||
	(!strIsEmpty($sContainment) && $sContainment!=="Not Specified" && $sContainment!=="False") )
	{
	$sAttribExInfo .= '<div class="attribute-properties">';
	if ( !strIsEmpty($sMultiplicity) )
	{
	$sAllowDups = SafeGetArrayItem2Dim($a, $i, 'allowdups');
	$sIsOrdered = SafeGetArrayItem2Dim($a, $i, 'isordered');
	$sAttribExInfo .= '<div class="attribute-property">';
	$sAttribExInfo .= '<span class="attribute-property-label">' . _glt('Multiplicity') . ':</span>&nbsp;(&nbsp;' . _h($sMultiplicity);
	if ( $sAllowDups=='True')
	$sAttribExInfo .= '<span class="attribute-property-label">, ' . _glt('Allows duplicates') . '</span>&nbsp;';
	if ( $sIsOrdered=='True')
	$sAttribExInfo .= '<span class="attribute-property-label">, ' . _glt('Ordered') . '</span>&nbsp;';
	$sAttribExInfo .= ' ) </div>';
	}
	if ($sIsStatic==="True")
	{
	$sAttribExInfo .= '<div class="attribute-property">';
	$sAttribExInfo .= '<span class="attribute-property-label">' . _glt('Is static') . '</span>&nbsp;';
	$sAttribExInfo .= '</div>';
	}
	if ( !strIsEmpty($sContainment) && $sContainment!=="Not Specified" && $sContainment!=="False")
	{
	$sAttribExInfo .= '<div class="attribute-property">';
	$sAttribExInfo .= '<span class="attribute-property-label">' . _glt('Containment') . ':</span>&nbsp;' . _h($sContainment);
	$sAttribExInfo .= '</div>';
	}
	$sAttribExInfo .= '</div>';
	}
	if (array_key_exists(("taggedvalues"), $a[$i]))
	{
	$aTV = $a[$i]['taggedvalues'];
	$sAttribExInfo .= '<div class="attribute-taggedvalues">';
	$sAttribExInfo .= '<div id="attribute-taggedvalues-' . $sGUID . '" ';
	$sAttribExInfo .=     ' class="attribute-taggedvalues-header">' . _glt('Tagged Values') . '</div>';
	$sAttribExInfo .= '<div class="attribute-taggedvalues-items">';
	$iTVCnt = count($aTV);
	for ($iTV=0; $iTV<$iTVCnt; $iTV++)
	{
	$sAttribExInfo .= '<div class="attribute-taggedvalue-item">';
	$sName = SafeGetArrayItem2Dim($aTV, $iTV, 'name');
	$sValue = SafeGetArrayItem2Dim($aTV, $iTV, 'value');
	$sNotes = SafeGetArrayItem2Dim($aTV, $iTV, 'notes');
	$sNotes = (substr($sNotes,0,8)=='Values: ') ? '' : $sNotes;
	if ($sValue==='<memo>')
	{
	$sValue = '';
	}
	$sAttribExInfo .= '<div class="attribute-taggedvalue-data">' . _h($sName);
	if ( !strIsEmpty($sValue) )
	{
	$sAttribExInfo .= '&nbsp;=&nbsp;' . _h($sValue);
	}
	$sAttribExInfo .= '</div>';
	if ( !strIsEmpty($sNotes) )
	{
	$sAttribExInfo .= '<div class="attribute-taggedvalue-notes">' . _h($sNotes) . '</div>';
	}
	$sAttribExInfo .= '</div>';
	}
	$sAttribExInfo .= '</div>';
	$sAttribExInfo .= '</div>';
	}
	}
	if ( !strIsEmpty($sAttribExInfo) )
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-closed">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $aAttribItemHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-hide">';
	echo $sAttribExInfo;
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-blank-icon">';
	echo $aAttribItemHdr;
	echo '</div>';
	}
	echo '</div>' . PHP_EOL;
	}
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionOperations($a, $bIsMini=false)
	{
	$sHeading = _glt('Operations');
	if(empty($a))
	{
	echo WriteSectionEmpty($sHeading);
	return;
	}
	if ($bIsMini)
	{
	$sSectionID = 'operation-mini-section';
	echo '<div id="' . _h($sSectionID) . '"  class="propsview-section" style="display:block">';
	echo '<div class="miniprops-header">' . $sHeading  . '</div>';
	echo '<div class="miniprops-content">';
	}
	else
	{
	$sSectionID = 'operation-section';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">' . $sHeading  . '</div>';
	echo '<div class="properties-content">';
	}
	usort($a, 'SortByPostion');
	$iCnt = count($a);
	for ($i=0;$i<$iCnt;$i++)
	{
	$sName 	= SafeGetArrayItem2Dim($a, $i, 'name');
	$sGUID 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	$sScope 	= SafeGetArrayItem2Dim($a, $i, 'scope');
	$sParameters 	= SafeGetArrayItem2Dim($a, $i, 'parastring');
	$sParametersFormatted = SafeGetArrayItem2Dim($a, $i, 'parastringformat');
	$sType 	= SafeGetArrayItem2Dim($a, $i, 'classifier');
	$sStereotype 	= SafeGetArrayItem2Dim($a, $i, 'sterotype');
	$sAlias 	= SafeGetArrayItem2Dim($a, $i, 'alias');
	$sClassifierName 	= SafeGetArrayItem2Dim($a, $i, 'classifiername');
	$sClassifierGUID 	= SafeGetArrayItem2Dim($a, $i, 'classifierguid');
	$sClassifierImageURL= SafeGetArrayItem2Dim($a, $i, 'classifierimageurl');
	$sStereotypeHTML = formatWithStereotypeChars($sStereotype);
	echo '<div class="operation-item property-section-item">';
	$s = mb_strtolower($sScope);
	$s = ($s==='private' || $s==='protected' || $s==='public' || $s==='package' ) ? 'propsprite-operation' . $s : 'propsprite-operation';
	$sOperationItemHdr = '<div class="operation-item-hdr">' ;
	$sOperationItemHdr .= '<img alt="" src="images/spriteplaceholder.png" class="' . _h($s) . '">';
	$sOperationItemHdr .= '<div class="operation-name">' ;
	if ( !strIsEmpty($sAlias) )
	{
	$sOperationItemHdr .= '(' . _h($sAlias) . ') ';
	}
	$sOperationItemHdr .= _h($sName);
	$sOperationItemHdr .= WriteValueInSentence($sStereotypeHTML, '&nbsp;', '', false);
	$sOperationItemHdr .= '(' . $sParameters . ')';
	if ( strIsEmpty( $sClassifierGUID ) )
	{
	$sOperationItemHdr .= WriteValueInSentence(_h($sType), ':&nbsp;', '', false);
	}
	else
	{
	$sOperationItemHdr .= ':&nbsp;<a class="w3-link" onclick="LoadObject(\'' . _j($sClassifierGUID) . '\',\'false\',\'\',\'\',\'' . _j($sClassifierName) . '\',\'' . _j($sClassifierImageURL) . '\')">';
	$sOperationItemHdr .= '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sClassifierImageURL) . '" style="float: none;">&nbsp;' . _h($sClassifierName);
	$sOperationItemHdr .= '</a>';
	}
	$sOperationItemHdr .= '</div>';
	$sOperationItemHdr .= '</div>';
	$sOperationExInfo = '';
	$sNotes = SafeGetArrayItem2Dim($a, $i, 'notes');
	if ( !strIsEmpty($sNotes) )
	{
	$sOperationExInfo .= '<div class="operation-notes">' . _hRichText($sNotes) . '</div>';
	}
	$sIsStatic 	= SafeGetArrayItem2Dim($a, $i, 'isstatic');
	$sIsAbstract	= SafeGetArrayItem2Dim($a, $i, 'isabstract');
	$sIsReturnArray	= SafeGetArrayItem2Dim($a, $i, 'isreturnarray');
	$sIsQuery 	= SafeGetArrayItem2Dim($a, $i, 'isquery');
	$sIsSynch 	= SafeGetArrayItem2Dim($a, $i, 'issynch');
	if (( !strIsEmpty($sIsStatic) 	&& $sIsStatic!=="False") ||
	( !strIsEmpty($sIsAbstract) 	&& $sIsAbstract!=="False") ||
	( !strIsEmpty($sIsReturnArray) 	&& $sIsReturnArray!=="False") ||
	( !strIsEmpty($sIsQuery)	&& $sIsQuery!=="False") ||
	( !strIsEmpty($sIsSynch) 	&& $sIsSynch!=="False") )
	{
	$sOperationExInfo .= '<div class="operation-properties">';
	if ( !strIsEmpty($sIsStatic) && $sIsStatic!=="False")
	{
	$sOperationExInfo .= '<div class="operation-property"><span class="operation-property-label">' . _glt('Is static') . '</span>&nbsp;</div>';
	}
	if ( !strIsEmpty($sIsAbstract) && $sIsAbstract!=="False")
	{
	$sOperationExInfo .= '<div class="operation-property"><span class="operation-property-label">' . _glt('Is abstract') . '</span>&nbsp;</div>';
	}
	if ( !strIsEmpty($sIsReturnArray) && $sIsReturnArray!=="False")
	{
	$sOperationExInfo .= '<div class="operation-property"><span class="operation-property-label">' . _glt('Is return array') . '</span>&nbsp;</div>';
	}
	if ( !strIsEmpty($sIsQuery) && $sIsQuery!=="False")
	{
	$sOperationExInfo .= '<div class="operation-property"><span class="operation-property-label">' . _glt('Is query') . '</span>&nbsp;</div>';
	}
	if ( !strIsEmpty($sIsSynch) && $sIsSynch!=="False")
	{
	$sOperationExInfo .= '<div class="operation-property"><span class="operation-property-label">' . _glt('Is synchronized') . '</span>&nbsp;</div>';
	}
	$sOperationExInfo .= '</div>';
	}
	if (array_key_exists(("taggedvalues"), $a[$i]))
	{
	$aTV = $a[$i]['taggedvalues'];
	$sOperationExInfo .= '<div class="operation-taggedvalues">';
	$sOperationExInfo .= '<div id="operation-taggedvalues-' . _h($sGUID) . '" ';
	$sOperationExInfo .=     ' class="operation-taggedvalues-header">' . _glt('Tagged Values') . '</div>';
	$sOperationExInfo .= '<div class="operation-taggedvalues-items">';
	$iTVCnt = count($aTV);
	for ($iTV=0; $iTV<$iTVCnt; $iTV++)
	{
	$sOperationExInfo .= '<div class="operation-taggedvalue-item">';
	$sName = SafeGetArrayItem2Dim($aTV, $iTV, 'name');
	$sValue = SafeGetArrayItem2Dim($aTV, $iTV, 'value');
	$sNotes = SafeGetArrayItem2Dim($aTV, $iTV, 'notes');
	$sNotes = (substr($sNotes,0,8)=='Values: ') ? '' : $sNotes;
	if ($sValue==='<memo>')
	{
	$sValue = '';
	}
	$sOperationExInfo .= '<div class="operation-taggedvalue-data">' . _h($sName);
	if ( !strIsEmpty($sValue) )
	{
	$sOperationExInfo .= '&nbsp;=&nbsp;' . _h($sValue);
	}
	$sOperationExInfo .= '</div>';
	if ( !strIsEmpty($sNotes) )
	{
	$sOperationExInfo .= '<div class="operation-taggedvalue-notes">' . _hRichTextText($sNotes) . '</div>';
	}
	$sOperationExInfo .= '</div>';
	}
	$sOperationExInfo .= '</div>';
	$sOperationExInfo .= '</div>' . PHP_EOL;
	}
	if ( !strIsEmpty($sOperationExInfo) )
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-closed" >';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $sOperationItemHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-hide">';
	echo $sOperationExInfo;
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-blank-icon">';
	echo $sOperationItemHdr;
	echo '</div>';
	}
	echo '</div>' . PHP_EOL;
	}
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionExternalData($a)
	{
	echo '<div id="externaldata-section">';
	$iCnt = count($a);
	for ($i=0; $i<$iCnt; $i++)
	{
	$sName	= SafeGetArrayItem2Dim($a, $i, 'name');
	$sGUID	 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	$sNotes	 	= SafeGetArrayItem2Dim($a, $i, 'notes');
	$sValue	 	= SafeGetArrayItem2Dim($a, $i, 'value');
	$sExtSrcType 	= SafeGetArrayItem2Dim($a, $i, 'extsrctype');
	$sExtSrcUrl	 	= SafeGetArrayItem2Dim($a, $i, 'extsrcfullurl');
	echo '<div class="non-collapsible-guid">';
	echo '<table class="common-table" style="padding-bottom: 10px;padding-left: 30px;"><tbody>';
	if ( !strIsEmpty($sName) )
	echo '<tr><td class="common-label">Name:</td><td class="common-value">' . _h($sName) . '</td></tr>';
	if ( !strIsEmpty($sGUID) )
	echo '<tr><td class="common-label">GUID</td><td class="common-value">' . _h($sGUID) . '</td></tr>';
	if ( !strIsEmpty($sNotes) )
	echo '<tr><td class="common-label">Notes</td><td class="common-value">' . _hRichText($sNotes) . '</td></tr>';
	if ( !strIsEmpty($sValue) )
	echo '<tr><td class="common-label">Value</td><td class="common-value">' . _h($sValue) . '</td></tr>';
	if ( !strIsEmpty($sExtSrcType) )
	echo '<tr><td class="common-label">Type</td><td class="common-value">' . _h($sExtSrcType) . '</td></tr>';
	if ( !strIsEmpty($sExtSrcUrl) )
	echo '<tr><td class="common-label">URL</td><td class="common-value"><a href="' . _h($sExtSrcUrl) . '">Link</a></td></tr>';
	echo '</tbody></table>';
	echo '</div>';
	}
	echo '</div>';
	}
	function WriteSectionTaggedValues($a, $bIsMini=false)
	{
	$sHeading = _glt('Tagged Values');
	if(empty($a))
	{
	echo WriteSectionEmpty($sHeading);
	return;
	}
	if ($bIsMini)
	{
	$sSectionID = 'taggedvalue-mini-section';
	echo '<div id="' . _h($sSectionID) . '" class="propsview-section" style="display:block">';
	echo '<div class="miniprops-header">'.$sHeading.'</div>';
	echo '<div>';
	}
	else
	{
	$sSectionID = 'taggedvalue-section';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">'.$sHeading.'</div>';
	echo '<div class="properties-content">';
	}
	$aTagGroups = array();
	$iCnt = count($a);
	for ($i=0;$i<$iCnt;$i++)
	{
	$sGroup  = SafeGetArrayItem2Dim($a, $i, 'group');
	if ($sGroup == '')
	{
	WriteTaggedValue($a, $i);
	}
	else
	{
	if (!in_array($sGroup, $aTagGroups)) {
	$aTagGroups[] = $sGroup;
	}
	}
	}
	asort($aTagGroups);
	foreach ($aTagGroups as $sCurrentGroup)
	{
	$sTVGroupHdr  = '<div class="taggedvalue-group-name">';
	$sTVGroupHdr .= '<div class="propsprite-taggedvaluegroup"></div>';
	$sTVGroupHdr .= '<div class="taggedvalue-name">' . _h($sCurrentGroup) . '</div>';
	$sTVGroupHdr .= '</div>';
	echo '<div class="taggedvalue-group-item property-section-item">';
	$sCollapsibleGroupID = 'collapsible-group-' . $sCurrentGroup;
	echo '<div id="' . _h($sCollapsibleGroupID) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-opened">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-opened-icon">';
	echo $sTVGroupHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-show">';
	$iCnt = count($a);
	for ($i=0;$i<$iCnt;$i++)
	{
	$sGroup  = SafeGetArrayItem2Dim($a, $i, 'group');
	if ($sGroup == $sCurrentGroup)
	{
	WriteTaggedValue($a, $i);
	}
	}
	echo '</div>';
	echo '</div>';
	}
	echo '</div>';
	echo '</div>';
	}
	function WriteTaggedValue($a, $i)
	{
	echo '<div class="taggedvalue-item property-section-item">';
	$sName  = SafeGetArrayItem2Dim($a, $i, 'name');
	$sValue = SafeGetArrayItem2Dim($a, $i, 'value');
	$sNotes = SafeGetArrayItem2Dim($a, $i, 'notes');
	$sTagType = SafeGetArrayItem2Dim($a, $i, 'type');
	$sGUID  = SafeGetArrayItem2Dim($a, $i, 'guid');
	$aRefResource  = SafeGetArrayItem2Dim($a, $i, 'referredresource');
	$sColon = "";
	if ($sValue==='<memo>')
	{
	$sValue = '';
	if (substr($sNotes,0,11)==='<Checklist>')
	{
	$aTV = ConvertTVXMLToArray($sNotes);
	$iTVCnt = count($aTV);
	if ($iTVCnt>0)
	{
	$sNotes  = '<table class="taggedvalue-notes-checklist"><tbody>';
	for ($iTV=0; $iTV<$iTVCnt; $iTV++)
	{
	$sSubValue	= SafeGetArrayItem2Dim($aTV, $iTV, 'checked');
	if ($sSubValue === "True")
	{
	$sSubValue	= '<img alt="True" src="images/spriteplaceholder.png" class="propsprite-tick">';
	}
	else
	{
	$sSubValue	= '<img alt="False" src="images/spriteplaceholder.png" class="propsprite-untick">';
	}
	$sSubName	= SafeGetArrayItem2Dim($aTV, $iTV, 'text');
	$sNotes    .= WriteLabelValueProperty($sSubValue, $sSubName, 'checklist-tickbox', 'checklist-text', false);
	}
	$sNotes .= '</tbody></table>';
	}
	else
	{
	$sNotes = _h($sNotes);
	}
	}
	else if (substr($sNotes,0,12)==='<MatrixData>')
	{
	$sNotes = _h('<Matrix Configuration Data>');
	}
	else if (substr($sNotes,0,11)==='<modelview>')
	{
	$sNotes = _h('<Model View Configuration Data>');
	}
	else if (substr($sNotes,0,17)==='<DocumentOptions>')
	{
	$sNotes = _h('<Document Generation Data>');
	}
	else if (substr($sNotes,0,12)==='<chart type=')
	{
	$sNotes = _h('<Chart Configuration Data>');
	}
	else
	{
	$sNotes = _h($sNotes);
	}
	if($sName === 'decisionLogic')
	{
	$sNotes= str_replace(_h('<?xml version="1.0" encoding="UTF-16" standalone="no" ?>'),'',$sNotes);
	}
	}
	if (substr($sNotes,0,8)==='Values: ')
	{
	$sNotes = '';
	}
	if (substr($sNotes,0,14)==='<tagStructure>')
	{
	$sValue = '';
	$aTV = ConvertTVXMLToArray($sNotes);
	$iTVCnt = count($aTV);
	if ($iTVCnt>0)
	{
	$sNotes  = '<table class="taggedvalue-notes-stv"><tbody>';
	for ($iTV=0; $iTV<$iTVCnt; $iTV++)
	{
	$sSubName	= SafeGetArrayItem2Dim($aTV, $iTV, 'name');
	$sSubValue	= SafeGetArrayItem2Dim($aTV, $iTV, 'value');
	$sNotes    .= WriteLabelValueProperty($sSubName, $sSubValue, 'taggedvalue-label', 'taggedvalue-value', false);
	}
	$sNotes .= '</tbody></table>';
	}
	else
	{
	$sNotes = _h($sNotes);
	$sValue = _h($sValue);
	}
	}
	$bIsURL = false;
	$iPos = strpos($sNotes, 'Type=');
	if ($iPos !== false)
	{
	$iPosEnd = strpos($sNotes, ';', $iPos+5);
	if ($iPosEnd !== false)
	{
	$sSpecialType = mb_substr($sNotes, $iPos+5, $iPosEnd-($iPos+5));
	if ( $sSpecialType === 'URL' )
	{
	$bIsURL = true;
	}
	$sNotes = mb_substr($sNotes, 0, $iPos) . mb_substr($sNotes, $iPosEnd+1);
	}
	}
	$sTVItemHdr  = '<div class="propsprite-taggedvaluesingle"></div>';
	$sTVItemHdr .= '<div class="taggedvalue-name">';
	$sTVItemHdr .= _h($sName);
	if ($sTagType!=='' && $sTagType!==null)
	{
	$sProtocol = '';
	$sSpecialType = '';
	$iPos = strpos($sValue, '://');
	if ($iPos !== false)
	{
	$sProtocol = substr($sValue, 0, $iPos);
	}
	$iPos = strpos($sTagType, 'Type=');
	$iPosEnd = strpos($sTagType, ';', $iPos+5);
	$sSpecialType = mb_substr($sTagType, $iPos+5, $iPosEnd-($iPos+5));
	if (($sSpecialType === 'URL') || ($sProtocol==='http' || $sProtocol==='https'))
	{
	$sPrefix = '';
	if ($sProtocol!=='http' && $sProtocol!=='https')
	{
	$sPrefix = '//';
	}
	if ( $sValue!=='' )
	{
	$sValue = '<a target="_blank" rel="noopener noreferrer" href="' . _h($sPrefix . $sValue) . '">' . _h($sValue) .'</a>';
	}
	}
	else if ($sSpecialType === 'CheckList')
	{
	$iPos = strpos($sTagType, 'Values=');
	if ($iPos !== false)
	{
	$iPosEnd = strpos($sTagType, ';', $iPos+7);
	if ($iPosEnd !== false)
	{
	$sPreVal = mb_substr($sTagType, $iPos+7, $iPosEnd-($iPos+7));
	$aItems = str_getcsv($sPreVal);
	$aValues = str_getcsv($sValue);
	$sValue = 'Complete';
	$sNotes  = '<table class="taggedvalue-notes-checklist"><tbody>';
	$i=0;
	foreach ($aItems as $sItem)
	{
	if (array_key_exists($i, $aValues) && $aValues[$i] === "1")
	{
	$sSubValue	= '<img alt="True" src="images/spriteplaceholder.png" class="propsprite-tick">';
	}
	else
	{
	$sSubValue	= '<img alt="False" src="images/spriteplaceholder.png" class="propsprite-untick">';
	$sValue = 'Incomplete';
	}
	$sNotes    .= WriteLabelValueProperty($sSubValue, $sItem, 'checklist-tickbox', 'checklist-text', false);
	$i++;
	}
	$sNotes .= '</tbody></table>';
	}
	}
	}
	else if ($sSpecialType === 'Color')
	{
	$sValue = dechex($sValue);
	if (strlen($sValue) < 6)
	{
	$zeroCount = 6 - strlen($sValue);
	while ($zeroCount > 0)
	{
	$sValue = '0'.$sValue;
	--$zeroCount;
	}
	}
	$sB = mb_substr($sValue, 0, 2);
	$sG = mb_substr($sValue, 2, 2);
	$sR = mb_substr($sValue, 4, 6);
	$sValue = strtoupper($sR . $sG . $sB);
	$sValue = '<div style="display:inline-block;"><div class="taggedvalue-color-icon" style="background-color:#' . _h($sValue) . '"></div></div>' . _h($sValue);
	}
	else if ($sSpecialType === 'ImageRef')
	{
	$iPos = strpos($sValue, 'name=');
	if ($iPos !== false)
	{
	$iPosEnd = strpos($sValue, ';', $iPos+5);
	if ($iPosEnd !== false)
	{
	$sValue = mb_substr($sValue, $iPos+5, $iPosEnd-($iPos+5));
	}
	}
	}
	else if ($aRefResource !== '')
	{
	$sValue  = '';
	$refCount = count($aRefResource);
	$refName = '';
	$refGUID = '';
	$refType = '';
	$refResType = '';
	for ($i=0; $i<$refCount; $i++)
	{
	$refName = 	SafeGetArrayItem2Dim($aRefResource, $i, 'name');
	$refGUID = SafeGetArrayItem2Dim($aRefResource, $i, 'guid');
	$refType = SafeGetArrayItem2Dim($aRefResource, $i, 'type');
	$refResType = SafeGetArrayItem2Dim($aRefResource, $i, 'restype');
	$refImageURL = SafeGetArrayItem2Dim($aRefResource, $i, 'imageurl');
	$sValue .= '<a class="w3-link" onclick="LoadObject(\'' . _j($refGUID) . '\',\'false\',\'\',\'\',\'' . _j($refName) . '\',\'' . _j($refImageURL) . '\')">';
	$sValue .= '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($refImageURL) . ' taggedvalue-ref-icon" style="float: none;">&nbsp;' . _h($refName);
	$sValue .= '</a>';
	if ($i !== $refCount-1)
	{
	$sValue .= ', ';
	}
	}
	}
	}
	else
	{
	$sValue = _h($sValue);
	}
	$sTVItemHdr .= WriteValueInSentence($sValue, '&nbsp;:&nbsp;', '', false);
	$sTVItemHdr .= '</div>';
	if ( !strIsEmpty($sNotes) )
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     	' class="collapsible-section-header collapsible-section-header-closed">';
	echo '  <img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $sTVItemHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-hide">';
	echo '  <div class="taggedvalue-notes">' . $sNotes . '</div>';
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo '  <img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-blank-icon">';
	echo $sTVItemHdr;
	echo '</div>';
	}
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionReview($sObjectGUID, $sObjectName, $aTaggedValues, $aReviewDiscuss, $aReviewDiagrams, $aReviewNoDiscuss)
	{
	$sReviewStatus	= GetTaggedValue($aTaggedValues, 'Status', 'EAReview::Status');
	$sReviewStartDate = GetTaggedValue($aTaggedValues, 'StartDate', 'EAReview::StartDate');
	$sReviewEndDate	= GetTaggedValue($aTaggedValues, 'EndDate', 'EAReview::EndDate');
	echo '<div id="review-summary-section" class="property-section">';
	echo '<div class="properties-header">'. _glt('Review Summary').'</div>';
	WriteSectionReviewSummary($aReviewDiscuss, $sReviewStatus, $sReviewStartDate, $sReviewEndDate, $aReviewDiagrams, $aReviewNoDiscuss);
	if ( count($aReviewDiscuss)>0 || count($aReviewNoDiscuss)>0 )
	{
	echo '<div class="properties-header" style="padding-top: 16px;">'. _glt('Objects in Review') . '</div>';
	WriteSectionReviewDiscussions($aReviewDiscuss, $aReviewNoDiscuss);
	}
	echo '</div>';
	}
	function WriteSectionRequirements($a)
	{
	$sTableAttr = 'id="requirement-table" class="property-table"';
	$aHeader = ['', 'Name', 'Type', 'Status', 'Difficulty', 'Priority'];
	$aFields = ['icon', 'name', 'type', 'status', 'difficulty', 'priority'];
	$aFieldMap = [
	['label' => _glt('Stability'), 'value' => 'stability'],
	['label' => _glt('Modified'), 'value' => 'modified'],
	['label' => _glt('Description'), 'value' => 'notes']
	];
	$sSectionID = 'requirement-section';
	$i = 0;
	foreach ($a as &$row)
	{
	$reqID = 'requirement_'.$i;
	$row['tr_attributes'] = '';
	$row['icon'] = '<div class="expand-icon"></div>';
	$row['tr_details'] = WriteDataFields($row, $aFieldMap);
	$i++;
	}
	echo '<div id="requirement-section" class="property-section" style="'.GetSectionVisibility($sSectionID).'">';
	echo '<div id="requirement-list">';
	echo '<div class="properties-header">Requirements</div>';
	WriteListTable($sTableAttr, $aHeader, $a, $aFields);
	echo '</div>';
	echo '<div id="requirement-details" class="property-details">';
	$i=0;
	foreach ($a as $aReq)
	{
	$reqID = 'requirement_' . $i;
	echo '<div id="'.$reqID.'" style="display: none;">';
	echo '<div class="properties-header"><a class="properties-header-link" title="'._glt('Return to list').'"  onclick="ReturnToList(this)">'.'Requirements'.'</a>';
	echo '<img alt="" src="images/spriteplaceholder.png" class="propsprite-separator">';
	echo _h($aReq['name']) . '</div>';
	echo '<div class="property-details-container">';
	WritePropertyField('Name', $aReq['name'],'','style="width: 300px;"');
	echo '<div class="prop-row">';
	echo '<div class="prop-column">';
	WritePropertyField('Type', $aReq['type']);
	WritePropertyField('Status', $aReq['status']);
	echo '</div>';
	echo '<div class="prop-column">';
	WritePropertyField('Difficulty', $aReq['difficulty']);
	WritePropertyField('Priority', $aReq['priority']);
	echo '</div>';
	echo '<div class="prop-column">';
	WritePropertyField('Stability', $aReq['stability']);
	echo '</div>';
	echo '</div>';
	echo '<div class="prop-row">';
	echo '<div class="prop-column">';
	WritePropertyField('Modified', $aReq['modified'],'','style="width:150px;"');
	echo '</div>';
	echo '</div>';
	WritePropertyNoteField('Description', $aReq['notes']);
	echo '</div>';
	echo '</div>';
	$i++;
	}
	echo '</div>';
	echo '</div>';
	}
	function WriteSectionConstraints($a)
	{
	$sSectionID = 'constraint-section';
	echo '<div id="constraint-section" class="property-section" style="'.GetSectionVisibility($sSectionID).'">';
	echo '<div class="properties-header">Constraints</div>';
	echo '<div class="properties-content">';
	$iCnt = count($a);
	for ($i=0;$i<$iCnt;$i++)
	{
	$sName = SafeGetArrayItem2Dim($a, $i, 'name');
	$sNotes = SafeGetArrayItem2Dim($a, $i, 'notes');
	echo '<div class="constraint-item">';
	echo '<div class="constraint-name">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="constraint-icon">';
	echo '<span class="constraint-type">' . _h($a[$i]['type']) . '.</span>&nbsp;';
	echo '<span class="constraint-name-text">' . _h($sName) . '</span>&nbsp;';
	echo '</div>';
	if ( !strIsEmpty($sNotes) )
	{
	echo '<div class="constraint-desc">';
	echo '<span class="constraint-notes">' . _hRichText($sNotes) . '</span>';
	echo '</div>';
	}
	echo '<div class="constraint-status">[&nbsp;' . _h($a[$i]['status']) . '.&nbsp;]</div>';
	echo '</div>';
	}
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionScenarios($a)
	{
	$sSectionID = 'scenario-section';
	echo '<div id="scenario-section" class="property-section" style="'.GetSectionVisibility($sSectionID).'">';;
	echo '<div class="properties-header">Scenarios</div>';
	echo '<div class="properties-content">';
	$iCnt = count($a);
	for ($i=0; $i<$iCnt; $i++)
	{
	$sType = SafeGetArrayItem2Dim($a, $i, 'type');
	$sName = SafeGetArrayItem2Dim($a, $i, 'name');
	echo '<div class="scenario-item">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="scenario-icon">';
	if( !strIsEmpty($sType) && !strIsEmpty($sName) )
	{
	echo '<div class="scenario-name">'. _h($sType) . '&nbsp;.&nbsp'. _h($sName) .'</div>';
	echo '<div class="scenario-join"></div>';
	}
	elseif ( strIsEmpty($sType) && !strIsEmpty($sName))
	{
	echo '<div class="scenario-name">'. _h($sName) . '</div>';
	}
	echo '<div class="scenario-desc">';
	$sNotes = SafeGetArrayItem2Dim($a, $i, 'notes');
	if ( !strIsEmpty($sNotes) )
	{
	echo '<div class="scenario-notes">' . _hRichText($sNotes) . '</div>';
	}
	if (array_key_exists(("steps"), $a[$i]))
	{
	$aSteps = $a[$i]['steps'];
	$iStepsCnt = count($aSteps);
	for ($in=0; $in<$iStepsCnt; $in++)
	{
	$sStepName = SafeGetArrayItem2Dim($aSteps, $in, 'stepname');
	$sLevel = SafeGetArrayItem2Dim($aSteps, $in, 'level');
	$sUses = SafeGetArrayItem2Dim($aSteps, $in,'uses');
	$sTrigger = SafeGetArrayItem2Dim($aSteps, $in,'trigger');
	$sExtensions = SafeGetArrayItem2Dim($aSteps, $in,'extensions');
	if($sTrigger === '0')
	{
	$sTrigger = '<img alt="" src="images/spriteplaceholder.png" class="scenario-trigger-system">';
	}
	else if ($sTrigger === '1')
	{
	$sTrigger = '<img alt="" src="images/spriteplaceholder.png" class="scenario-trigger-user">';
	}
	echo '<div class="scenario-step">'.$sTrigger.'&nbsp;' . $sLevel . '.' . '&nbsp;' . _h($sStepName) . '</div>';
	if ( !strIsEmpty($sUses) )
	{
	echo '<div class="scenario-step-uses">';
	echo '<span class="scenario-step-uses-label">Uses:</span><span id="scenario-step-uses-text">&nbsp;' . _h($sUses) . '</span>';
	echo '</div>';
	}
	if ( !strIsEmpty($sExtensions) )
	{
	foreach ($sExtensions as $sExtension)
	{
	$sExtensionType = SafeGetArrayItem1Dim($sExtension, 'type');
	$sExtensionLevel = SafeGetArrayItem1Dim($sExtension, 'level');
	$sExtensionName = SafeGetArrayItem1Dim($sExtension, 'name');
	$sExtensionJoin = SafeGetArrayItem1Dim($sExtension, 'joinstep');
	$sExtensionJoinLabel = '&nbsp&nbsp<span class="scenario-step-extension-label">[Join: </span>' . _h($sExtensionJoin) . '<span class="scenario-step-extension-label">]</span>';
	echo '<div class="scenario-step-extension">';
	echo '<span class="scenario-step-extension-label">';
	echo _h($sExtensionType) . ':</span><span id="scenario-step-extension-text">&nbsp;' . _h($sExtensionLevel) . '. '. _h($sExtensionName);
	echo $sExtensionJoinLabel;
	echo '</span>';
	echo '</div>';
	}
	}
	}
	}
	echo '</div>';
	echo '</div>';
	}
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionFiles($a, $bIsMini)
	{
	$sSectionID = 'file-section';
	$sHeading = _glt('Files');
	if(empty($a))
	{
	echo WriteSectionEmpty($sHeading);
	return;
	}
	if ($bIsMini)
	{
	$sSectionID = 'file-mini-section';
	echo '<div id="' . _h($sSectionID) . '" class="propsview-section" style="display:block">';
	echo '<div class="miniprops-header">' . $sHeading . '</div>';
	echo '<div class="miniprops-content">';
	}
	else
	{
	$sSectionID = 'file-section';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">Files</div>';
	echo '<div class="properties-content">';
	}
	foreach ($a as $aFile)
	{
	$sFilePath = SafeGetArrayItem1Dim($aFile, 'filepath');
	$sType = SafeGetArrayItem1Dim($aFile, 'type');
	$sSize = SafeGetArrayItem1Dim($aFile, 'size');
	$sModified = SafeGetArrayItem1Dim($aFile, 'modified');
	$sDescription = SafeGetArrayItem1Dim($aFile, 'description');
	echo '<div class="file-item property-section-item">';
	echo '<div  class="file-item-line">';
	echo '<div style="position:absolute; margin-top:2px;">';
	$sOnClick = '';
	$sStyle = '';
	if(!strIsEmpty($sDescription))
	{
	$sOnClick = 'onclick="ToggleSectionAndIcon($(this).parent().parent().next().next(), $(this).children().first())"';
	$sStyle = 'style="cursor:pointer;"';
	}
	echo '<div class="expand-icon-container" '.$sStyle.' '.$sOnClick.'>';
	if(!strIsEmpty($sDescription))
	{
	echo '<img alt="" src="images/spriteplaceholder.png" class="expand-icon" expanded="false">';
	}
	echo '</div>';
	echo '<div class="file-item-icon">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="propsprite-file">';
	echo '</div>';
	echo '</div>';
	echo '<div class="file-item-path">';
	if ($sType==='Local File')
	{
	echo '<a href="file:///'._h($sFilePath).'">' ._h($sFilePath) .'</a>';
	}
	else
	{
	echo '<a target="_blank" rel="noopener noreferrer" href="'._h($sFilePath).'">' ._h($sFilePath) .'</a>';
	}
	echo '<input type="text" value="'._h($sFilePath).'" hidden="">';
	echo '<div class="copy-button" title="Copy path to clipboard" onclick="CopyText($(this).prev())">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="copy-icon">';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div style="margin-left: 36px;" class="file-item-line">';
	if(!strIsEmpty($sType))
	{
	echo '<div class="file-item-type"><a class="file-item-label">Type: </a>' ._h($sType) .'</div>';
	}
	if(!strIsEmpty($sModified))
	{
	echo '<div class="file-item-modified"><a class="file-item-label">Modified: </a>' ._h($sModified) .'</div>';
	}
	if(!strIsEmpty($sSize))
	{
	echo '<div class="file-item-size"><a class="file-item-label">Size: </a>' ._h($sSize) .'</div>';
	}
	echo '</div>';
	if(!strIsEmpty($sDescription))
	{
	echo '<div style="margin-left: 36px; display:none;" class="file-item-line">';
	echo '<div class="file-item-notes">' ._hRichText($sDescription) . '</div>';
	echo '</div>';
	}
	echo '</div>';
	}
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	function WriteTableInputRow($sLabel, $sField)
	{
	echo '<tr>';
	echo '<td>';
	echo _h($sLabel);
	echo '</td>';
	echo '<td>';
	echo _h($sField);
	echo '</td>';
	echo '</tr>';
	}
	function WriteSectionReviewSummary($a, $sReviewStatus, $sReviewStartDate, $sReviewEndDate, $aDiagrams, $aNoDiscuss)
	{
	$iElementNotDiscussCnt = 0;
	$iDiscussTopicCnt 	= 0;
	$iPriorLowCnt	= 0;
	$iPriorMediumCnt	= 0;
	$iPriorHighCnt 	= 0;
	$iPriorNoneCnt	= 0;
	$iStatusOpenCnt 	= 0;
	$iStatusAwaitCnt 	= 0;
	$iStatusClosedCnt 	= 0;
	$iReviewDiagramCnt 	= 0;
	echo '<div id="reviewsummary-section" class="properties-content">';
	$iElementCnt 	= count($a);
	for ($iEle=0; $iEle<$iElementCnt; $iEle++)
	{
	if (array_key_exists(("discussions"), $a[$iEle]))
	{
	$aDiscussions = $a[$iEle]['discussions'];
	$iDiscussCnt = count($aDiscussions);
	$iDiscussTopicCnt += $iDiscussCnt;
	for ($iD=0; $iD<$iDiscussCnt; $iD++)
	{
	$sPriority	= mb_strtolower(SafeGetArrayItem2Dim($aDiscussions, $iD, 'priority'));
	$sStatus 	= mb_strtolower(SafeGetArrayItem2Dim($aDiscussions, $iD, 'status'));
	if ( $sPriority === 'low' )
	$iPriorLowCnt += 1;
	elseif ( $sPriority === 'medium' )
	$iPriorMediumCnt += 1;
	elseif ( $sPriority === 'high' )
	$iPriorHighCnt += 1;
	else
	$iPriorNoneCnt += 1;
	if ( $sStatus === 'open' )
	$iStatusOpenCnt += 1;
	elseif ( $sStatus === 'awaiting review' )
	$iStatusAwaitCnt += 1;
	elseif ( $sStatus === 'closed' )
	$iStatusClosedCnt += 1;
	}
	}
	}
	echo '<div class="reviewsummary-line"><div class="reviewsummary-lvl1-col1">' . _glt('Review Status') . '</div><div class="reviewsummary-lvl1-col2">' . _h($sReviewStatus) . '</div></div>';
	echo '<div class="reviewsummary-line"><div class="reviewsummary-lvl1-col1">' . _glt('Start') . '</div><div class="reviewsummary-lvl1-col2">' . _h($sReviewStartDate) . '</div></div>';
	echo '<div class="reviewsummary-line"><div class="reviewsummary-lvl1-col1">' . _glt('End') . '</div><div class="reviewsummary-lvl1-col2">' . _h($sReviewEndDate) . '</div></div>';
	$iElementNotDiscussCnt = count($aNoDiscuss);
	echo '<div class="reviewsummary-line"><div class="reviewsummary-lvl1-col1">' . _glt('Elements discussed') . '</div><div class="reviewsummary-lvl1-col2">' . _h($iElementCnt) . '</div></div>';
	echo '<div class="reviewsummary-line"><div class="reviewsummary-lvl1-col1">' . _glt('Elements not discussed') . '</div><div class="reviewsummary-lvl1-col2">' . _h($iElementNotDiscussCnt) . '</div></div>';
	echo '<div class="reviewsummary-line"><div class="reviewsummary-lvl1-col1">' . _glt('Discussion Topics') . '</div><div class="reviewsummary-lvl1-col2">' . _h($iDiscussTopicCnt) . '</div></div>';
	echo '<div class="reviewsummary-line-lvl2-hdr">' . _glt('Priority') . '</div>';
	echo '<div class="reviewsummary-line-lvl2"><div class="reviewsummary-lvl2-col1"><img class="reviewsummary-lvl2-img propsprite-discusspriorityhigh" alt="" src="images/spriteplaceholder.png">&nbsp;' . _glt('High') . '</div><div class="reviewsummary-lvl2-col2">' . _h($iPriorHighCnt) . '</div></div>';
	echo '<div class="reviewsummary-line-lvl2"><div class="reviewsummary-lvl2-col1"><img class="reviewsummary-lvl2-img propsprite-discussprioritymed" alt="" src="images/spriteplaceholder.png">&nbsp;' . _glt('Medium') . '</div><div class="reviewsummary-lvl2-col2">' . _h($iPriorMediumCnt) . '</div></div>';
	echo '<div class="reviewsummary-line-lvl2"><div class="reviewsummary-lvl2-col1"><img class="reviewsummary-lvl2-img propsprite-discussprioritylow" alt="" src="images/spriteplaceholder.png">&nbsp;' . _glt('Low') . '</div><div class="reviewsummary-lvl2-col2">' . _h($iPriorLowCnt) . '</div></div>';
	echo '<div class="reviewsummary-line-lvl2"><div class="reviewsummary-lvl2-col1"><img class="reviewsummary-lvl2-img propsprite-discussprioritynone" alt="" src="images/spriteplaceholder.png"">&nbsp;' . _glt('<none>') . '</div><div class="reviewsummary-lvl2-col2">' . _h($iPriorNoneCnt) . '</div></div>';
	echo '<div class="reviewsummary-line-lvl2-hdr">' . _glt('Status') . '</div>';
	echo '<div class="reviewsummary-line-lvl2"><div class="reviewsummary-lvl2-col1"><img class="reviewsummary-lvl2-img propsprite-discussstatusopen" alt="" src="images/spriteplaceholder.png">&nbsp;' . _glt('Open') . '</div><div class="reviewsummary-lvl2-col2">' . _h($iStatusOpenCnt) . '</div></div>';
	echo '<div class="reviewsummary-line-lvl2"><div class="reviewsummary-lvl2-col1"><img class="reviewsummary-lvl2-img propsprite-discussstatusawait" alt="" src="images/spriteplaceholder.png">&nbsp;' . _glt('Awaiting Review') . '</div><div class="reviewsummary-lvl2-col2">' . _h($iStatusAwaitCnt) . '</div></div>';
	echo '<div class="reviewsummary-line-lvl2"><div class="reviewsummary-lvl2-col1"><img class="reviewsummary-lvl2-img propsprite-discussstatuscomplete" alt="" src="images/spriteplaceholder.png">&nbsp;' . _glt('Closed') . '</div><div class="reviewsummary-lvl2-col2">' . _h($iStatusClosedCnt) . '</div></div>';
	echo '</div>' . PHP_EOL;
	$iReviewDiagramCnt = count($aDiagrams);
	echo '<div class="properties-header" style="padding-top: 16px;">'. _glt('Review Diagrams') . '</div>';
	echo '<div id="reviewdiagram-section" class="properties-content">';
	if ($iReviewDiagramCnt > 0)
	{
	$sName 	= '';
	$sGUID 	= '';
	$sImageURL	= '';
	for ($i=0; $i<$iReviewDiagramCnt; $i++)
	{
	$sName = SafeGetArrayItem2Dim($aDiagrams, $i, 'name');
	$sGUID = SafeGetArrayItem2Dim($aDiagrams, $i, 'guid');
	$sImageURL = SafeGetArrayItem2Dim($aDiagrams, $i, 'imageurl');
	echo '<div class="reviewsummary-line w3-link"><div onclick="LoadObject(\'' . _j($sGUID) . '\',\'false\',\'\',\'\',\'' . _j($sName) . '\',\'' . _j($sImageURL) . '\')">';
	echo '<img alt="" title="Diagram" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '">&nbsp;' . _h($sName) . '</div></div>';
	}
	}
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionReviewDiscussions($a, $aNoDiscuss)
	{
	echo '<div id="reviewdiscussion-section" class="properties-content">';
	$iElementCnt = count($aNoDiscuss);
	for ($iEle=0; $iEle<$iElementCnt; $iEle++)
	{
	$sObjName 	= SafeGetArrayItem2Dim($aNoDiscuss, $iEle, 'name');
	$sObjType 	= SafeGetArrayItem2Dim($aNoDiscuss, $iEle, 'type');
	$sObjResType= SafeGetArrayItem2Dim($aNoDiscuss, $iEle, 'restype');
	$sObjGUID 	= SafeGetArrayItem2Dim($aNoDiscuss, $iEle, 'guid');
	$sObjStereo = SafeGetArrayItem2Dim($aNoDiscuss, $iEle, 'stereotype');
	$sObjImageURL = SafeGetArrayItem2Dim($aNoDiscuss, $iEle, 'imageurl');
	$sObjName 	= GetPlainDisplayName($sObjName);
	echo '<div class="reviewdiscussion-item">';
	if ( !strIsEmpty($sObjType) )
	{
	echo '<div id=reviewdiscussion-element-link class="w3-link" onclick="LoadObject(\'' . _j($sObjGUID) . '\',\'false\',\'props\',\'\',\'' . _j($sObjName) . '\',\'' . _j($sObjImageURL) . '\')">';
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName($sObjImageURL) . '" src="images/spriteplaceholder.png" alt="">&nbsp;' . _h($sObjName);
	echo '</div>';
	}
	echo '</div>';
	}
	$iElementCnt = count($a);
	for ($iEle=$iElementCnt; $iEle>0; $iEle--)
	{
	$sObjName 	= SafeGetArrayItem2Dim($a, $iEle, 'name');
	$sObjType 	= SafeGetArrayItem2Dim($a, $iEle, 'type');
	$sObjResType= SafeGetArrayItem2Dim($a, $iEle, 'restype');
	$sObjGUID 	= SafeGetArrayItem2Dim($a, $iEle, 'guid');
	$sObjStereo = SafeGetArrayItem2Dim($a, $iEle, 'stereotype');
	$sObjImageURL = SafeGetArrayItem2Dim($a, $iEle, 'imageurl');
	echo '<div class="reviewdiscussion-item">';
	if( !strIsEmpty($sObjType) && !strIsEmpty($sObjName) )
	{
	echo '<div id=reviewdiscussion-element-link class="w3-link" onclick="LoadObject(\'' . _j($sObjGUID) . '\',\'false\',\'props\',\'\',\'' . _j($sObjName) . '\',\'' . _j($sObjImageURL) . '\')">';
	echo '<img class="mainprop-object-image ' . GetObjectImageSpriteName($sObjImageURL) . '" src="images/spriteplaceholder.png" alt="">&nbsp;' . _h($sObjName);
	echo '</div>';
	if (array_key_exists(("discussions"), $a[$iEle]))
	{
	echo '<div class="reviewdiscussion-item-topics">';
	$aDiscussions = $a[$iEle]['discussions'];
	$iDiscussCnt = count($aDiscussions);
	for ($iD=0; $iD<$iDiscussCnt; $iD++)
	{
	$sDiscussText 	= SafeGetArrayItem2Dim($aDiscussions, $iD, 'discussion');
	$sDiscussGUID 	= SafeGetArrayItem2Dim($aDiscussions, $iD, 'guid');
	$sPriority	= SafeGetArrayItem2Dim($aDiscussions, $iD, 'priority');
	$sPriorityImageClass = SafeGetArrayItem2Dim($aDiscussions, $iD, 'priorityimageclass');
	$sPriorityTooltip = str_replace('%PRIORITY%', $sPriority, _glt('Priority: xx'));
	$sStatus 	= SafeGetArrayItem2Dim($aDiscussions, $iD, 'status');
	$sStatusImageClass = SafeGetArrayItem2Dim($aDiscussions, $iD, 'statusimageclass');
	$sStatusTooltip = str_replace('%STATUS%', $sStatus, _glt('Status: xx'));
	$aReplies = null;
	$iReplyCnt = 0;
	if (array_key_exists(("replies"), $aDiscussions[$iD]))
	{
	$aReplies = $aDiscussions[$iD]['replies'];
	$iReplyCnt = count($aReplies);
	}
	if ($iReplyCnt > 0)
	{
	echo '<div class="reviewdiscussion-discussion-item collapsible-section-header-closed">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="reviewdiscussion-discussion-item-icon collapsible-section-header-closed-icon show-cursor-pointer" onclick="OnToggleReviewDiscussionReplies(\'' . _j($sDiscussGUID) . '\')">';
	}
	else
	{
	echo '<div class="reviewdiscussion-discussion-item">';
	}
	echo WriteAvatarImage($aDiscussions[$iD]['avatarid'], 'false');
	echo '<div class="reviewdiscussion-discussion-item-states" >';
	echo '<span id="review-prioritymenu-button"><img alt="" src="images/spriteplaceholder.png" class="' . _h($sPriorityImageClass) . '" title="' . _h($sPriorityTooltip) . '" height="16" width="16">&nbsp;</span>';
	echo '<span id="review-statusmenu-button"><img alt="" src="images/spriteplaceholder.png" class="' . _h($sStatusImageClass) . '" title="' . _h($sStatusTooltip) . '" height="16" width="16">&nbsp;</span>';
	echo '</div>';
	echo '<div class="reviewdiscussion-discussion-item-text' . ($iReplyCnt > 0 ? ' show-cursor-pointer' : '') . '" onclick="OnToggleReviewDiscussionReplies(\'' . _j($sDiscussGUID) . '\')">' . _hRichText($sDiscussText) . '</div>';
	echo '<div class="reviewdiscussion-discussion-item-text-footer">';
	echo '<div class="reviewdiscussion-discussion-item-text-footer-dateauthor">' . _h($aDiscussions[$iD]['created']) . '&nbsp; &nbsp;' . _h($aDiscussions[$iD]['author']) . '</div>';
	echo '</div>';
	if ($iReplyCnt > 0)
	{
	echo '<div class="reviewdiscussion-discussion-item-replies" id="mpreplies_' . _h($sDiscussGUID) . '">';
	for ($iR=0; $iR<$iReplyCnt; $iR++)
	{
	$sReplyAuthor 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyauthor');
	$sReplyCreated 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replycreated');
	$sReplyText 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replytext');
	$sReplyAvatarID 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyavatarid');
	$sReplyAvatarImage 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyavatarimage');
	echo '<div class="reviewdiscussion-discussion-item-reply">';
	WriteAvatarImage($sReplyAvatarID, 'true');
	echo '<div class="reviewdiscussion-discussion-item-reply-text">' . _hRichText($sReplyText) . '</div>';
	echo '<div class="reviewdiscussion-discussion-item-reply-text-footer">';
	echo '<div class="reviewdiscussion-discussion-item-reply-text-footer-dateauthor">' . _h($sReplyCreated) . '&nbsp; &nbsp;' . _h($sReplyAuthor) . '</div>';
	echo '</div>';
	echo '</div>';
	}
	echo '</div>';
	}
	echo '</div>';
	}
	echo '</div>';
	}
	}
	echo '</div>';
	if ($iEle<$iElementCnt-1)
	{
	echo '<hr class="reviewdiscussion-hr">';
	}
	}
	echo '</div>' . PHP_EOL;
	}
	function ConvertTVXMLToArray($sXML)
	{
	$aTV = array();
	$xmlDoc = new DOMDocument();
	$validXML = SafeXMLLoad($xmlDoc, $sXML);
	if ($xmlDoc !== null && $validXML)
	{
	$xnRoot = $xmlDoc->documentElement;
	if ($xnRoot->nodeName === 'tagStructure')
	{
	foreach ($xnRoot->childNodes as $xnProp)
	{
	$aRow	= array();
	GetXMLNodeValueAttr($xnProp, 'property', 'name', $aRow['name']);
	GetXMLNodeValue($xnProp, 'property', $aRow['value']);
	$aTV[] 	= $aRow;
	}
	}
	else if ($xnRoot->nodeName === 'Checklist')
	{
	foreach ($xnRoot->childNodes as $xnProp)
	{
	$aRow	= array();
	GetXMLNodeValueAttr($xnProp, 'Item', 'Text', $aRow['text']);
	GetXMLNodeValueAttr($xnProp, 'Item', 'Checked', $aRow['checked']);
	$aTV[] 	= $aRow;
	}
	}
	}
	return $aTV;
	}
	function WriteSectionTest($a, $sObjectGUID, $bObjectLocked, $sObjectName, $sObjectImageURL, $sLinkType, $sObjectHyper, $bIsMini=false)
	{
	if ($bIsMini)
	{
	echo '<div id="test-mini-section"  class="propsview-section" style="display:block">';
	echo '<div class="miniprops-header">Tests</div>';
	$iCnt = count($a);
	for ($i=0; $i<$iCnt; $i++)
	{
	$sTestItemExInfo = '';
	$sType 	= SafeGetArrayItem2Dim($a, $i, 'type');
	$sClassType = SafeGetArrayItem2Dim($a, $i, 'classtype');
	$sName	 	= SafeGetArrayItem2Dim($a, $i, 'name');
	$sGUID	 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	$sStatus 	= SafeGetArrayItem2Dim($a, $i, 'status');
	$sLrun 	= SafeGetArrayItem2Dim($a, $i, 'lastrun');
	$sRunBy 	= SafeGetArrayItem2Dim($a, $i, 'runby');
	$sChkdBy 	= SafeGetArrayItem2Dim($a, $i, 'checkedby');
	$testID = 'test_' . StripSpecialChars($sClassType) . '_' . StripSpecialChars($sName);
	echo '<div class="test-item property-section-item">';
	if ( !$bObjectLocked )
	{
	if ( IsSessionSettingTrue('login_perm_test') )
	{
	if (IsSessionSettingTrue('edit_objectfeature_tests'))
	{
	echo '<div class="test-item-edit">';
	echo '<input class="test-item-edit-button" type="button" value="&#160;" onclick="EditTest(\'' . _j($sObjectGUID). '\',\''._j($sClassType).'\',\''._j($sName).'\',\''. _j($testID). '\')" />';
	echo '</div>';
	}
	}
	}
	$sTestItemHdr  = '<img alt="" src="images/spriteplaceholder.png" class="test-item-icon">';
	$sTestItemHdr .= '<div class="test-name">';
	$s = '';
	if ( !strIsEmpty($sType) )
	$s .= $sType . ' ';
	if ( !strIsEmpty($sClassType) )
	$s .= '<span class="test-classtype">' . _h($sClassType) . '</span> ';
	if ( !strIsEmpty($s) )
	{
	$s = substr($s, 0, strlen($s)-1);
	$s .= '.  ';
	}
	$sTestItemHdr .= $s . $sName;
	$sTestItemHdr .= '</div>';
	$sTestItemHdr .= '<div class="test-runstate">';
	if ($sStatus==='Not Run' )
	{
	$sTestItemHdr .= '<img alt="" src="images/spriteplaceholder.png" class="test-runstate-notrun"><span style="padding-left: 10px;">' . _glt('Not run yet') . '</span>';
	}
	else
	{
	$sStatusClassName = mb_strtolower($sStatus);
	if ((stripos(',deferred,fail,not run yet,pass,cancelled,', ',' . $sStatusClassName . ',')!==false))
	$sStatusClassName = 'test-runstate-' . $sStatusClassName;
	else
	$sStatusClassName = 'test-runstate-userdef';
	$sTestItemHdr .= '<div class="test-runstate-status"><img alt="" src="images/spriteplaceholder.png" class="' . _h($sStatusClassName) . '"><span style="padding-left: 10px;">' . _h($sStatus) . '</span></div>';
	}
	$sTestItemHdr .= '</div>';
	if ( !strIsEmpty($sLrun) && !strIsEmpty($sRunBy) )
	{
	$sTestItemExInfo .= '<span class="test-status-label"> [ ' . _glt('Last Run at') . '</span>';
	$sTestItemExInfo .= '<span class="test-status-value">' . $sLrun . '</span>';
	$sTestItemExInfo .= '<span class="test-status-label"> ' . _glt('by') . ' </span><span class="test-status-value">' . _h($sRunBy) . '</span>';
	if ( !strIsEmpty($sChkdBy) )
	{
	$sTestItemExInfo .= '<span class="test-status-label">' . _glt('and checked by') . ' </span><span class="test-status-value">' . _h($sChkdBy) . '</span>';
	}
	$sTestItemExInfo .= '<span class="test-status-label"> ]</span>';
	}
	$sDesc = SafeGetArrayItem2Dim($a, $i, 'notes');
	$sInput = SafeGetArrayItem2Dim($a, $i, 'input');
	$sAccptCr = SafeGetArrayItem2Dim($a, $i, 'acceptance');
	$sResults = SafeGetArrayItem2Dim($a, $i, 'results');
	if ( !strIsEmpty($sDesc) )
	{
	$sTestItemExInfo .= '<div class="testblock-desc-label">' . _glt('Description') . ':</div><div class="testblock-desc">' . _hRichText($sDesc) . '</div>';
	}
	if ( !strIsEmpty($sInput) )
	{
	$sTestItemExInfo .= '<div class="testblock-input-label">' . _glt('Input') . ':</div><div class="testblock-input">' . _hRichText($sInput) . '</div>';
	}
	if ( !strIsEmpty($sAccptCr) )
	{
	$sTestItemExInfo .= '<div class="testblock-acceptance-label">' . _glt('Acceptance Criteria') . ':</div><div class="testblock-acceptance">'. _hRichText($sAccptCr) .'</div>';
	}
	if ( !strIsEmpty($sResults) )
	{
	$sTestItemExInfo .= '<div class="testblock-results-label">' . _glt('Results') . ':</div><div class="testblock-results">' . _hRichText($sResults) . '</div>';
	}
	if ( !strIsEmpty($sTestItemExInfo) )
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-closed">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $sTestItemHdr . '</div>';
	echo '<div class="collapsible-section w3-hide">';
	echo '<div class="test-ex-info" >';
	echo $sTestItemExInfo;
	echo '</div>';
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo $sTestItemHdr;
	echo '</div>';
	}
	echo '</div>'. PHP_EOL;
	}
	echo '</div>' . PHP_EOL;
	}
	else
	{
	if ($sLinkType === 'props-tests')
	{
	echo '<div id="test-section" style="display:block;">';
	}
	else
	{
	$sSectionID = 'test-section';
	echo '<div id="test-section" class="property-section" style="'.GetSectionVisibility($sSectionID).'">';
	}
	$sTableAttr = 'id="test-table" class="property-table"';
	$aHeader = ['', 'Test', 'Status', 'Class', 'Type', 'Last Run'];
	$aFields = ['icon', 'name', 'statusicon', 'classtype', 'type', 'lastrun'];
	$aFieldMap = [
	['label' => 'Run By', 'value' => 'runby'],
	['label' => 'Checked By', 'value' => 'checkedby'],
	['label' => 'Description', 'value' => 'notes'],
	['label' => 'Input', 'value' => 'input'],
	['label' => 'Acceptance Criteria', 'value' => 'acceptance'],
	['label' => 'Results', 'value' => 'results']
	];
	$i = 0;
	foreach ($a as &$row)
	{
	if ($row['status']==='Not Run' )
	{
	$row['statusicon'] = '&nbsp&nbsp&nbsp<img alt="" src="images/spriteplaceholder.png" class="test-runstate-notrun" title="' . _h($row['status']) . '">';
	}
	else
	{
	$sStatusClassName = mb_strtolower($row['status']);
	if ((stripos(',deferred,fail,not run yet,pass,cancelled,', ',' . $sStatusClassName . ',')!==false))
	$sStatusClassName = 'test-runstate-' . $sStatusClassName;
	else
	$sStatusClassName = 'test-runstate-userdef';
	$row['statusicon'] = '&nbsp&nbsp&nbsp<img alt="" src="images/spriteplaceholder.png" class="' . _h($sStatusClassName) .'" title="' . _h($row['status']) . '">';
	}
	$testID = 'test_' . StripSpecialChars($row['classtype']) . '_' . StripSpecialChars($row['name']);
	$row['tr_attributes'] = 'id="main_'.$testID.'"';
	$row['icon'] = '<div class="expand-icon"></div>';
	$sDetails = '';
	$sDetails .= '<input class="test-item-edit-button" style="float:right;" value="&nbsp;" onclick="EditTest(\'' . _j($sObjectGUID). '\',\''._j($row['classtype']).'\',\''._j($row['name']).'\',\''. _j($testID). '\')" type="button">';
	$sDetails .= WriteDataFields($row, $aFieldMap);
	$row['tr_details'] = $sDetails;
	$i++;
	}
	echo '<div id="test-list">';
	echo '<div class="properties-header">Tests</div>';
	WriteListTable($sTableAttr, $aHeader, $a, $aFields);
	echo '</div>';
	echo '</div>';
	}
	}
	function WritePropertyNoteField($sLabel, $sValue, $sLabelAttr = '', $sInputAttr = '')
	{
	echo '<div class="prop-field">';
	echo '<div class="prop-label-textarea">';
	echo $sLabel . ':';
	echo '</div>';
	echo '<div class="prop-field-textarea">';
	echo _hRichText($sValue);
	echo '</div>';
	echo '</div>';
	}
	function WritePropertyField($sLabel, $sValue, $sLabelAttr = '', $sInputAttr = '')
	{
	echo '<div class="prop-field">';
	echo '<div class="prop-label" '.$sLabelAttr.'>';
	echo $sLabel . ':';
	echo '</div>';
	echo '<div class="prop-text">';
	echo '<div ' . $sInputAttr . ' class="prop-text-field">' . _h($sValue) . '</div>';
	echo '</div>';
	echo '</div>';
	}
	function WriteListTable($sTableAttr, $aHeader, $aData, $aFields)
	{
	echo '<table '.$sTableAttr.'>';
	echo '<tbody>';
	echo '<tr>';
	foreach ($aHeader as $header)
	{
	if(!strIsEmpty($header))
	$header = _glt($header);
	echo '<th>'.$header.'</th>';
	}
	echo '</tr>';
	foreach ($aData as $aRow)
	{
	echo '<tr class="tr-expand" '.SafeGetArrayItem1Dim($aRow,'tr_attributes').'>';
	$i=0;
	foreach ($aFields as $field)
	{
	$sFieldContent = SafeGetArrayItem1Dim($aRow, $field);
	if (($field != 'statusicon') &&
	($field != 'icon'))
	{
	$sFieldContent = _h($sFieldContent);
	}
	echo '<td title="'.$aHeader[$i].'">'.$sFieldContent.'</td>';
	$i++;
	}
	echo '</tr>';
	echo '<tr class="tr-details" ><td colspan="6">'.SafeGetArrayItem1Dim($aRow,'tr_details'). '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	}
	function WriteSectionResourceAllocs($a, $sObjectGUID, $bObjectLocked, $sObjectName, $sObjectImageURL, $sLinkType, $sObjectHyper, $bIsMini=false)
	{
	$sHeading = _glt('Resources');
	if(empty($a))
	{
	echo WriteSectionEmpty($sHeading);
	return;
	}
	if($bIsMini)
	{
	echo '<div id="resource-mini-section" class="propsview-section" style="display:block">';
	echo '<div class="miniprops-header">' . $sHeading . '</div>';
	$iCnt = count($a);
	for ($i=0;$i<$iCnt;$i++)
	{
	$sResource 	= SafeGetArrayItem2Dim($a, $i, 'resource');
	$sRole 	= SafeGetArrayItem2Dim($a, $i, 'role');
	$sGUID 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	echo '<div class="resource-item property-section-item property-section-item">';
	if ( !$bObjectLocked )
	{
	if ( IsSessionSettingTrue('login_perm_resalloc') )
	{
	if (IsSessionSettingTrue('edit_objectfeature_resources'))
	{
	$sPropID = 'resalloc_' . StripSpecialChars($sResource) . '_' . StripSpecialChars($sRole);
	echo '<div class="resource-item-edit">';
	echo '<input class="resource-item-edit-button" type="button" value="&#160;" onclick="EditResource(\'' .  _j($sObjectGUID) . '\',\'' . _j($sResource) . '\',\'' . _j($sRole) . '\')" />';
	echo '</div>';
	}
	}
	}
	$sResourceItemHdr  = '<img alt="" src="images/spriteplaceholder.png" class="resource-item-icon">';
	$sResourceItemHdr .= '<div class="resources-name">';
	$sResourceItemHdr .= _h($sResource) . '&nbsp;-&nbsp;' . _h($sRole) . '</div>';
	$sStartDt =  SafeGetArrayItem2Dim($a, $i, 'sdate');
	$sEndDt =  SafeGetArrayItem2Dim($a, $i, 'edate');
	if ( !strIsEmpty($sStartDt) || !strIsEmpty($sEndDt) )
	{
	$sResourceItemHdr .= '<div class="resources-dates1">';
	$sResourceItemHdr .= '<div class="resources-date-line">';
	if ( !strIsEmpty($sStartDt) )
	{
	$sResourceItemHdr .= '<span class="resources-date-value">' . _h($sStartDt) . '&nbsp;</span>';
	}
	if ( !strIsEmpty($sEndDt) )
	{
	$sResourceItemHdr .= '<span class="resources-date-label2">' . _glt('until') . '</span><span class="resources-date-value">' . _h($sEndDt) . '</span>';
	}
	$sResourceItemHdr .= '</div>';
	$sResourceItemHdr .= '</div>';
	}
	$sPercentage = SafeGetArrayItem2Dim($a, $i, 'percentage');
	$sPercentage = Trim($sPercentage, '.');
	if ( !strIsEmpty($sPercentage) )
	{
	$sResourceItemHdr .= '<div class="resources-dates2">';
	$sResourceItemHdr .= '<div class="resources-date-line">';
	$sResourceItemHdr .= '<span class="resources-date-value">' . _h($sPercentage) . '&#37;&nbsp;</span><span class="resources-date-label">' . _glt('completed') . '</span>';
	$sResourceItemHdr .= '</div>';
	$sResourceItemHdr .= '</div>';
	}
	$sNotes 	= SafeGetArrayItem2Dim($a, $i, 'notes');
	$sHistory 	= SafeGetArrayItem2Dim($a, $i, 'history');
	$sAllocated = SafeGetArrayItem2Dim($a, $i, 'atime');
	$sExpected 	= SafeGetArrayItem2Dim($a, $i, 'exptime');
	$sExpended 	= SafeGetArrayItem2Dim($a, $i, 'expendtime');
	if ( !strIsEmpty($sNotes) || !strIsEmpty($sHistory) || (!strIsEmpty($sAllocated) && $sAllocated !== '0') ||
	(!strIsEmpty($sExpected) && $sExpected !== "0") || (!strIsEmpty($sExpended) && $sExpended !== "0"))
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-closed">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $sResourceItemHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-hide">';
	if ( !strIsEmpty($sNotes) )
	{
	echo '<div class="resources-notes">' . _hRichText($sNotes) . '</div>';
	}
	if ( !strIsEmpty($sHistory) )
	{
	echo '<div class="resources-history-header">History: </div><div class="resources-history">' . _hRichText($sHistory) . '</div>';
	}
	if ( ( !strIsEmpty($sAllocated) && $sAllocated !== "0") ||
	 ( !strIsEmpty($sExpected) && $sExpected !== "0") ||
	 ( !strIsEmpty($sExpended) && $sExpended != "0") )
	{
	echo '<div class="resources-times-div"><div class="resources-times">';
	echo '<span class="resources-time-label"> (' . _glt('Actual') . ':</span><span class="resources-time-value">' . _h($sExpended) . '</span>';
	echo '<span class="resources-time-label"> ' . _glt('Expected') . ':</span><span class="resources-time-value">' . _h($sExpected) . '</span>';
	echo '<span class="resources-time-label"> ' . _glt('Time') . ':</span><span class="resources-time-value">' . _h($sAllocated) . '</span><span class="resources-time-label"> )</span>';
	echo '</div></div>';
	}
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo $sResourceItemHdr;
	echo '</div>';
	}
	echo '</div>'. PHP_EOL;
	}
	echo '</div>' . PHP_EOL;
	}
	else
	{
	$propType = 'resource';
	$aHeader = ['', 'Resource', 'Task', '% Complete', 'Start', 'End'];
	$aFields = ['icon', 'resource', 'role', 'percentage', 'sdate', 'edate'];
	$aFieldMap = [
	['label' => 'Expected time', 'value' => 'exptime'],
	['label' => 'Allocated time', 'value' => 'atime'],
	['label' => 'Time expended', 'value' => 'expendtime'],
	['label' => 'Description', 'value' => 'notes'],
	['label' => 'History', 'value' => 'history']
	];
	$sTableAttr = 'id="'.$propType.'-table" class="property-table"';
	$sPropDetailsID = 'props-'.$propType.'-details';
	$i=0;
	foreach ($a as &$row)
	{
	$sResource = SafeGetArrayItem1Dim($row, 'resource');
	$sRole = SafeGetArrayItem1Dim($row, 'role');
	$sPropID = 'resalloc_' . StripSpecialChars($sResource) . '_' . StripSpecialChars($sRole);
	$row['tr_attributes'] = 'id="main_'.$sPropID.'"';
	$row['icon'] = '<div class="expand-icon"></div>';
	$sDetails = '';
	$sDetails .= '<div>';
	$sDetails .= '<input class="'.$propType.'-item-edit-button" title="Edit Resource" value="&nbsp;" style="float:right;" onclick="EditResource(\'' .  _j($sObjectGUID) . '\',\'' . _j($sResource) . '\',\'' . _j($sRole) . '\',\'' . _j($sPropID) . '\')" type="button">';
	$sDetails .= WriteDataFields($row, $aFieldMap);
	$sDetails .= '</div>';
	$row['tr_details'] = $sDetails;
	$i++;
	}
	$sSectionID = 'resource-section';
	echo '<div id="'._h($sSectionID).'" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div id="'.$propType.'-list">';
	echo '<div class="properties-header">Resources</div>';
	WriteListTable($sTableAttr, $aHeader, $a, $aFields);
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	}
	function WriteSectionRunStates($a)
	{
	$sSectionID = 'runstate-section';
	echo '<div id="runstate-section" class="property-section" style="'.GetSectionVisibility($sSectionID).'">';
	echo '<div class="properties-header">Run States</div>';
	echo '<div class="properties-content">';
	$iCnt = count($a);
	for ($i=0; $i<$iCnt; $i++)
	{
	$sName 	= SafeGetArrayItem2Dim($a, $i, 'name');
	$sGUID 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	$sValue 	= SafeGetArrayItem2Dim($a, $i, 'value');
	$sOperator 	= SafeGetArrayItem2Dim($a, $i, 'operator');
	$sOperator 	= str_replace('<![CDATA[', '', $sOperator);
	$sOperator 	= str_replace("]]>","", $sOperator);
	echo '<div class="runstate-item property-section-item">';
	$sRunStateItemHdr  = '<div class="runstate-item-hdr">';
	$sRunStateItemHdr .= '<img alt="" src="images/spriteplaceholder.png" class="runstate-item-icon">';
	$sRunStateItemHdr .= '<div class="runstate-name">' . _h($sName);
	$sRunStateItemHdr .= WriteValueInSentence(_h($sOperator), '&nbsp;', '&nbsp;', false);
	$sRunStateItemHdr .= WriteValueInSentence(_h($sValue), '&nbsp;', '', false);
	$sRunStateItemHdr .= '</div>';
	$sRunStateItemHdr .= '</div>';
	$sNotes 	= SafeGetArrayItem2Dim($a, $i, 'notes');
	$sRunStateExInfo = '';
	if ( !strIsEmpty($sNotes) )
	{
	$sRunStateExInfo .= '<div class="runstate-notes">' . _h($sNotes) . '</div>';
	}
	if ( !strIsEmpty($sRunStateExInfo) )
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-closed">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $sRunStateItemHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-hide">';
	echo $sRunStateExInfo;
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo $sRunStateItemHdr;
	echo '</div>';
	}
	echo '</div>' . PHP_EOL;
	}
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionLocation($sInternalName, $sSectionName, $sDefault, $aP, $a, $bIsMini=false)
	{
	if (empty($aP) && count($a) <= 0)
	{
	return;
	}
	if ($bIsMini)
	{
	$sSectionID = 'location-mini-section';
	echo '<div id="' . _h($sSectionID) . '" style="display:block">';
	echo '<div class="miniprops-header">Usage</div>';
	echo '<div class="miniprops-content">';
	}
	else
	{
	$sSectionID = 'location-section';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">Usage</div>';
	echo '<div class="properties-content">';
	}
	if (empty($aP) === false)
	{
	$sName 	= SafeGetArrayItem1Dim($aP, 'text');
	$sName 	= GetPlainDisplayName($sName);
	$sGUID 	= SafeGetArrayItem1Dim($aP, 'guid');
	$sResType 	= SafeGetArrayItem1Dim($aP, 'restype');
	$sImageURL	= SafeGetArrayItem1Dim($aP, 'imageurl');
	if ( strIsEmpty($sGUID) && $sName===_glt('<Unnamed object>'))
	{
	SafeStartSession();
	$sName = isset($_SESSION['model_name']) ? $_SESSION['model_name'] : 'Root';
	$sImageURL = 'home.png';
	}
	echo '<div id="parent-section">';
	echo '<div class="parent-desc w3-link" onclick="LoadObject(\'' . _j($sGUID) . '\',\'true\',\'\',\'\',\'' . _j($sName) . '\',\'' . _j($sImageURL) . '\')">';
	echo '<img alt="" title="' . _h($sResType) . '" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '"> ' . _h($sName);
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	if (empty($a) === false)
	{
	$aD = $a['diagrams'];
	echo '<div id="usage-section">';
	$iCnt = count($aD);
	if ($iCnt > 0)
	{
	$sName 	= '';
	$sGUID 	= '';
	$sImageURL	= '';
	echo '<table class="usage-table">';
	for ($i=0; $i<$iCnt; $i++)
	{
	$sName = SafeGetArrayItem2Dim($aD, $i, 'name');
	$sGUID = SafeGetArrayItem2Dim($aD, $i, 'guid');
	$sImageURL = SafeGetArrayItem2Dim($aD, $i, 'imageurl');
	$aClassifierUsage = SafeGetArrayItem2Dim($aD, $i, 'classifierusage');
	echo '<tr><td class="usage-name w3-link" onclick="LoadObject(\'' . _j($sGUID) . '\',\'false\',\'\',\'\',\'' . _j($sName) . '\',\'' . _j($sImageURL) . '\')" >';
	echo '<img alt="" title="Diagram" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '"> ' . _h($sName);
	echo '</td></tr>'. PHP_EOL;
	}
	echo '</table>';
	}
	echo '</div>' . PHP_EOL;
	}
	echo '</div>';
	if ((array_key_exists('instances',$a)) && (count($a['instances']) > 0))
	{
	$aI = $a['instances'];
	if ($bIsMini)
	{
	echo '<div class="miniprops-header" style="padding-top:0px;">Instances</div>';
	echo '<div class="miniprops-content">';
	}
	else
	{
	echo '<div class="properties-header">Instances</div>';
	echo '<div class="properties-content">';
	}
	if (empty($a) === false)
	{
	$iCnt = count($aI);
	if ($iCnt > 0)
	{
	$sName 	= '';
	$sGUID 	= '';
	$sImageURL	= '';
	echo '<table class="usage-table">';
	for ($i=0; $i<$iCnt; $i++)
	{
	$sName = SafeGetArrayItem2Dim($aI, $i, 'name');
	$sGUID = SafeGetArrayItem2Dim($aI, $i, 'guid');
	$sImageURL = SafeGetArrayItem2Dim($aI, $i, 'imageurl');
	$aClassifierUsage = SafeGetArrayItem2Dim($aI, $i, 'classifierusage');
	echo '<tr><td class="usage-name w3-link" onclick="LoadObject(\'' . _j($sGUID) . '\',\'false\',\'\',\'\',\'' . _j($sName) . '\',\'' . _j($sImageURL) . '\')" >';
	echo '<img alt="" title="Diagram" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sImageURL) . '"> ' . _h($sName);
	echo '</td></tr>'. PHP_EOL;
	foreach ($aClassifierUsage as $aClassifierDiagram)
	{
	echo '<tr><td class="instance-diagram-name w3-link" onclick="LoadObject(\'' . _j($aClassifierDiagram['guid']) . '\',\'false\',\'\',\'\',\'' . _j($aClassifierDiagram['name']) . '\',\'' . _j($aClassifierDiagram['imageurl']) . '\')">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($aClassifierDiagram['imageurl']) . '"> ' . _h($aClassifierDiagram['name']);
	echo '</td></tr>'. PHP_EOL;
	}
	}
	echo '</table>';
	}
	}
	echo '</div>';
	}
	echo '</div>';
	}
	function WriteSectionComments($sInternalName, $sSectionName, $sDefault, $aComments, $bIsMini, $sObjectGUID, $sCommentID)
	{
	$sVisibleCommentID = $sCommentID;
	if (empty($aComments))
	{
	return;
	}
	if ($bIsMini)
	{
	$sSectionID = 'comments-mini-section';
	echo '<div id="' . _h($sSectionID) . '" style="display:block">';
	echo '<div class="miniprops-header">Comments</div>';
	echo '<div class="miniprops-content">';
	}
	else
	{
	$sSectionID = 'comments-section';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">Comments</div>';
	echo '<div class="properties-content">';
	}
	if (empty($aComments) === false)
	{
	echo '<div id="comments-section-contents">';
	foreach ($aComments as $aComment)
	{
	$sCreated = SafeGetArrayItem1Dim($aComment, 'created');
	$sDescription = SafeGetArrayItem1Dim($aComment, 'description');
	$sCommentID = SafeGetArrayItem1Dim($aComment, 'identifier');
	$sDisplayCreatedDate = date("y-M-d", strtotime($sCreated));
	StripCDATA($sDescription);
	echo '<div class="comment-container" commentid="'._h($sCommentID).'" '.'>';
	echo '<div class="comment-date-line">';
	echo '<div class="comment-date">' .$sDisplayCreatedDate. '</div>';
	echo '</div>';
	echo '<div class="comment-description-text">' ._hRichText($sDescription). '</div>';
	echo '</div>';
	}
	if ($sVisibleCommentID !== 'all')
	{
	echo '<button class="comments-show-all-button" onclick="ShowCommentsAll(\''.$sObjectGUID.'\')">Show All</button>';
	}
	echo '</div>' . PHP_EOL;
	}
	echo '</div>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
	}
	function WriteSectionRelationships($sInternalName, $sSectionName, $sDefault, $a, $bIsMini=false)
	{
	$sHeading = _glt('Relationships');
	if(empty($a))
	{
	echo WriteSectionEmpty($sHeading);
	return;
	}
	if ($bIsMini)
	{
	$sSectionID = 'relationship-mini-section';
	echo '<div id="' . _h($sSectionID) . '" class="propsview-section" style="display:block">';
	echo '<div class="miniprops-header">' . $sHeading . '</div>';
	echo '<div class="miniprops-content">';
	$iCnt = count($a);
	if ($iCnt > 0)
	{
	$iOutCnt = 0;
	$iInCnt = 0;
	for ($i=0; $i<$iCnt; $i++)
	{
	$sLinkDirection	= SafeGetArrayItem2Dim($a, $i, 'linkdirection');
	if ($sLinkDirection	=== 'Outgoing')
	{
	$iOutCnt = $iOutCnt + 1;
	}
	if ($sLinkDirection	=== 'Incoming')
	{
	$iInCnt = $iInCnt + 1;
	}
	}
	if ( $iOutCnt > 0 )
	{
	echo '<div class="relationship-outgoing">';
	for ($i=0; $i<$iCnt; $i++)
	{
	$sLinkDirection	= SafeGetArrayItem2Dim($a, $i, 'linkdirection');
	if ($sLinkDirection	=== 'Outgoing')
	{
	$sConnectorName = SafeGetArrayItem2Dim($a, $i, 'connectorname');
	$sConnectorType = SafeGetArrayItem2Dim($a, $i, 'connectortype');
	$sConnectorName2= $sConnectorType . ' connector ' . $sConnectorName;
	$sConnectorGUID = SafeGetArrayItem2Dim($a, $i, 'connectorguid');
	$sConnectorImageURL = SafeGetArrayItem2Dim($a, $i, 'connectorimageurl');
	$sElementName 	= SafeGetArrayItem2Dim($a, $i, 'elementname');
	$sElementName 	= GetPlainDisplayName($sElementName);
	$sElementGUID 	= SafeGetArrayItem2Dim($a, $i, 'elementguid');
	$sElementImageURL = SafeGetArrayItem2Dim($a, $i, 'elementimageurl');
	$sDirection 	= SafeGetArrayItem2Dim($a, $i, 'direction');
	echo '<div class="relationship-item">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="relationship-item-out">';
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sConnectorGUID) . '\',\'false\',\'\',\'\',\'' . _j($sConnectorName2) . '\',\'' . _j($sConnectorImageURL) . '\')">';
	echo _h($sConnectorType) . '</a>';
	echo '&nbsp;' . _glt('to') . '&nbsp;';
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sElementGUID) . '\',\'false\',\'\',\'\',\'' . _j($sElementName) . '\',\'' . _j($sElementImageURL) . '\')">';
	echo '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sElementImageURL) . '" alt="">&nbsp;';
	echo _h($sElementName);
	echo '</a>';
	echo '</div>'. PHP_EOL;
	}
	}
	echo '</div>';
	}
	if ( $iInCnt > 0 )
	{
	echo '<div class="relationship-incoming">';
	for ($i=0; $i<$iCnt; $i++)
	{
	$sLinkDirection	= SafeGetArrayItem2Dim($a, $i, 'linkdirection');
	if ($sLinkDirection	=== 'Incoming')
	{
	$sConnectorName = SafeGetArrayItem2Dim($a, $i, 'connectorname');
	$sConnectorType = SafeGetArrayItem2Dim($a, $i, 'connectortype');
	$sConnectorName2= $sConnectorType . ' connector ' . $sConnectorName;
	$sConnectorGUID = SafeGetArrayItem2Dim($a, $i, 'connectorguid');
	$sConnectorImageURL = SafeGetArrayItem2Dim($a, $i, 'connectorimageurl');
	$sElementName 	= SafeGetArrayItem2Dim($a, $i, 'elementname');
	$sElementName 	= GetPlainDisplayName($sElementName);
	$sElementGUID 	= SafeGetArrayItem2Dim($a, $i, 'elementguid');
	$sElementImageURL = SafeGetArrayItem2Dim($a, $i, 'elementimageurl');
	$sDirection 	= SafeGetArrayItem2Dim($a, $i, 'direction');
	echo '<div class="relationship-item">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="relationship-item-in">';
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sConnectorGUID) . '\',\'false\',\'\',\'\',\'' . _j($sConnectorName2) . '\',\'' . _j($sConnectorImageURL) . '\')">';
	echo _h($sConnectorType) . '</a>';
	echo '&nbsp;' . _glt('from') . '&nbsp;';
	echo '<a class="w3-link" onclick="LoadObject(\'' . _j($sElementGUID) . '\',\'false\',\'\',\'\',\'' . _j($sElementName) . '\',\'' . _j($sElementImageURL) . '\')">';
	echo '<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sElementImageURL) . '" alt="">&nbsp;';
	echo _h($sElementName);
	echo '</a>';
	echo '</div>'. PHP_EOL;
	}
	}
	echo '</div>';
	}
	echo '</div>' . PHP_EOL;
	}
	else
	{
	echo '</div>';
	}
	echo '</div>';
	}
	else
	{
	$sSectionID = 'relationship-section';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">'._glt('Relationships').'</div>';
	$sTableAttr = 'id="relationship-table" class="property-table"';
	$aHeader = ['Type', 'Direction', 'Target'];
	echo '<table '.$sTableAttr.'>';
	echo '<tbody>';
	echo '<tr>';
	foreach ($aHeader as $header)
	{
	echo '<th>'.$header.'</th>';
	}
	echo '</tr>';
	foreach ($a as $aRow)
	{
	$sLinkDirection	= SafeGetArrayItem1Dim($aRow, 'linkdirection');
	$sConnectorName = SafeGetArrayItem1Dim($aRow, 'connectorname');
	$sConnectorType = SafeGetArrayItem1Dim($aRow, 'connectortype');
	$sConnectorName2= $sConnectorType . ' connector ' . $sConnectorName;
	$sConnectorGUID = SafeGetArrayItem1Dim($aRow, 'connectorguid');
	$sConnectorImageURL = SafeGetArrayItem1Dim($aRow, 'connectorimageurl');
	$sElementName 	= SafeGetArrayItem1Dim($aRow, 'elementname');
	$sElementName 	= GetPlainDisplayName($sElementName);
	$sElementGUID 	= SafeGetArrayItem1Dim($aRow, 'elementguid');
	$sElementImageURL = SafeGetArrayItem1Dim($aRow, 'elementimageurl');
	$sDirection 	= SafeGetArrayItem1Dim($aRow, 'direction');
	if ($sLinkDirection === 'Outgoing')
	$sLinkImgClass = 'relationship-item-out';
	else
	$sLinkImgClass = 'relationship-item-in';
	$sConnectorTypeHTML = '<a>' . _h($sConnectorType) . '</a>';
	$sConnectorDirection = '<img alt="" src="images/spriteplaceholder.png" class="'._h($sLinkImgClass).'">'. _h($sLinkDirection);
	$sTargetNameHTML =
	 '<a class="w3-link" >'
	.'	<img src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sElementImageURL) . '" alt="">&nbsp;'
	.	_h($sElementName)
	.'</a>';
	echo '<tr>';
	echo '<td class="w3-link" onclick="LoadObject(\'' . _j($sConnectorGUID) . '\',\'false\',\'\',\'\',\'' . _j($sConnectorName2) . '\',\'' . _j($sConnectorImageURL) . '\')">' . $sConnectorTypeHTML . '</td>';
	echo '<td class="relationship-direction">'.$sConnectorDirection.'</td>';
	echo '<td class="w3-link"  onclick="LoadObject(\'' . _j($sElementGUID) . '\',\'false\',\'\',\'\',\'' . _j($sElementName) . '\',\'' . _j($sElementImageURL) . '\')">'. $sTargetNameHTML .'</td>';
	echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	}
	}
	function WriteSectionChangeManagement1($a, $sChgMgmtType, $sObjectGUID, $bObjectLocked, $sObjectName, $sObjectImageURL, $sLinkType, $sObjectHyper, $bIsMini=false)
	{
	$sHeading = ucfirst($sChgMgmtType. 's');
	if(empty($a))
	{
	echo WriteSectionEmpty($sHeading);
	return;
	}
	if ($bIsMini)
	{
	$sDate1FieldName = 'requestedon';
	$sDate2FieldName = 'completedon';
	$sPerson1FieldName = 'requestedby';
	$sPerson2FieldName = 'completedby';
	$sPerson1Desc = _glt('Requested by');
	$sPerson2Desc = _glt('Completed by');
	if ( $sChgMgmtType === 'defect' || $sChgMgmtType === 'event' )
	{
	$sDate1FieldName = 'reportedon';
	$sDate2FieldName = 'resolvedon';
	$sPerson1FieldName = 'reportedby';
	$sPerson2FieldName = 'resolvedby';
	$sPerson1Desc = _glt('Reported by');
	$sPerson2Desc = _glt('Resolved by');
	}
	elseif ( $sChgMgmtType === 'issue' )
	{
	$sDate1FieldName = 'raisedon';
	$sDate2FieldName = 'completedon';
	$sPerson1FieldName = 'raisedby';
	$sPerson2FieldName = 'completedby';
	$sPerson1Desc = _glt('Raised by');
	$sPerson2Desc = _glt('Completed by');
	}
	elseif ( $sChgMgmtType === 'decision' )
	{
	$sDate1FieldName = 'date';
	$sDate2FieldName = 'effective';
	$sPerson1FieldName = 'owner';
	$sPerson2FieldName = 'author';
	$sPerson1Desc = _glt('Owner');
	$sPerson2Desc = _glt('Author');
	}
	echo '<div id="' . _h($sChgMgmtType) . '-mini-section" class="propsview-section">';
	echo '<div class="miniprops-header">'. _h($sHeading) .'</div>';
	$iCnt = count($a);
	for ($i=0;$i<$iCnt;$i++)
	{
	$sName	 	= SafeGetArrayItem2Dim($a, $i, 'name');
	$sStatus	= SafeGetArrayItem2Dim($a, $i, 'status');
	$sPriority	= SafeGetArrayItem2Dim($a, $i, 'priority');
	$sGUID 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	echo '<div class="' . _h($sChgMgmtType) . '-item property-section-item">';
	$sItemHdr  	= '<img alt="" src="images/spriteplaceholder.png" class="' . _h($sChgMgmtType) . '-item-icon">';
	$sItemHdr  .= '<div class="chgmgmt-name">';
	if ( !strIsEmpty($sStatus) )
	$sItemHdr .= _h($sStatus) . ', ';
	if ( !strIsEmpty($sPriority) )
	$sItemHdr .= _h($sPriority) . ' ' . _glt('priority') . '.  ';
	$sItemHdr  .=  _h($sName) . ' </div>';
	$sDate1 	=  SafeGetArrayItem2Dim($a, $i, $sDate1FieldName);
	$sDate2 	=  SafeGetArrayItem2Dim($a, $i, $sDate2FieldName);
	if ( !strIsEmpty($sDate1) || !strIsEmpty($sDate2) )
	{
	$sItemHdr .= '<div class="chgmgmt-dates1">';
	$sItemHdr .= '<div class="chgmgmt-date-line">';
	if ( !strIsEmpty($sDate1) )
	{
	$sItemHdr .= '<span class="chgmgmt-date-value">' . _h($sDate1) . '&nbsp;</span>';
	}
	if ( !strIsEmpty($sDate2) )
	{
	$sItemHdr .= '<span class="chgmgmt-date-label2">' . _glt('until') . '</span><span class="chgmgmt-date-value">' . _h($sDate2) . '</span>';
	}
	$sItemHdr .= '</div>';
	$sItemHdr .= '</div>';
	}
	$sNotes 	= SafeGetArrayItem2Dim($a, $i, 'notes');
	$sHistory 	= SafeGetArrayItem2Dim($a, $i, 'history');
	$sPerson1 	=  SafeGetArrayItem2Dim($a, $i, $sPerson1FieldName);
	$sPerson2 	=  SafeGetArrayItem2Dim($a, $i, $sPerson2FieldName);
	$sVersion 	=  SafeGetArrayItem2Dim($a, $i, 'version');
	if (!strIsEmpty($sNotes) || !strIsEmpty($sHistory) || !strIsEmpty($sPerson1) || !strIsEmpty($sPerson2) || !strIsEmpty($sVersion) )
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-closed">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $sItemHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-hide">';
	if ( !strIsEmpty($sNotes) )
	{
	echo '<div class="chgmgmt-notes">' . _hRichText($sNotes) . '</div>';
	}
	if ( !strIsEmpty($sHistory) )
	{
	echo '<div class="chgmgmt-history-header">' . _glt('History') . ': </div><div class="chgmgmt-history">' . _hRichText($sHistory) . '</div>';
	}
	if ( !strIsEmpty($sPerson1) || !strIsEmpty($sPerson2) || !strIsEmpty($sVersion) )
	{
	echo '<div class="chgmgmt-times-div"><div class="chgmgmt-times">';
	echo '<span class="chgmgmt-time-label"> [ </span>';
	if ( !strIsEmpty($sPerson1) )
	echo '<span class="chgmgmt-time-label"> ' . _h($sPerson1Desc) . ' </span><span class="chgmgmt-time-value"> ' . _h($sPerson1) . '.</span>&nbsp;';
	if ( !strIsEmpty($sPerson2) )
	echo '<span class="chgmgmt-time-label"> ' . _h($sPerson2Desc) . ' </span><span class="chgmgmt-time-value"> ' . _h($sPerson2) . '.</span>&nbsp;';
	if ( !strIsEmpty($sVersion) )
	echo '<span class="chgmgmt-time-label"> ' . _glt('Version') . ':</span><span class="chgmgmt-time-value"> ' . _h($sVersion) . '</span>&nbsp;';
	echo '<span class="chgmgmt-time-label">]</span>';
	echo '</div></div>';
	}
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo $sItemHdr;
	echo '</div>';
	}
	echo '</div>'. PHP_EOL;
	}
	echo '</div>' . PHP_EOL;
	}
	else
	{
	$propType = $sChgMgmtType;
	$aFieldMap = [];
	$sTableAttr = 'id="'.$propType.'-table" class="property-table"';
	$sPropDetailsID = 'props-'.$propType.'-details';
	if ($sLinkType === 'props-'.$sChgMgmtType.'s')
	{
	echo '<div id="' . _h($sChgMgmtType) . '-section" style="display:block">';
	}
	else
	{
	echo '<div id="' . _h($sChgMgmtType) . '-section" class="property-section" style="'.GetSectionVisibility($sChgMgmtType).'">';
	}
	$sTableAttr = 'id="'. _h($sChgMgmtType) .'-table" class="property-table"';
	if (($sChgMgmtType === 'change') || ($sChgMgmtType === 'feature') || ($sChgMgmtType === 'document') || ($sChgMgmtType === 'task'))
	{
	$aHeader = ['', ucfirst($sChgMgmtType), 'Status', 'Priority', 'Requested On', 'Requested By'];
	$aFields = ['icon', 'name', 'status', 'priority', 'requestedon', 'requestedby'];
	$aFieldMap = [
	['label' => 'Version', 'value' => 'version'],
	['label' => 'Completed On', 'value' => 'completedon'],
	['label' => 'Completed By', 'value' => 'completedby'],
	['label' => 'Description', 'value' => 'notes'],
	['label' => 'History', 'value' => 'history'],
	];
	}
	elseif (($sChgMgmtType === 'defect') || ($sChgMgmtType === 'event'))
	{
	$aHeader = ['', ucfirst($sChgMgmtType), 'Status', 'Priority', 'Reported On', 'Reported By'];
	$aFields = ['icon', 'name', 'status', 'priority', 'reportedon', 'reportedby'];
	$aFieldMap = [
	['label' => 'Version', 'value' => 'version'],
	['label' => 'Resolved on', 'value' => 'resolvedon'],
	['label' => 'Resolved by', 'value' => 'resolvedby'],
	['label' => 'Description', 'value' => 'notes'],
	['label' => 'History', 'value' => 'history'],
	];
	}
	elseif(($sChgMgmtType === 'issue'))
	{
	$aHeader = ['', ucfirst($sChgMgmtType), 'Status', 'Priority', 'Raised On', 'Raised By'];
	$aFields = ['icon', 'name', 'status', 'priority', 'raisedon', 'raisedby'];
	$aFieldMap = [
	['label' => 'Version', 'value' => 'version'],
	['label' => 'Completed On', 'value' => 'completedon'],
	['label' => 'Completed By', 'value' => 'completedby'],
	['label' => 'Description', 'value' => 'notes'],
	['label' => 'History', 'value' => 'history'],
	];
	}
	elseif($sChgMgmtType === 'decision')
	{
	$aHeader = ['', ucfirst($sChgMgmtType), 'Status', 'Impact', 'Date', 'Author'];
	$aFields = ['icon', 'name', 'status', 'impact', 'date', 'author'];
	$aFieldMap = [
	['label' => 'Effective', 'value' => 'effective'],
	['label' => 'Owner', 'value' => 'owner'],
	['label' => 'Version / ID', 'value' => 'version'],
	['label' => 'Description', 'value' => 'notes'],
	['label' => 'History', 'value' => 'history'],
	];
	}
	$i=0;
	foreach ($a as &$row)
	{
	$propID = $propType . '_' . $i;
	$row['tr_attributes'] = '';
	$row['notes'] = _hRichText($row['notes']);
	$row['history'] = _hRichText($row['history']);
	$row['icon'] = '<div class="expand-icon"></div>';
	$row['tr_details'] = WriteDataFields($row, $aFieldMap);
	$i++;
	}
	echo '<div id="' . $propType . '-list">';
	echo '<div class="properties-header">' . _h(ucfirst($propType)) . 's</div>';
	WriteListTable($sTableAttr, $aHeader, $a, $aFields);
	echo '</div>' . PHP_EOL;
	echo '<div id="' . _h($propType) . '-details" class="property-details">';
	$i=0;
	foreach ($a as $aItem)
	{
	$sHeaderLabel = _h(ucfirst($propType)) . 's</a><img alt="" src="images/spriteplaceholder.png" class="propsprite-separator">' . _h($aItem['name']);
	$propID = $propType . '_' . $i;
	$sPriorityDesc = 'Priority';
	$sPriorityFieldName = 'priority';
	$sDate1Desc = 'Requested on';
	$sDate1FieldName = 'requestedon';
	$sDate2Desc = 'Completed on';
	$sDate2FieldName = 'completedon';
	$sPerson1Desc = _glt('Requested by');
	$sPerson1FieldName = 'requestedby';
	$sPerson2Desc = _glt('Completed by');
	$sPerson2FieldName = 'completedby';
	$sVersionDesc = 'Version';
	$sVersionFieldName = 'version';
	if ( $propType === 'defect' || $propType === 'event' )
	{
	$sDate1Desc = 'Reported on';
	$sDate1FieldName = 'reportedon';
	$sDate2Desc = 'Resolved on';
	$sDate2FieldName = 'resolvedon';
	$sPerson1Desc = _glt('Reported by');
	$sPerson1FieldName = 'reportedby';
	$sPerson2Desc = _glt('Resolved by');
	$sPerson2FieldName = 'resolvedby';
	}
	elseif ( $propType === 'issue' )
	{
	$sDate1Desc = _glt('Raised on');
	$sDate1FieldName = 'raisedon';
	$sDate2Desc = _glt('Completed on');
	$sDate2FieldName = 'completedon';
	$sPerson1Desc = _glt('Raised by');
	$sPerson1FieldName = 'raisedby';
	$sPerson2Desc = _glt('Completed by');
	$sPerson2FieldName = 'completedby';
	}
	elseif ( $propType === 'decision' )
	{
	$sPriorityDesc = _glt('Impact');
	$sPriorityFieldName = 'impact';
	$sDate1Desc = _glt('Date');
	$sDate1FieldName = 'date';
	$sDate2Desc = _glt('Effective');
	$sDate2FieldName = 'effective';
	$sPerson1Desc = _glt('Owner');
	$sPerson1FieldName = 'owner';
	$sPerson2Desc = _glt('Author');
	$sPerson2FieldName = 'author';
	$sVersionDesc = 'Version / ID';
	}
	if (($sLinkType = $sPropDetailsID) && ($sObjectHyper === $propID))
	echo '<div id="' . _h($propID) . '" style="display: block;">';
	else
	echo '<div id="' . _h($propID) . '" style="display: none;">';
	echo '<div class="properties-header"><a class="properties-header-link" title="'._glt('Return to list').'"  onclick="ReturnToList(this)">'.$sHeaderLabel .'</div>';
	echo '<div class="property-details-container">';
	WritePropertyField('Name', $aItem['name'],'','style="width: 240px;"');
	echo '<div class="prop-row">';
	echo '<div class="prop-column">';
	WritePropertyField('Status', $aItem['status']);
	WritePropertyField($sPriorityDesc, $aItem[$sPriorityFieldName]);
	echo '</div>';
	echo '<div class="prop-column">';
	WritePropertyField($sPerson1Desc, $aItem[$sPerson1FieldName]);
	WritePropertyField($sDate1Desc, $aItem[$sDate1FieldName]);
	echo '</div>';
	echo '<div class="prop-column">';
	WritePropertyField($sPerson2Desc, $aItem[$sPerson2FieldName]);
	WritePropertyField($sDate2Desc, $aItem[$sDate2FieldName]);
	echo '</div>';
	echo '</div>';
	echo '<div class="prop-row">';
	WritePropertyField($sVersionDesc , $aItem['version']);
	echo '</div>';
	WritePropertyNoteField('Description', $aItem['notes']);
	WritePropertyNoteField('History', $aItem['history']);
	echo '</div>';
	echo '</div>';
	$i++;
	}
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	}
	function WriteDataFields($aRow, $aFieldMap)
	{
	$sDetail = '';
	$sDetail .=  '<div class="table-detail-grouping">';
	foreach ($aFieldMap as $a)
	{
	$sLabel = SafeGetArrayItem1Dim($a, 'label');
	$sFieldName = SafeGetArrayItem1Dim($a, 'value');
	$sDetail .= '<div class="table-detail-row">';
	$sDetail .= '<div class="table-detail-field">';
	$sDetail .= $sLabel.':';
	$sDetail .= '</div>';
	$sDetail .= '<div class="table-detail-value">';
	$sDetail .= WriteTableDetailValue($aRow, $sFieldName);
	$sDetail .= '</div>';
	$sDetail .= '</div>';
	}
	$sDetail .= '</div>';
	return $sDetail;
	}
	function WriteTableDetailValue($aData, $sFieldName)
	{
	$sValue = SafeGetArrayItem1Dim($aData, $sFieldName);
	if (strIsEmpty($sValue))
	$sValue = '<a style ="font-style: italic; color: #a0a0a0;">unset</a>';
	return $sValue;
	}
	function WriteSectionChangeManagement2($a, $sChgMgmtType, $sObjectGUID, $bObjectLocked, $sObjectName, $sObjectImageURL, $sLinkType, $sObjectHyper)
	{
	$propType = $sChgMgmtType;
	$sTableAttr = 'id="'.$propType.'-table" class="property-table"';
	$sPropDetailsID = 'props-'.$propType.'-details';
	$sSectionID = $sChgMgmtType . '-section';
	$sHeader = ucfirst($sChgMgmtType).'s';
	echo '<div id="' . _h($sSectionID) . '" class="property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	echo '<div class="properties-header">' . _h($sHeader) . '</div>';
	echo '<div class="properties-content">';
	$iCnt = count($a);
	for ($i=0;$i<$iCnt;$i++)
	{
	$sName	 	= SafeGetArrayItem2Dim($a, $i, 'name');
	$sNotes	= SafeGetArrayItem2Dim($a, $i, 'notes');
	$sType	= SafeGetArrayItem2Dim($a, $i, 'type');
	$sWeight	= SafeGetArrayItem2Dim($a, $i, 'weight');
	$sGUID 	= SafeGetArrayItem2Dim($a, $i, 'guid');
	echo '<div class="' . _h($sChgMgmtType) . '-item property-section-item">';
	$sItemHdr  	= '<img alt="" src="images/spriteplaceholder.png" class="' . _h($sChgMgmtType) . '-item-icon">';
	$sItemHdr  .= '<div class="chgmgmt-name">';
	if ( !strIsEmpty($sType) )
	$sItemHdr .= _h($sType) . ' ' . _h($sChgMgmtType) . '.  ';
	$sItemHdr  .=  _h($sName) . ' </div>';
	if ( !strIsEmpty($sNotes) || (!strIsEmpty($sWeight) && $sWeight !== "0" ) )
	{
	$sSectionName = 'collapsible-' . $sGUID;
	echo '<div id="' . _h($sSectionName) . '" onclick="OnToggleCollapsibleSection(this)" ';
	echo     ' class="collapsible-section-header collapsible-section-header-closed">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-closed-icon">';
	echo $sItemHdr;
	echo '</div>';
	echo '<div class="collapsible-section w3-hide">';
	if ( !strIsEmpty($sNotes) )
	{
	echo '<div class="chgmgmt-notes">' . _hRichText($sNotes) . '</div>';
	}
	if ( !strIsEmpty($sWeight) && $sWeight !== "0" )
	{
	echo '<div class="chgmgmt-times-div"><div class="chgmgmt-times">';
	echo '<span class="chgmgmt-time-label"> [ </span>';
	echo '<span class="chgmgmt-time-label"> Weight is </span><span class="chgmgmt-time-value"> ' . number_format($sWeight, 2, '.', '') . '</span>&nbsp;';
	echo '<span class="chgmgmt-time-label">]</span>';
	echo '</div></div>';
	}
	echo '</div>';
	}
	else
	{
	echo '<div class="non-collapsible-guid">';
	echo '<img alt="" src="images/spriteplaceholder.png" class="collapsible-section-header-blank-icon">';
	echo $sItemHdr;
	echo '</div>';
	}
	echo '</div>'. PHP_EOL;
	}
	echo '</div>' . PHP_EOL;
	echo '</div>';
	}
	function WriteGenerateDiagramSection($sObjectResType, $sObjectGenerated, $sImageInSync, $sObjectGUID)
	{
	$sPropertiesDetails ='';
	$sPCSEdition = SafeGetInternalArrayParameter($_SESSION, 'pro_cloud_license');
	if ($sObjectResType === 'Diagram' && (!IsSessionSettingTrue('readonly_model') && $sPCSEdition !== 'Express') )
	{
	if ( strIsEmpty($sObjectGenerated) )
	{
	$sObjectGenerated = _glt('<awaiting>');
	}
	$sPropertiesDetails .= '<div class="generate-diagram-section">';
	$sPropertiesDetails .= '<a class="w3-grey-text">' . _glt('Generated') . ' </a>';
	$sPropertiesDetails .= '<a class="generate-diagram-date">' . _h($sObjectGenerated) . '</a>';
	$sPropertiesDetails .= '<input class="webea-main-styled-button" id="generatediagram-action-button"' . ((strIsTrue($sImageInSync)) ? '' : ' disabled=""') . ' type="button" onclick="OnRequestDiagramRegenerate(\'' . _j($sObjectGUID) . '\')" title="' . _glt('Mark the current diagram as requiring re-generation') . '"></td>';
	$sPropertiesDetails .= '';
	$sPropertiesDetails .= '<div id="diagram-regeneration-label-line"' . ((strIsTrue($sImageInSync)) ? ' style="display: none;"' : '') . '>';
	$sPropertiesDetails .= '<div class="diagram-regeneration-label">  ' . _glt('Image pending regeneration') . '  </div>';
	$sPropertiesDetails .= '</div>';
	$sPropertiesDetails .= '</div>';
	}
	echo $sPropertiesDetails;
	}
	function WriteSectionSummary($aCommonProps, $bShowSummary)
	{
	$sStatus = SafeGetArrayItem1Dim($aCommonProps, 'status');
	$sVersion = SafeGetArrayItem1Dim($aCommonProps, 'version');
	$sPhase = SafeGetArrayItem1Dim($aCommonProps, 'phase');
	$aCompositeInfo	= SafeGetArrayItem1Dim($aCommonProps, 'composite_info');
	$sObjectResType = SafeGetArrayItem1Dim($aCommonProps, 'restype');
	$sObjectGUID = SafeGetArrayItem1Dim($aCommonProps, 'guid');
	$sDifficulty = SafeGetArrayItem1Dim($aCommonProps, 'difficulty');
	$sPriority = SafeGetArrayItem1Dim($aCommonProps, 'priority');
	$sVisibility = '';
	$sClass = '';
	if(!$bShowSummary)
	{
	$sVisibility = 'hidden';
	$sClass = 'class="mainview-summary-section"';
	}
	echo '<div id="summary-section" '.$sClass.' '.$sVisibility.'>';
	if (!empty($aCompositeInfo))
	{
	$sCompositeDiagramName = SafeGetArrayItem1Dim($aCompositeInfo, 'text');
	$sCompositeDiagramGUID = SafeGetArrayItem1Dim($aCompositeInfo, 'guid');
	$sCompositeDiagramImageURL = SafeGetArrayItem1Dim($aCompositeInfo, 'imageurl');
	echo '<a class="w3-grey-text">Composite </a>' ;
	echo '<a class="composite-diagram-name w3-link" title="'._glt('View Composite Diagram').'" onclick="LoadObject(\'' . _j($sCompositeDiagramGUID) . '\',\'false\',\'\',\'\',\'' . _j($sCompositeDiagramName) . '\',\'' . _j($sCompositeDiagramImageURL) . '\')" >';
	echo '<img alt="" src="images/spriteplaceholder.png" class="' . GetObjectImageSpriteName($sCompositeDiagramImageURL) . '">';
	echo ' ' . _h($sCompositeDiagramName);
	echo '</a>';
	echo '<br>';
	}
	if(!strIsEmpty($sStatus))
	{
	echo _h($sStatus);
	echo '<br>';
	}
	if(!strIsEmpty($sVersion))
	{
	echo '<a class="w3-grey-text">Version </a>' . _h(SafeGetArrayItem1Dim($aCommonProps, 'version'));
	echo '&nbsp&nbsp';
	}
	if(!strIsEmpty($sPhase))
	{
	echo '<a class="w3-grey-text">Phase </a>' . _h(SafeGetArrayItem1Dim($aCommonProps, 'phase'));
	}
	echo '<br>';
	echo _h(SafeGetArrayItem1Dim($aCommonProps, 'created')) .'<a class="w3-grey-text"> created by </a>' . _h(SafeGetArrayItem1Dim($aCommonProps, 'author'));
	echo '<br>';
	echo _h(SafeGetArrayItem1Dim($aCommonProps, 'modified')) . '<a class="w3-grey-text"> last modified</a>';
	echo '<br>';
	if(!strIsEmpty($sDifficulty))
	{
	echo '<a class="w3-grey-text">Difficulty </a>' . _h($sDifficulty);
	echo '&nbsp&nbsp';
	}
	if(!strIsEmpty($sPriority))
	{
	echo '<a class="w3-grey-text">Priority </a>' . _h($sPriority);
	}
	echo '</div>';
	}
	function GetSectionVisibility($sSectionID)
	{
	$bFilterProperties = SafeGetInternalArrayParameter($_SESSION, 'filter_properties','true');
	if (strIsTrue($bFilterProperties))
	{
	return 'display:none';
	}
	else
	{
	return 'display:block';
	}
	}
	function WriteSectionNotes($sNotes, $sObjectGUID, $sObjectName, $bObjLocked, $sObjImageURL, $sLinkType, $sDefaultSection, $bIsMini = false)
	{
	if($bIsMini)
	{
	echo '<div id="note-mini-section"  class="propsview-section">';
	echo '<div class="miniprops-header">Notes</div>';
	if ( !$bObjLocked )
	{
	if ( IsSessionSettingTrue('login_perm_element') )
	{
	if (IsSessionSettingTrue('edit_object_notes'))
	{
	echo '<div class="notes-section-edit">';
	echo '<input class="notes-section-edit-button" type="button" value="&#160;" onclick="EditNote(\''. _j($sObjectGUID) . '\')" />';
	echo '</div>';
	}
	}
	}
	if ( strIsEmpty($sNotes) )
	$sNotes = WriteNoContents();
	echo '<div class="notes-note">' . _hRichText($sNotes) ;
	echo '</div>';
	echo '<br>';
	echo '</div>' . PHP_EOL;
	}
	else
	{
	$sSectionID = 'note-section';
	if (($sLinkType === 'props-notes') || ($sLinkType === 'props') || ($sLinkType === ''))
	{
	if ($sDefaultSection !== 'notes')
	{
	echo '<div id="note-section" class="notes-section property-section" style="'. GetSectionVisibility($sSectionID) .'">';
	}
	else
	{
	echo '<div id="note-section" class="notes-section property-section" style="display:block;">';
	}
	}
	else
	{
	echo '<div id="note-section" class="notes-section property-section" style="display:block;">';
	}
	echo '<div class="properties-header">Notes</div>';
	echo '<div class="properties-content">';
	if ( !$bObjLocked )
	{
	if ( IsSessionSettingTrue('login_perm_element') )
	{
	if (IsSessionSettingTrue('edit_object_notes'))
	{
	echo '<div class="notes-section-edit">';
	echo '<input class="notes-section-edit-button" type="button" value="&#160;" onclick="EditNote(\''. _j($sObjectGUID) . '\')" />';
	echo '</div>';
	}
	}
	}
	if ( strIsEmpty($sNotes) )
	$sNotes = WriteNoContents();
	echo '<div class="notes-note">' . _hRichText($sNotes) . '</div>';
	echo '</div>';
	echo '</div>' . PHP_EOL;
	}
	}
	function WriteReviewsSection($aDiscussions, $sObjectGUID, $bAddDiscussions, $sAuthor, $sLoginGUID, $sSessionReviewGUID)
	{
	$sHTML = '';
	if (!IsSessionSettingTrue('show_discuss'))
	{
	$sHTML .=  '<div class="review-new-topic-message">';
	$sHTML .=  _glt('Reviews and Discussions are not enabled for this WebEA model connection');
	$sHTML .=  '</div>';
	return $sHTML;
	}
	$aReviews = array();
	foreach ($aDiscussions as $disc)
	{
	$aRow = array();
	$aRow['reviewname'] = $disc['reviewname'];
	$aRow['reviewguid'] = $disc['reviewguid'];
	if (!(in_array($aRow,$aReviews)) && ($disc['reviewguid']!==''))
	{
	$aReviews[] = $aRow;
	}
	}
	foreach ($aReviews as &$rev)
	{
	$rev['reviewdiscussions'] = array();
	foreach ($aDiscussions as $disc)
	{
	if ($disc['reviewguid']===$rev['reviewguid'])
	{
	$rev['reviewdiscussions'][] = $disc;
	}
	}
	}
	$sReviewName 	= '';
	$sReviewGUID 	= '';
	$aReviewDiscussions = array();
	if(empty($aReviews))
	{
	if(strIsEmpty($sSessionReviewGUID))
	{
	$sHTML .=  '<div class="review-new-topic-message">';
	$sHTML .=  _glt('Join a review to create a new topic');
	$sHTML .=  '</div>';
	}
	else
	{
	$sHTML .= WriteAddReviewField($sObjectGUID, $sSessionReviewGUID);
	}
	}
	foreach ($aReviews as $aReview)
	{
	$sReviewName 	 	= $aReview['reviewname'];
	$sReviewGUID 	= $aReview['reviewguid'];
	$aReviewDiscussions = $aReview['reviewdiscussions'];
	if ((strIsEmpty($sSessionReviewGUID)) || ($sSessionReviewGUID === $sReviewGUID))
	{
	$sHTML .= '<div class="review-name " >';
	$sHTML .=  '<img alt="" title="Review" src="images/spriteplaceholder.png" class="propsprite-review">';
	$sHTML .=  ' <a class="w3-link" onclick="LoadObject(\'' . _j($sReviewGUID) . '\',\'false\',\'\',\'\',\'' . _j($sReviewName) . '\',\'images/element16/review.png\')">' . _h($aReview['reviewname']) . '</a>';
	if($sSessionReviewGUID !== $sReviewGUID)
	$sHTML .= '<button class="review-inline-join-button webea-main-styled-button" onclick="OnJoinLeaveReviewSession(\'' . _j($sReviewGUID) . '\', \'' . _j($sReviewName) . '\', \'' . _j($sObjectGUID) . '\')">Join</button>';
	else
	$sHTML .= '<button class="review-inline-join-button webea-main-styled-button" onclick="OnJoinLeaveReviewSession(\'' . '\', \'' . '\', \'' . _j($sObjectGUID) . '\')">Leave</button>';
	$sHTML .=  '</div>';
	$iCnt = count($aReviewDiscussions);
	if ($bAddDiscussions)
	{
	if ( !strIsEmpty($sSessionReviewGUID) )
	{
	$sHTML .= WriteAddReviewField($sObjectGUID, $sSessionReviewGUID);
	}
	}
	for ($i=$iCnt-1; $i>=0; $i--)
	{
	$sDiscussText 	= SafeGetArrayItem2Dim($aReviewDiscussions, $i, 'discussion');
	$sDiscussGUID 	= SafeGetArrayItem2Dim($aReviewDiscussions, $i, 'guid');
	$sReviewGUID  	= SafeGetArrayItem2Dim($aReviewDiscussions, $i, 'reviewguid');
	$sExpandDiscussEvent= ' onclick="OnTogglePropertiesReviewDiscussionReplies(this)"';
	$sReplyID	= 'replies_' . $sDiscussGUID;
	$sStatusButtonID	= 'sb_' . $sDiscussGUID;
	$sPriority	= SafeGetArrayItem2Dim($aReviewDiscussions, $i, 'priority');
	$sPriorityImageClass= SafeGetArrayItem2Dim($aReviewDiscussions, $i, 'priorityimageclass');
	$sPriorityTooltip 	= str_replace('%PRIORITY%', $sPriority, _glt('Priority: xx'));
	$sStatus 	= SafeGetArrayItem2Dim($aReviewDiscussions, $i, 'status');
	$sStatusImageClass	= SafeGetArrayItem2Dim($aReviewDiscussions, $i, 'statusimageclass');
	$sStatusTooltip 	= str_replace('%STATUS%', $sStatus, _glt('Status: xx'));
	$bHasReplies 	= array_key_exists(("replies"), $aReviewDiscussions[$i]);
	$sIsDisabled 	= '';
	if ($bAddDiscussions)
	{
	if ( !strIsEmpty($sReviewGUID) )
	{
	if ($sSessionReviewGUID === $sReviewGUID)
	{
	$bCanReply = true;
	}
	else
	{
	$bCanReply = false;
	}
	}
	else
	{
	$bCanReply = true;
	}
	}
	else
	{
	$bCanReply = false;
	}
	if ($bAddDiscussions || $bHasReplies)
	{
	$sHTML .=  '<div class="review-item collapsible-section-header-closed">';
	$sHTML .=  '<img alt="" src="images/spriteplaceholder.png" class="review-item-icon collapsible-section-header-closed-icon show-cursor-pointer" onclick="$(this).next().next().next().click()">';
	}
	else
	{
	$sHTML .=  '<div class="review-item">';
	$sExpandDiscussEvent = '';
	$sIsDisabled 	=  'disabled="true"';
	}
	$sHTML .= WriteAvatarImage($aReviewDiscussions[$i]['avatarid'], 'false');
	$sHTML .=  '<div class="discussion-item-states" >';
	$sHTML .= WriteDiscussionStatusMenus($bCanReply, $sDiscussGUID, $sPriorityImageClass, $sPriorityTooltip, $sStatusImageClass, $sStatusTooltip);
	$sHTML .=  '</div>';
	$sHTML .=  '<div class="review-item-text"' . _h($sIsDisabled) . $sExpandDiscussEvent . '>' . _hRichText($sDiscussText) . '</div>';
	$sHTML .=  '<div class="review-item-text-footer">';
	$sHTML .=  '<div class="review-item-text-footer-dateauthor">' . _h($aReviewDiscussions[$i]['created']) . '&nbsp; &nbsp;' . _h($aReviewDiscussions[$i]['author']) . '</div>';
	$sHTML .=  '</div>';
	$sHTML .=  '<div class="review-item-replies ' . _h($sReplyID) . '">';
	if (array_key_exists(("replies"), $aReviewDiscussions[$i]))
	{
	$aReplies = $aReviewDiscussions[$i]['replies'];
	$iReplyCnt = count($aReplies);
	for ($iR=0; $iR<$iReplyCnt; $iR++)
	{
	$sReplyAuthor 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyauthor');
	$sReplyAvatarID	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyavatarid');
	$sReplyAvatarImage	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyavatarimage');
	$sReplyCreated 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replycreated');
	$sReplyText 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replytext');
	$sHTML .=  '<div class="review-item-reply">';
	$sHTML .= WriteAvatarImage($sReplyAvatarID, 'true');
	$sHTML .=  '<div class="review-item-reply-text">' . _hRichText($sReplyText) . '</div>';
	$sHTML .=  '<div class="review-item-reply-text-footer">';
	$sHTML .=  '<div class="review-item-reply-text-footer-dateauthor">' . _h($sReplyCreated) . '&nbsp; &nbsp;' . _h($sReplyAuthor) . '</div>';
	$sHTML .=  '</div>';
	$sHTML .=  '</div>';
	}
	}
	if ($bAddDiscussions)
	{
	if ($bCanReply)
	{
	$sHTML .=  '<div class="discussion-reply-form" id="ddrf_' . _h($sDiscussGUID) . '"><form id="drf_' . _h($sDiscussGUID) . '" method="post">';
	$sHTML .=  '<div class="discussion-reply-comment-div">';
	$sHTML .=  '<textarea class="discussion-reply-new-comment" name="comments" placeholder="' . _glt('Post reply') . '"></textarea>';
	$sHTML .=  '</div>';
	$sHTML .=  '<div class="discussion-reply-comment-send">';
	$sHTML .=  '<div class="discussion-reply-send-div">';
	$sHTML .=  '<input class="webea-main-styled-button discussion-reply-send-button" type="submit" value="" onclick="OnFormRunAddReply(event, this.form)">';
	$sHTML .=  '</div>';
	$sHTML .=  '</div>';
	$sHTML .=  '<input type="hidden" name="guid" value="' . _h($sDiscussGUID) .'"> ';
	$sHTML .=  '<input type="hidden" name="isreply" value="true"> ';
	$sHTML .=  '<input type="hidden" name="objectguid" value="' . _h($sObjectGUID) . '"> ';
	if ( !strIsEmpty($sSessionReviewGUID) && $sSessionReviewGUID===$sReviewGUID )
	{
	$sHTML .=  '<input type="hidden" name="sessionreviewguid" value="' . _h($sSessionReviewGUID) . '">';
	}
	$sHTML .=  '</form></div>';
	}
	else
	{
	$sHTML .=  '<div class="discussion-reply-form" id="ddrf_' . _h($sDiscussGUID) . '"><form id="drf_' . _h($sDiscussGUID) . '" method="post">';
	$sHTML .=  '<div class="discussion-reply-join-review-message">' . _glt('Join the review to post a reply') . '</div>';
	$sHTML .=  '</form></div>';
	}
	}
	$sHTML .=  '</div>';
	$sHTML .=  '</div>';
	}
	}
	}
	return $sHTML;
	}
	function WriteAddReviewField($sObjectGUID, $sSessionReviewGUID)
	{
	$sHTML = '';
	$sHTML .=  '<div class="review-filtered-message">';
	$sHTML .=  _glt('Filtered to display the current review only');
	$sHTML .=  '</div>';
	$sHTML .=  '<div id="discussion-form"><form id="discussion-form1" method="post">';
	$sHTML .=  '<div id="discussion-comment-div">';
	$sHTML .=  '<textarea class="discussion-new-comment" name="comments" placeholder="' . _glt('Create Review Topic') . '"></textarea>';
	$sHTML .=  '</div>';
	$sHTML .=  '<div id="discussion-comment-send">';
	$sHTML .=  '<div id="discussion-send-div">';
	$sHTML .=  '<input class="webea-main-styled-button" id="discussion-send-button" type="submit" value="" onclick="OnFormRunAddDiscussion(event, this);">';
	$sHTML .=  '</div>';
	$sHTML .=  '</div>';
	$sHTML .=  '<input type="hidden" name="guid" value="' . _h($sObjectGUID) . '"> ';
	$sHTML .=  '<input type="hidden" name="isreply" value="false"> ';
	$sHTML .=  '<input type="hidden" name="sessionreviewguid" value="' . _h($sSessionReviewGUID) . '">';
	$sHTML .=  '</form></div>';
	return $sHTML;
	}
	function WriteDiscussionsSection($aDiscussions, $sObjectGUID, $bAddDiscussions, $sAuthor, $sLoginGUID, $sSessionReviewGUID)
	{
	$sHTML = '';
	if (!IsSessionSettingTrue('show_discuss'))
	{
	$sHTML .=  '<div class="review-new-topic-message">';
	$sHTML .=  _glt('Reviews and Discussions are not enabled for this WebEA model connection');
	$sHTML .=  '</div>';
	return $sHTML;
	}
	if ($bAddDiscussions)
	{
	$sHTML .= '<div id="discussion-form"><form id="discussion-form1" method="post">';
	$sHTML .= '<div id="discussion-comment-div">';
	$sHTML .= '<textarea id="discussion-new-comment" class="discussion-new-comment" name="comments" placeholder="' . _glt('Create new Discussion') . '"></textarea>';
	$sHTML .= '</div>';
	$sHTML .= '<div id="discussion-comment-send">';
	$sHTML .= '<div id="discussion-send-div">';
	$sHTML .= '<input class="webea-main-styled-button" id="discussion-send-button" type="submit" value="" onclick="OnFormRunAddDiscussion(event, this);">';
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	$sHTML .= '<input type="hidden" name="guid" value="' . _h($sObjectGUID) . '"> ';
	$sHTML .= '<input type="hidden" name="isreply" value="false"> ';
	$sHTML .= '</form></div>';
	}
	$iCnt = count($aDiscussions);
	for ($i=$iCnt-1 ; $i>=0; $i--)
	{
	$sDiscussText 	= SafeGetArrayItem2Dim($aDiscussions, $i, 'discussion');
	$sDiscussGUID 	= SafeGetArrayItem2Dim($aDiscussions, $i, 'guid');
	$sReviewGUID  	= SafeGetArrayItem2Dim($aDiscussions, $i, 'reviewguid');
	$sExpandDiscussEvent= ' onclick="OnToggleDiscussionReplies(this)"';
	$sReplyID	= 'replies_' . $sDiscussGUID;
	$sStatusButtonID	= 'sb_' . $sDiscussGUID;
	$sPriority	= SafeGetArrayItem2Dim($aDiscussions, $i, 'priority');
	$sPriorityImageClass= SafeGetArrayItem2Dim($aDiscussions, $i, 'priorityimageclass');
	$sPriorityTooltip = str_replace('%PRIORITY%', $sPriority, _glt('Priority: xx'));
	$sStatus 	= SafeGetArrayItem2Dim($aDiscussions, $i, 'status');
	$sStatusImageClass	= SafeGetArrayItem2Dim($aDiscussions, $i, 'statusimageclass');
	$sStatusTooltip = str_replace('%STATUS%', $sStatus, _glt('Status: xx'));
	$bHasReplies 	= array_key_exists(("replies"), $aDiscussions[$i]);
	$sIsDisabled 	= '';
	if ($bAddDiscussions)
	{
	$bCanReply = true;
	}
	else
	{
	$bCanReply = false;
	}
	if (strIsEmpty($sReviewGUID) )
	{
	if (($bAddDiscussions && $bCanReply) || $bHasReplies)
	{
	$sHTML .= '<div class="discussion-item collapsible-section-header-closed">';
	$sHTML .= '<img alt="" src="images/spriteplaceholder.png" class="discussion-item-icon collapsible-section-header-closed-icon show-cursor-pointer" onclick="$(this).next().next().next().click()">';
	}
	else
	{
	$sHTML .= '<div class="discussion-item">';
	$sExpandDiscussEvent = '';
	$sIsDisabled 	=  'disabled="true"';
	}
	$sHTML .= WriteAvatarImage($aDiscussions[$i]['avatarid'], 'false');
	$sHTML .= '<div class="discussion-item-states" >';
	$sHTML .= WriteDiscussionStatusMenus($bCanReply, $sDiscussGUID, $sPriorityImageClass, $sPriorityTooltip, $sStatusImageClass, $sStatusTooltip);
	$sHTML .= '</div>';
	$sHTML .= '<div class="discussion-item-text2"' . _h($sIsDisabled) . $sExpandDiscussEvent . '><div class="discussion-item-text">' . _hRichText($sDiscussText) . '</div></div>';
	$sHTML .= '<div class="discussion-item-text-footer">';
	$sHTML .= '<div class="discussion-item-text-footer-dateauthor">' . _h($aDiscussions[$i]['created']) . '&nbsp; &nbsp;' . _h($aDiscussions[$i]['author']) . '</div>';
	$sHTML .= '</div>';
	$sHTML .= '<div class="discussion-item-replies ' . _h($sReplyID) . '">';
	if (array_key_exists(("replies"), $aDiscussions[$i]))
	{
	$aReplies = $aDiscussions[$i]['replies'];
	$iReplyCnt = count($aReplies);
	for ($iR=0; $iR<$iReplyCnt; $iR++)
	{
	$sReplyAuthor 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyauthor');
	$sReplyAvatarID	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyavatarid');
	$sReplyAvatarImage	= SafeGetArrayItem2Dim($aReplies, $iR, 'replyavatarimage');
	$sReplyCreated 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replycreated');
	$sReplyText 	= SafeGetArrayItem2Dim($aReplies, $iR, 'replytext');
	$sHTML .= '<div class="discussion-item-reply">';
	$sHTML .= WriteAvatarImage($sReplyAvatarID, 'true');
	$sHTML .= '<div class="discussion-item-reply-text">' . _hRichText($sReplyText) . '</div>';
	$sHTML .= '<div class="discussion-item-reply-text-footer">';
	$sHTML .= '<div class="discussion-item-reply-text-footer-dateauthor">' . _h($sReplyCreated) . '&nbsp; &nbsp;' . _h($sReplyAuthor) . '</div>';
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	}
	}
	if ($bAddDiscussions)
	{
	if ($bCanReply)
	{
	$sHTML .= '<div class="discussion-reply-form" id="ddrf_' . _h($sDiscussGUID) . '"><form id="drf_' . _h($sDiscussGUID) . '" method="post">';
	$sHTML .= '<div class="discussion-reply-comment-div">';
	$sHTML .= '<textarea class="discussion-reply-new-comment" name="comments" placeholder="' . _glt('Post reply') . '"></textarea>';
	$sHTML .= '</div>';
	$sHTML .= '<div class="discussion-reply-comment-send">';
	$sHTML .= '<div class="discussion-reply-send-div">';
	$sHTML .= '<input class="webea-main-styled-button discussion-reply-send-button" type="submit" value="" onclick="OnFormRunAddReply(event, this.form)">';
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	$sHTML .= '<input type="hidden" name="guid" value="' . _h($sDiscussGUID) .'"> ';
	$sHTML .= '<input type="hidden" name="isreply" value="true"> ';
	$sHTML .= '<input type="hidden" name="objectguid" value="' . _h($sObjectGUID) . '"> ';
	$sHTML .= '</form></div>';
	}
	}
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	}
	}
	return $sHTML;
	}
	function WriteDiscussionStatusMenus($bIsEnabled, $sDiscussGUID, $sPriorityImageClass, $sPriorityTooltip, $sStatusImageClass, $sStatusTooltip)
	{
	$sHTML = '';
	if ($bIsEnabled)
	{
	$sHTML .= '<span id="prioritymenu-button">';
	$sHTML .= '<img alt="" id="priorityimage_' . _h($sDiscussGUID) . '" src="images/spriteplaceholder.png" class="' . _h($sPriorityImageClass) . ' priorityimage_' . _h($sDiscussGUID) . '" title="' . _h($sPriorityTooltip) . '" onclick="ShowMenu(this)">&nbsp;';
	$sHTML .= '<div class="prioritymenu-content" id="prioritymenu-content-' . _h($sDiscussGUID) . '">';
	$sHTML .= '<div class="contextmenu-header">' . _glt('Priority') . '</div>';
	$sHTML .= '<div class="contextmenu-items">';
	$sHTML .= '<div class="contextmenu-item" onclick="SetDiscussState(\'' . _j($sDiscussGUID) . '\',\'priority\',\'High\',\'' . str_replace('%PRIORITY%', _glt('High'), _glt('Priority: xx')) . '\', this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-discusspriorityhigh"></img>' . _glt('High') . '</div>';
	$sHTML .= '<div class="contextmenu-item" onclick="SetDiscussState(\'' . _j($sDiscussGUID) . '\',\'priority\',\'Medium\',\'' . str_replace('%PRIORITY%', _glt('Medium'), _glt('Priority: xx')) . '\', this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-discussprioritymed"></img>' . _glt('Medium') . '</div>';
	$sHTML .= '<div class="contextmenu-item" onclick="SetDiscussState(\'' . _j($sDiscussGUID) . '\',\'priority\',\'Low\',\'' . str_replace('%PRIORITY%', _glt('Low'), _glt('Priority: xx')) . '\', this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-discussprioritylow"></img>' . _glt('Low') . '</div>';
	$sHTML .= '<hr>';
	$sHTML .= '<div class="contextmenu-item" onclick="SetDiscussState(\'' . _j($sDiscussGUID) . '\',\'priority\',\'None\',\'' . str_replace('%PRIORITY%', 'None', _glt('Priority: xx')) . '\', this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-discussprioritynone"></img>' . _glt('<none>') . '</div>';
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	$sHTML .= '</span>';
	$sHTML .= '<span id="statusmenu-button">';
	$sHTML .= '<img alt="" id="statusimage_' . _h($sDiscussGUID) . '" src="images/spriteplaceholder.png" class="' . _h($sStatusImageClass) . ' statusimage_' . _h($sDiscussGUID) . '" title="' . _h($sStatusTooltip) . '" onclick="ShowMenu(this)">&nbsp;';
	$sHTML .= '<div class="statusmenu-content" id="statusmenu-content-' . _h($sDiscussGUID) . '">';
	$sHTML .= '<div class="contextmenu-header">' . _glt('Status') . '</div>';
	$sHTML .= '<div class="contextmenu-items">';
	$sHTML .= '<div class="contextmenu-item" onclick="SetDiscussState(\'' . _j($sDiscussGUID) . '\',\'status\',\'Open\',\'' . str_replace('%STATUS%', _glt('Open'), _glt('Status: xx')) . '\', this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-discussstatusopen"></img>' . _glt('Open') . '</div>';
	$sHTML .= '<div class="contextmenu-item" onclick="SetDiscussState(\'' . _j($sDiscussGUID) . '\',\'status\',\'Awaiting Review\',\'' . str_replace('%STATUS%', _glt('Awaiting Review'), _glt('Status: xx')) . '\', this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-discussstatusawait"></img>' . _glt('Awaiting Review') . '</div>';
	$sHTML .= '<div class="contextmenu-item" onclick="SetDiscussState(\'' . _j($sDiscussGUID) . '\',\'status\',\'Closed\',\'' . str_replace('%STATUS%', _glt('Closed'), _glt('Status: xx')) . '\', this)"><img alt="" src="images/spriteplaceholder.png" class="propsprite-discussstatuscomplete"></img>' . _glt('Closed') . '</div>';
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	$sHTML .= '</span>';
	}
	else
	{
	$sHTML .= '<span id="prioritymenu-button" disabled="true">';
	$sHTML .= '<img alt="" id="priorityimage_' . _h($sDiscussGUID) . '" src="images/spriteplaceholder.png" class="' . _h($sPriorityImageClass) . '  priorityimage_' . _h($sDiscussGUID) .'" title="' . _h($sPriorityTooltip) . '">&nbsp;';
	$sHTML .= '</span>';
	$sHTML .= '<span id="statusmenu-button" disabled="true">';
	$sHTML .= '<img alt="" id="statusimage_' . _h($sDiscussGUID) . '" src="images/spriteplaceholder.png" class="' . _h($sStatusImageClass) . ' statusimage_' . _h($sDiscussGUID) . '" title="' . _h($sStatusTooltip) . '">&nbsp;';
	$sHTML .= '</span>';
	}
	return $sHTML;
	}
	function WriteSectionDMNExpression($aTaggedValues)
	{
	$aData = [];
	$sExpressionType = '';
	foreach ($aTaggedValues as $aTag)
	{
	$sTagName = '';
	$sTagNotes = '';
	$sTagName = SafeGetArrayItem1Dim($aTag, 'name');
	if (($sTagName === 'decisionLogic') ||
	($sTagName === 'encapsulatedLogic'))
	{
	$sTagNotes = SafeGetArrayItem1Dim($aTag, 'notes');
	$sTagNotes = str_replace('<?xml version="1.0" encoding="UTF-16" standalone="no" ?>','',$sTagNotes);
	$aData = [];
	if (!strIsEmpty($sTagNotes))
	{
	$aData = XMLStringToArray($sTagNotes);
	}
	}
	if ($sTagName === 'expressionType')
	{
	$sExpressionType = SafeGetArrayItem1Dim($aTag, 'value');
	}
	}
	$sHTML = '';
	$sHeading = _glt('DMN Expression');
	$sSectionID = 'dmnexpression-section';
	$sHTML .= '<div id="' . _h($sSectionID) . '" class="property-section">';
	$sHTML .= '<div class="properties-header">' . _h($sHeading)  . '</div>';
	$sHTML .= '<div class="properties-content">';
	if ($sExpressionType === 'DecisionTable')
	{
	$sHTML .= WriteDMNDecisionTable($aData);
	}
	else if ($sExpressionType === 'Context')
	{
	$sHTML .= WriteDMNContext($aData);
	}
	else if ($sExpressionType === 'Invocation')
	{
	$sHTML .= WriteDMNInvocation($aData);
	}
	else if ($sExpressionType === 'LiteralExpression')
	{
	$sHTML .= WriteDMNLiteralExpression($aData);
	}
	if (empty($aData))
	{
	$sHTML .= WriteNoContents();
	}
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	return $sHTML;
	}
	function WriteDMNInvocation($aData)
	{
	$sHTML = '';
	if (array_key_exists('dmn:encapsulatedLogic',$aData))
	{
	$aData = SafeGetArrayItem1Dim($aData, 'dmn:encapsulatedLogic');
	}
	$sParamString = GetDMNFormalParameters($aData);
	$aData = SafeGetArrayItem1Dim($aData, 'dmn:invocation');
	$sLiteralExpression =  SafeGetStringFromArray($aData,[ 'dmn:literalExpression', 'dmn:text']);
	if(strIsEmpty($sLiteralExpression))
	{
	$sLiteralExpression =  SafeGetStringFromArray($aData,[ 'dmn:literalExpression', 'dmn:text', '_value']);
	}
	$sRequestOutput =  SafeGetStringFromArray($aData,[ '@attributes', 'requestOutput']);
	$sHeadingText = $sLiteralExpression;
	if (!strIsEmpty($sRequestOutput))
	{
	$sHeadingText .= ' . ' . $sRequestOutput;
	}
	$aBindings = SafeGetArrayItem1Dim($aData, 'dmn:binding');
	ConvertToChildArrayItem('dmn:parameter', $aBindings);
	$aBindingList = [];
	foreach ($aBindings as $aBinding)
	{
	$sParam = SafeGetStringFromArray($aBinding,[ 'dmn:parameter', '@attributes', 'name']);
	$sVal = SafeGetStringFromArray($aBinding,[ 'dmn:literalExpression' ,'dmn:text']);
	if (strIsEmpty($sVal))
	{
	$sVal = SafeGetStringFromArray($aBinding,[ 'dmn:literalExpression' ,'dmn:text', '_value']);
	}
	$aBindingList[$sParam] = $sVal;
	}
	if ((strIsEmpty($sParamString)) && (strIsEmpty($sParamString)) && (empty($aBindingList)))
	{
	return $sHTML;
	}
	$sHTML .= '<table class="dmn-invocation-table">';
	$sHTML .= '<thead>';
	if(!strIsEmpty($sParamString))
	{
	$sHTML .= '<tr>';
	$sHTML .= '<td colspan="2">( ' . _h($sParamString) . ' )</td>';
	$sHTML .= '</tr>';
	}
	$sHTML .= '<tr>';
	$sHTML .= '<td colspan="2">' . _h($sHeadingText) . '</td>';
	$sHTML .= '</tr>';
	$sHTML .= '</thead>';
	$sHTML .= '<tbody>';
	foreach ($aBindingList as $sParam => $sExpression)
	{
	$sHTML .= '<tr><td class="dmn-invocation-param">' . _h($sParam) . '</td><td>' . _h($sExpression) . '</td></tr>';
	}
	$sHTML .= '</tbody>';
	$sHTML .= '</table>';
	return $sHTML;
	}
	function WriteDMNLiteralExpression($aData)
	{
	$sHTML = '';
	$sParamString = '';
	if (array_key_exists('dmn:encapsulatedLogic',$aData))
	{
	$aData = SafeGetArrayItem1Dim($aData, 'dmn:encapsulatedLogic');
	}
	$sParamString = GetDMNFormalParameters($aData);
	$aDMNExpression = SafeGetChildArray($aData, ['dmn:literalExpression']);
	$sLiteralExpression = GetDMNLiteralExpressionText($aDMNExpression);
	$sHTML .= '<table class="dmn-invocation-table">';
	$sHTML .= '<thead>';
	if(!strIsEmpty($sParamString))
	{
	$sHTML .= '<tr>';
	$sHTML .= '<td colspan="2">( ' . _h($sParamString) . ' )</td>';
	$sHTML .= '</tr>';
	}
	$sHTML .= '</thead>';
	$sHTML .= '<tbody>';
	$sHTML .= '<tr><td>' . _h($sLiteralExpression) . '</td></tr>';
	$sHTML .= '</tbody>';
	$sHTML .= '</table>';
	return $sHTML;
	}
	function WriteDMNContext($aData)
	{
	$sFormalParameter = SafeGetChildArray($aData, ['dmn:encapsulatedLogic', 'dmn:formalParameter']);
	$sFormalParameter = ConvertToIndexedArray($sFormalParameter);
	$sParamString = '';
	$bFirst = true;
	foreach ($sFormalParameter as $aParam)
	{
	if($bFirst)
	$bFirst = false;
	else
	$sParamString .= ', ';
	$sParamString .= SafeGetStringFromArray($aParam,['@attributes', 'name']);
	}
	$aContextEntry = SafeGetChildArray($aData, ['dmn:encapsulatedLogic', 'dmn:context', 'dmn:contextEntry']);
	ConvertToChildArrayItem('dmn:literalExpression', $aContextEntry);
	$iHeadingColCount = 2;
	foreach ($aContextEntry as $aEntry)
	{
	if(array_key_exists('dmn:invocation',$aEntry))
	{
	$iHeadingColCount = 3;
	}
	}
	$sHTML = '';
	$sHTML .= '<table class="dmn-context-table">';
	if(!strIsEmpty($sParamString))
	{
	$sHTML .= '<thead>';
	$sHTML .= '<tr>';
	$sHTML .= '<td colspan="'.$iHeadingColCount.'">( ' . _h($sParamString) . ' )</td>';
	$sHTML .= '</tr>';
	$sHTML .= '</thead>';
	}
	$sHTML .= '<tbody>';
	foreach ($aContextEntry as $aEntry)
	{
	$sVariable = SafeGetStringFromArray($aEntry,['dmn:variable', '@attributes', 'name']);
	if (array_key_exists('dmn:invocation',$aEntry))
	{
	$aInvocation = SafeGetChildArray($aEntry, ['dmn:invocation']);
	$sRequestOutput = SafeGetStringFromArray($aInvocation,['@attributes', 'requestOutput']);
	$iRowCount = 0;
	foreach ($aInvocation as $key => $val)
	{
	if ($key ==='dmn:literalExpression')
	{
	$iRowCount++;
	}
	elseif ($key ==='dmn:binding')
	{
	$iRowCount++;
	}
	}
	foreach ($aInvocation as $key => $val)
	{
	if ($key === 'dmn:literalExpression')
	{
	$aDMNExpression = $val;
	$sExpressionVal = GetDMNLiteralExpressionText($aDMNExpression);
	$sHTML .= '<tr><td class="dmn-context-variable" rowspan='.$iRowCount.'>' . _h($sVariable) . '</td><td colspan=2>' . _h($sExpressionVal) . ' . ' . _h($sRequestOutput) . '</td></tr>';
	}
	elseif ($key === 'dmn:binding')
	{
	$sParam = SafeGetStringFromArray($val,[ 'dmn:parameter', '@attributes', 'name']);
	$sExpressionVal = GetDMNLiteralExpressionText($val['dmn:literalExpression']);
	$sHTML .= '<tr><td  class="dmn-context-variable">' . _h($sParam)  . '</td><td>' . _h($sExpressionVal) . '</td></tr>';
	}
	}
	}
	elseif (array_key_exists('dmn:literalExpression',$aEntry))
	{
	$aDMNExpression = SafeGetChildArray($aEntry, ['dmn:literalExpression']);
	$sExpressionVal = GetDMNLiteralExpressionText($aDMNExpression);
	if(strIsEmpty($sVariable))
	{
	$sHTML .= '<tr><td colspan=3>' . _h($sExpressionVal) . '</td></tr>';
	}
	else
	{
	$sHTML .= '<tr><td class="dmn-context-variable">' . _h($sVariable) . '</td><td colspan=2>' . _h($sExpressionVal) . '</td></tr>';
	}
	}
	}
	$sHTML .= '</tbody>';
	$sHTML .= '</table>';
	return $sHTML;
	}
	function WriteDMNDecisionTable($aData)
	{
	$sHTML = '';
	$sHitPolicyAbbrev = '';
	if (array_key_exists('dmn:encapsulatedLogic',$aData))
	{
	$aData = SafeGetArrayItem1Dim($aData, 'dmn:encapsulatedLogic');
	}
	$sParamString = GetDMNFormalParameters($aData);
	$aData = SafeGetArrayItem1Dim($aData, 'dmn:decisionTable');
	$sHitPolicy = SafeGetStringFromArray($aData, ['@attributes', 'hitPolicy']);
	$sAggregation = SafeGetStringFromArray($aData, ['@attributes', 'aggregation']);
	$sOrientation = SafeGetStringFromArray($aData, ['@attributes', 'preferredOrientation']);
	if ($sHitPolicy === 'UNIQUE')
	{
	$sHitPolicyAbbrev = 'U';
	}
	else if ($sHitPolicy === 'ANY')
	{
	$sHitPolicyAbbrev = 'A';
	}
	else if ($sHitPolicy === 'PRIORITY')
	{
	$sHitPolicyAbbrev = 'P';
	}
	else if ($sHitPolicy === 'FIRST')
	{
	$sHitPolicyAbbrev = 'F';
	}
	else if ($sHitPolicy === 'OUTPUT_ORDER')
	{
	$sHitPolicyAbbrev = 'O';
	}
	else if ($sHitPolicy === 'RULE_ORDER')
	{
	$sHitPolicyAbbrev = 'R';
	}
	else if ($sHitPolicy === 'COLLECT')
	{
	$sHitPolicyAbbrev = 'C';
	if ($sAggregation === 'SUM')
	{
	$sHitPolicyAbbrev = $sHitPolicyAbbrev . '+';
	}
	else if ($sAggregation === 'MIN')
	{
	$sHitPolicyAbbrev = $sHitPolicyAbbrev . '<';
	}
	else if ($sAggregation === 'MAX')
	{
	$sHitPolicyAbbrev = $sHitPolicyAbbrev . '>';
	}
	else if ($sAggregation === 'COUNT')
	{
	$sHitPolicyAbbrev = $sHitPolicyAbbrev . '#';
	}
	}
	else if ($sHitPolicy === 'FIRST')
	{
	$sHitPolicyAbbrev = 'F';
	}
	else if ($sHitPolicy === 'FIRST')
	{
	$sHitPolicyAbbrev = 'F';
	}
	else if ($sHitPolicy === 'FIRST')
	{
	$sHitPolicyAbbrev = 'F';
	}
	else
	{
	$sHitPolicyAbbrev = $sHitPolicy;
	}
	$aHeadingInput = SafeGetArrayItem1Dim($aData, 'dmn:input');
	ConvertToChildArrayItem('dmn:inputExpression', $aHeadingInput);
	$aHeadingOutput = SafeGetArrayItem1Dim($aData, 'dmn:output');
	ConvertToChildArrayItem('@attributes', $aHeadingOutput);
	$aHeadingAnnotation = SafeGetArrayItem1Dim($aData, 'dmn:annotation');
	ConvertToChildArrayItem('@attributes', $aHeadingAnnotation);
	if ($sOrientation === 'Rule-as-Row')
	{
	$iHeadingColCount = 1 + count($aHeadingInput) + count($aHeadingOutput) + count($aHeadingAnnotation);
	$sHTML .= '<table class="dmn-expression-table">';
	$sHTML .= '<thead>';
	if(!strIsEmpty($sParamString))
	{
	$sHTML .= '<tr>';
	$sHTML .= '<td colspan="'.$iHeadingColCount.'">( ' . _h($sParamString) . ' )</td>';
	$sHTML .= '</tr>';
	}
	$sHTML .= '<tr>';
	$sHTML .= '<td rowspan="2">' . $sHitPolicyAbbrev . '</td>';
	foreach ($aHeadingInput as $aHeadingCol)
	{
	$aInputExpression = SafeGetArrayItem1Dim($aHeadingCol, 'dmn:inputExpression');
	$sInputExpText = GetDMNText($aInputExpression);
	$sHTML .= '<td  class="dmn-table-input-heading">' . _h($sInputExpText). '</td>';
	}
	foreach ($aHeadingOutput as $aHeadingCol)
	{
	$sName = SafeGetArrayItem1Dim($aHeadingCol, '@attributes');
	$sName = SafeGetArrayItem1Dim($sName, 'name');
	$sHTML .= '<td class="dmn-table-output-heading">' . _h($sName) . '</td>';
	}
	if(!empty($aHeadingAnnotation))
	{
	foreach ($aHeadingAnnotation as $aHeadingCol)
	{
	$sName = SafeGetStringFromArray($aHeadingCol, ['@attributes', 'name']);
	$sHTML .= '<td class="dmn-table-annotation-heading">' . _h($sName) . '</td>';
	}
	}
	$sHTML .= '<tr>';
	foreach ($aHeadingInput as $aHeadingCol)
	{
	$sLabel = SafeGetArrayItem1Dim($aHeadingCol, 'dmn:inputValues');
	$sLabel = SafeGetArrayItem1Dim($sLabel, 'dmn:text');
	$sHTML .= '<td>' . _h($sLabel) . '</td>';
	}
	foreach ($aHeadingOutput as $aHeadingCol)
	{
	$sName = SafeGetArrayItem1Dim($aHeadingCol, 'dmn:outputValues');
	$sName = SafeGetArrayItem1Dim($sName, 'dmn:text');
	$sHTML .= '<td>' . _h($sName) . '</td>';
	}
	if(!empty($aHeadingAnnotation))
	{
	foreach ($aHeadingAnnotation as $aHeadingCol)
	{
	$sHTML .= '<td></td>';
	}
	}
	$sHTML .= '<tr>';
	$sHTML .= '</thead>';
	$sHTML .= '<tbody>';
	$aRules = SafeGetArrayItem1Dim($aData, 'dmn:rule');
	$sRowNum = 0;
	foreach ($aRules as $aRule)
	{
	$sRowNum++;
	$sHTML .= '<tr>';
	$sHTML .= '<td>' . $sRowNum . '</td>';
	$aInputs = SafeGetArrayItem1Dim($aRule, 'dmn:inputEntry');
	ConvertToChildArrayItem('dmn:text', $aInputs);
	foreach ($aInputs as $aInput)
	{
	$sInputText = GetDMNText($aInput);
	$sHTML .= '<td>' . _h($sInputText) . '</td>';
	}
	$aOutputs = SafeGetArrayItem1Dim($aRule, 'dmn:outputEntry');
	ConvertToChildArrayItem('dmn:text', $aOutputs);
	foreach ($aOutputs as $aOutput)
	{
	$sOutputText = GetDMNText($aOutput);
	$sHTML .= '<td>' . _h($sOutputText) . '</td>';
	}
	$sAnnotations = SafeGetArrayItem1Dim($aRule, 'dmn:annotationEntry');
	ConvertToChildArrayItem('dmn:text', $sAnnotations);
	if(!empty($aHeadingAnnotation))
	{
	foreach ($sAnnotations as $sAnnotation)
	{
	$sAnnoText = SafeGetArrayItem1Dim($sAnnotation, 'dmn:text');
	$sHTML .= '<td>' . _h($sAnnoText) . '</td>';
	}
	}
	$sHTML .= '</tr>';
	}
	$sHTML .= '</tbody>';
	$sHTML .= '</table>';
	}
	else if ($sOrientation === 'Rule-as-Column')
	{
	$sHTML .= '<table class="dmn-expression-table">';
	$aRules = SafeGetArrayItem1Dim($aData, 'dmn:rule');
	$iHeadingColCount = 2 + count($aRules);
	if(!strIsEmpty($sParamString))
	{
	$sHTML .= '<tr>';
	$sHTML .= '<td colspan="'.$iHeadingColCount.'">( ' . _h($sParamString) . ' )</td>';
	$sHTML .= '</tr>';
	}
	$sHTML .= '<tbody>';
	$i=0;
	foreach ($aHeadingInput as $aHeadingCol)
	{
	$sLabel = SafeGetArrayItem1Dim($aHeadingCol, 'dmn:inputExpression');
	$sLabel = SafeGetArrayItem1Dim($sLabel, 'dmn:text');
	$sHTML .= '<tr><td  class="dmn-table-input-heading">' . _h($sLabel) . '</td>';
	$sLabel2 = SafeGetArrayItem1Dim($aHeadingCol, 'dmn:inputValues');
	$sLabel2 = SafeGetArrayItem1Dim($sLabel2, 'dmn:text');
	$sHTML .= '<td>' . _h($sLabel2) . '</td>';
	$aRules = SafeGetArrayItem1Dim($aData, 'dmn:rule');
	foreach ($aRules as $aRule)
	{
	$aInputs = SafeGetArrayItem1Dim($aRule, 'dmn:inputEntry');
	ConvertToChildArrayItem('dmn:text', $aInputs);
	$sInputText = SafeGetArrayItem1Dim($aInputs[$i], 'dmn:text');
	$sHTML .= '<td>' . _h($sInputText) . '</td>';
	}
	$sHTML .= '</tr>';
	$i++;
	}
	$i=0;
	foreach ($aHeadingOutput as $aHeadingCol)
	{
	$sName = SafeGetArrayItem1Dim($aHeadingCol, '@attributes');
	$sName = SafeGetArrayItem1Dim($sName, 'name');
	$sHTML .= '<tr><td class="dmn-table-output-heading">' . _h($sName) . '</td>';
	$sLabel2 = SafeGetArrayItem1Dim($aHeadingCol, 'dmn:outputValues');
	$sLabel2 = SafeGetArrayItem1Dim($sLabel2, 'dmn:text');
	$sHTML .= '<td>' . _h($sLabel2) . '</td>';
	$aRules = SafeGetArrayItem1Dim($aData, 'dmn:rule');
	foreach ($aRules as $aRule)
	{
	$aOutputs = SafeGetArrayItem1Dim($aRule, 'dmn:outputEntry');
	ConvertToChildArrayItem('dmn:text', $aOutputs);
	$sOutputText = SafeGetArrayItem1Dim($aOutputs[$i], 'dmn:text');
	$sHTML .= '<td>' . _h($sOutputText) . '</td>';
	}
	'</tr>';
	$i++;
	}
	if(!empty($aHeadingAnnotation))
	{
	$i=0;
	foreach ($aHeadingAnnotation as $aHeadingCol)
	{
	$sName = SafeGetStringFromArray($aHeadingCol, ['@attributes', 'name']);
	$sHTML .= '<tr><td class="dmn-table-annotation-heading">' . _h($sName) . '</td>';
	$sHTML .= '<td></td>';
	$aRules = SafeGetArrayItem1Dim($aData, 'dmn:rule');
	foreach ($aRules as $aRule)
	{
	$aAnnotations = SafeGetArrayItem1Dim($aRule, 'dmn:annotationEntry');
	ConvertToChildArrayItem('dmn:text', $aAnnotations);
	$sAnnoText = SafeGetArrayItem1Dim($aAnnotations[$i], 'dmn:text');
	$sHTML .= '<td>' . _h($sAnnoText) . '</td>';
	}
	$sHTML .= '</tr>';
	$i++;
	}
	}
	$sHTML .= '<tr><td colspan="2">' . $sHitPolicyAbbrev . '</td>';
	$i=1;
	foreach ($aRules as $aRule)
	{
	$sHTML .= '<td>' . $i  . '</td>';
	$i++;
	}
	$sHTML .= '</tr>';
	$sHTML .= '</tbody>';
	$sHTML .= '</table>';
	}
	else if ($sOrientation === 'CrossTable')
	{
	$aRules = SafeGetArrayItem1Dim($aData, 'dmn:rule');
	$aInput1Rules = [];
	foreach ($aRules as $aRule)
	{
	$aRuleInput = SafeGetChildArray($aRule,['dmn:inputEntry']);
	$aInput1Rules[] = $aRuleInput[0]['dmn:text'];
	}
	$aInput1Rules = array_unique($aInput1Rules);
	foreach ($aRules as $aRule)
	{
	$aInput2Rules[] = $aRule['dmn:inputEntry'][1]['dmn:text'];
	}
	$aInput2Rules = array_unique($aInput2Rules);
	$iInput2RulesCount = count($aInput2Rules);
	$aSecondaryInputs = [];
	$aSecondaryInputs = GetDMNCrosstabSecondaryInputsArray($aRules);
	$sHTML .= '<table class="dmn-expression-table">';
	$iHeadingColCount = 2 + count($aSecondaryInputs);
	if(!strIsEmpty($sParamString))
	{
	$sHTML .= '<tr>';
	$sHTML .= '<td colspan="'.$iHeadingColCount.'">( ' . _h($sParamString) . ' )</td>';
	$sHTML .= '</tr>';
	}
	$sHTML .= '<tbody>';
	$sHTML .= '<tr >';
	$iInputCount = count($aRules[0]['dmn:inputEntry']);
	foreach ($aHeadingOutput as $aHeadingCol)
	{
	$sName = SafeGetArrayItem1Dim($aHeadingCol, '@attributes');
	$sName = SafeGetArrayItem1Dim($sName, 'name');
	$sHTML .= '<td class="dmn-table-output-heading" colspan="2" rowspan="'.$iInputCount.'">' . _h($sName) . '</td>';
	}
	$sHeading = '';
	$i=0;
	$bFirst = true;
	foreach ($aHeadingInput as $aHeading)
	{
	$sLabel = '';
	$sLabel = SafeGetArrayItem1Dim($aHeading, 'dmn:inputExpression');
	$sLabel = SafeGetArrayItem1Dim($sLabel, 'dmn:text');
	if ($i !== 0)
	{
	if ($bFirst)
	{
	$sHeading = $sHeading . $sLabel;
	$bFirst = false;
	}
	else
	{
	$sHeading = $sHeading . ', ' .$sLabel;
	}
	}
	$i++;
	}
	$sHTML .= '<td class="dmn-table-input-heading" colspan="' . count($aSecondaryInputs) . '">' . _h($sHeading). '</td>';
	$sHTML .= '</tr>';
	$i = 0;
	while ($i < ($iInputCount - 1))
	{
	$aRow = [];
	$sHTML .= '<tr>';
	foreach ($aSecondaryInputs as $aRule)
	{
	$sHTML .= '<td>' . _h($aRule[$i]['dmn:text']) . '</td>';
	}
	$i++;
	$sHTML .= '</tr>';
	}
	$sHTML .= '</tr>';
	$aInput1Heading = $aHeadingInput[0];
	$aInputExpression = SafeGetArrayItem1Dim($aInput1Heading, 'dmn:inputExpression');
	$sLabel = GetDMNText($aInputExpression);
	$iInputCount = count($aInput1Rules);
	$aSecondaryInputsVals = [];
	foreach ($aSecondaryInputs as $aSecondaryInput)
	{
	$aRow = [];
	foreach ($aSecondaryInput as $aCol)
	{
	$aRow[] = $aCol['dmn:text'];
	}
	$aSecondaryInputsVals[] = $aRow;
	}
	$bIsFirst = true;
	foreach ($aInput1Rules as $sInput1Rule)
	{
	$sHTML .= '<tr>';
	if ($bIsFirst)
	{
	$sHTML .= '<td  class="dmn-table-input-heading" rowspan="'.$iInputCount.'">' . _h($sLabel) . '</td>';
	$bIsFirst = false;
	}
	$sHTML .= '<td >' . _h($sInput1Rule) . '</td>';
	foreach ($aSecondaryInputsVals as $aSecondaryInputsVal)
	{
	$sValue = '';
	$aCombinedInputs = [];
	foreach ($aRules as $aRule)
	{
	$aInput1RuleArray = [];
	$aInput1RuleArray[] = $sInput1Rule;
	$aCombinedInputs = array_merge($aInput1RuleArray,$aSecondaryInputsVal);
	$aRuleInputValues = [];
	$aInputEntries = $aRule['dmn:inputEntry'];
	foreach ($aInputEntries as $aInputEntry)
	{
	$aRuleInputValues[] = $aInputEntry['dmn:text'];
	}
	if ($aCombinedInputs === $aRuleInputValues)
	{
	$sValue = $aRule['dmn:outputEntry']['dmn:text'];
	}
	}
	$sHTML .= '<td class="dmn-table-output-heading">' . _h($sValue) . '</td>';
	}
	$sHTML .= '</tr>';
	}
	$sHTML .= '</tbody>';
	$sHTML .= '</table>';
	}
	return $sHTML;
	}
	function GetDMNText($aData)
	{
	$sText = '';
	$sText = SafeGetArrayItem1Dim($aData, 'dmn:text');
	if (is_array($sText))
	{
	$sText = SafeGetArrayItem1Dim($sText, '_value');
	}
	return $sText;
	}
	function GetDMNFormalParameters($aData)
	{
	$sFormalParameter = SafeGetChildArray($aData, ['dmn:formalParameter']);
	$sFormalParameter = ConvertToIndexedArray($sFormalParameter);
	$sParamString = '';
	$bFirst = true;
	foreach ($sFormalParameter as $aParam)
	{
	if($bFirst)
	$bFirst = false;
	else
	$sParamString .= ', ';
	$sParamString .= SafeGetStringFromArray($aParam,['@attributes', 'name']);
	}
	return $sParamString;
	}
	function GetDMNLiteralExpressionText($aDMNExpression)
	{
	$sExpTextVal = '';
	$sExpressionLanguage = SafeGetStringFromArray($aDMNExpression,['@attributes', 'expressionLanguage']);
	if ($sExpressionLanguage === '')
	$sExpressionLanguage = 'FEEL';
	$aExpressionText = SafeGetChildArray($aDMNExpression, ['dmn:text']);
	ConvertToChildArrayItem('_value',$aExpressionText);
	foreach($aExpressionText as $aExp)
	{
	$sItemLanguage = SafeGetStringFromArray($aExp,['@attributes', 'language']);
	if ($sItemLanguage === $sExpressionLanguage)
	{
	$sExpTextVal = $aExp['_value'];
	}
	}
	if (empty($aExpressionText))
	{
	$sExpTextVal = GetDMNText($aDMNExpression);
	}
	return $sExpTextVal;
	}
	function GetDMNCrosstabSecondaryInputsArray($aRules)
	{
	foreach ($aRules as $aRule)
	{
	$aInputEntries = [];
	$aInputEntries = $aRule['dmn:inputEntry'];
	array_shift($aInputEntries);
	$aSecondaryInputs[]=$aInputEntries;
	}
	$x=0;
	$aRows = [];
	foreach ($aSecondaryInputs as $aSecondaryInput)
	{
	$bIsUnique = true;
	$y=0;
	foreach ($aSecondaryInputs as $a)
	{
	if ($x !== $y)
	{
	if ($aSecondaryInput === $a)
	{
	if($y < $x)
	{
	$bIsUnique = false;
	}
	}
	}
	$y++;
	}
	$x++;
	if ($bIsUnique)
	{
	$aRows[] = $aSecondaryInput;
	}
	}
	$aSecondaryInputs = $aRows;
	return $aSecondaryInputs;
	}
	function WriteSectionEmpty($sHeading)
	{
	$sHTML = '';
	if(!IsSessionSettingTrue('propsview_hide_empty'))
	{
	$sHTML .= '<div class="propsview-section" style="display:block">';
	$sHTML .= '<div class="miniprops-header">'.$sHeading.'</div>';
	$sHTML .= '<div class="props-view-empty">';
	$sHTML .= WriteNoContents();
	$sHTML .= '</div>';
	$sHTML .= '</div>';
	}
	return $sHTML;
	}
	function StripSpecialChars($string) {
	$string = str_replace(' ', '-', $string);
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}
	function SortByPostion($a, $b) {
	return $a['position'] - $b['position'];
	}
?>