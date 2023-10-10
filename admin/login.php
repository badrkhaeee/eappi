<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	$sRootPath = dirname(__FILE__);
	require_once $sRootPath . '/globals.php';
	if (!isset($bViaIndex) && ($_SERVER["REQUEST_METHOD"] !== "POST"))
	{
	echo _glt('Direct navigation is not allowed. Click <a href="index.php">here</a> to login');
	exit();
	}
	echo '<div id="login-prompt">';
	echo '<form id="config-login-form" role="form" onsubmit="onFormSubmit(event, \'#config-login-form\', \'login\')">';
	echo '<div class="login-heading">';
	echo '<div id="main-header-label">PCS Configuration - Login</div>';
	echo '</div>';
	echo '<div class="login-contents">';
	echo '<div class="config-section">';
	echo '<div class="config-line">';
	WriteLabel('Password','','config-login-pwd-label');
	WriteTextField('','','','name="pwd" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" type="password"');
	echo '</div>';
	echo '</div>';
	echo '<div>';
	WriteButton('Login','','button button-login','type="submit"');
	echo '</div>';
	echo '</div>';
	echo '</form>';
	echo '</div>';
	echo '<footer>';
	echo '<div id="main-footer">';
	echo '<div class="main-footer-copyright"> Copyright &copy; ' . g_csCopyRightYears . ' Sparx Systems Pty Ltd.  All rights reserved.</div> ';
	echo '<div class="main-footer-version"> WebConfig v' . g_csWebConfigVersion . ' </div>';
	echo '</div>';
	echo '</footer>';
?>
<style>
#main-footer
{
	position: absolute;
	height: 14px;
	left: 0px;
	bottom: 0px;
	right: 0px;
	color: #333;
	margin: 0px 10px 0 10px;
	font-size: 10px;
	overflow: hidden;
	margin-bottom:6px;
}
.main-footer-copyright {
    display: inline;
}
.main-footer-version {
    display: inline;
    float: right;
}
.main-contents-margin
{
	top:0px;
	background-color: #e1e1e1;
}
.main-contents{
	padding: 0px;
	background-color: #e1e1e1;
}
.login-heading{
	width:100%;
	background-color: #3777bf;
	border-bottom: 3px solid #8caed4;
	font-size: 22px;
	color: white;
	border-radius: 3px 3px 0px 0px;
	text-align: center;
}
#main-header-label{
	padding:14px;
}
.config-section{
	padding-top:16px;
}
.config-group{
	padding-top:16px;
}
#login-prompt{
	margin: 70px auto 150px auto;
	border: 2px solid #a1a1a1;
	background: white;
	width: 360px;
	border-radius: 5px;
	box-shadow: 7px 7px 6px;
	font-size: 14px;
}
.login-contents{
	padding:14px;
}
.label{
	width:100px;
}
</style>
<script>
var bIsDirty = true;
sessionStorage.setItem("current_page","login.php");
sessionStorage.setItem("current_action","login");
sessionStorage.setItem("current_id","login");
</script>