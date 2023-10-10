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
	if (!isset($sImagePath))
	$sImagePath = 'images/';
	echo '<div id="webea-nojs-popup">';
	echo '<div class="webea-nojs-popup-section">';
	echo '<div class="webea-nojs-image">';
	echo '<img alt="" src="' . $sImagePath . 'spriteplaceholder.png" class="mainsprite-warning">';
	echo '</div>';
	echo '<div class="webea-nojs-line1">WebEA requires Javascript, please enable and refresh.</div>';
	echo '</div>';
	echo '</div>';
	echo '<div id="webea-nocookies-popup">';
	echo '<div class="webea-nocookies-popup-section">';
	echo '<div class="webea-nocookies-image">';
	echo '<img alt="" src="' . $sImagePath . 'spriteplaceholder.png" class="mainsprite-warning">';
	echo '</div>';
	echo '<div class="webea-nocookies-line1">WebEA requires cookies, please enable and refresh.</div>';
	echo '</div>';
	echo '</div>';
?>
<script>
	document.getElementById("webea-nojs-popup").style.display = 'none';
	$(document).ready(function()
	{
	$("#webea-main-content").show();
	CheckCookiesEnabled();
	});
</script>