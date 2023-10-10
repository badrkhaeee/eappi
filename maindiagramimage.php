<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
if (!isset($webea_page_parent_mainview)) {
	exit();
}
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/globals.php';
SafeStartSession();
CheckAuthorisation();
$iDiagramZoom = SafeGetInternalArrayParameter($_SESSION, 'diagram_zoom', 100);
if ($sResType === "Diagram") {
	$g_sViewingMode	= '2';
	if (!strIsEmpty($sObjectGUID)) {
		$bShowMiniProps = IsSessionSettingTrue('show_propertiesview');
		$sImgBin 	= '';
		$sImgMap 	= '';
		include('./data_api/get_diagramimage.php');
		if (!IsHTTPSuccess(http_response_code())) {
			exit();
		}
		echo '<div id="main-diagram-image" class="main-view diagram-related-bkcolor' . GetShowBrowserMinPropsStyleClasses() . '">';
		if (strlen($sImgBin) > 0) {
			echo '<div class="main-diagram-inner">';
			$sImgMap = ScaleImageMap($sImgMap, $iDiagramZoom);
			$iDiagramImgZoom = $iDiagramZoom / 100;
			echo $sImgMap;
			if (isset($_SESSION['ios_scroll']) && ($_SESSION['ios_scroll'] === 'true')) {
				echo '<img id="diagram-image" onload="this.width*=' . $iDiagramImgZoom . ';this.onload=null;" src="data:image/png;base64,' . $sImgBin . '" alt="' . _h($sDiagramNotes) . '" usemap=""/>';
			} else {
				echo '<img id="diagram-image" onload="this.width*=' . $iDiagramImgZoom . ';this.onload=null;" src="data:image/png;base64,' . $sImgBin . '" alt="' . _h($sDiagramNotes) . '" usemap="#diagrammap"/>';
			}
			echo PHP_EOL . '<svg id="svg-overlay" guid="" width="0" height="0">';
			echo '</svg>';
			echo '</div>';
			echo '<iframe id="main_diagram_iframe" style="display:none;"></iframe>';
		} else {
			$sOSLCErrorMsg = BuildOSLCErrorString();
			if (strIsEmpty($sOSLCErrorMsg)) {
				$sOSLCErrorMsg =  _glt('Invalid or missing diagram');
				$sOSLCErrorMsg .= '<br><br>';
				$sLink = '<a href="' . g_csHelpLocation . 'model_repository/webea_troubleshoot.html">Troubleshooting WebEA</a>';
				$sOSLCErrorMsg .= str_replace('%LINK%', $sLink, _glt('See Troubleshooting help topic'));
			}
			echo '<div class="main-diagram-inner">' . $sOSLCErrorMsg . '</div>';
		}
		echo '</div>';
	}
}
function ScaleImageMap($sImgMap, $iPercentage = 100)
{
	$sNewImgMap = '';
	$iPercentage = $iPercentage / 100;
	$aImg = explode('coords="', $sImgMap);
	$i = 0;
	foreach ($aImg as &$sLine) {
		$sCoord = '';
		$aCoords = [];
		if ($i !== 0) {
			$iCoEnd = strpos($sLine, '"');
			$sOrigCoord = substr($sLine, 0, $iCoEnd);
			$aCoords = explode(',', $sOrigCoord);
			$sNewCoords = '';
			$bIsFirst = true;
			foreach ($aCoords as &$sCoord) {
				$sCoord = intval($sCoord * $iPercentage);
				if ($bIsFirst)
					$bIsFirst = false;
				else
					$sCoord = ',' . $sCoord;
				$sNewCoords .= $sCoord;
			}
			$sLine = str_replace($sOrigCoord, $sNewCoords, $sLine);
			$sLine = 'coords="' . $sLine;
		}
		$i++;
	}
	$sNewImgMap = implode('', $aImg);
	return $sNewImgMap;
}
?>
<script>
	$(document).ready(function() {
		HighlightElementOnClick();
	});
</script>