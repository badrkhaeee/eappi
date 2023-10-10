<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
header('Content-type: text/plain');
header('Content-disposition: attachment; filename="pcsrequest.csr"');
$sRootPath = dirname(__FILE__);
require_once $sRootPath . '/globals.php';
SafeStartSession();
$sCertificate = SafeGetInternalArrayParameter($_SESSION, 'certificate');
echo $sCertificate;
unset($_SESSION['certificate']);
?>