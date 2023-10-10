<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
define('g_csCopyRightYears','2016 - 2021');
define('g_csWebConfigVersion', 	'4.2.65.2250');
define('g_csHelpLocation', 	'https://www.sparxsystems.com/enterprise_architect_user_guide/15.2/');
	define('g_cbDebugging', false);
	$g_sOSLCString 	= '';
	$gLog 	= new Logging(g_cbDebugging);
	$sRootPath 	= dirname(__FILE__);
	$g_sViewingMode	= '0';
	$g_sLastOSLCErrorCode	= '';
	$g_sLastOSLCErrorMsg	= '';
class Logging
{
    private $m_sLogFilename, $fp, $m_bDebugging;
    public function __construct($sDebug)
	{
        $this->m_bDebugging = false;
	if (strIsTrue($sDebug))
	$this->m_bDebugging = true;
    }
    public function SetLogfilePath($sPath)
	{
        $this->m_sLogFilename = $sPath;
    }
    public function ClearLog()
	{
	if (!is_resource($this->fp))
	{
	$bFileOK = $this->open();
	}
	else
	{
	$bFileOK = true;
	}
	if ( $bFileOK )
	{
	ftruncate($this->fp, 0);
	rewind($this->fp);
	fclose($this->fp);
	}
    }
    public function Write2Log($message)
	{
	if ($this->m_bDebugging)
	{
	$bFileOK = false;
	if (!is_resource($this->fp))
	{
	$bFileOK = $this->open();
	}
	else
	{
	$bFileOK = true;
	}
	if ( $bFileOK )
	{
	$sPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	$time = @date('[Y-m-d H:i:s.u]');
	fwrite($this->fp, $time . ' (' . $sPage . ') ' . $message . PHP_EOL);
	}
	}
    }
    public function Write2Console($data)
    {
	echo '<script>';
	echo 'console.log('. json_encode($data) .')';
	echo '</script>';
    }
    public function close()
	{
        fclose($this->fp);
    }
    private function open()
	{
	$bOK = false;
	$sLogFilename_default = '/tmp/webconfig_logfile.log';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            $sLogFilename_default = 'c:/temp/webconfig_logfile.log';
        $sPath = $this->m_sLogFilename ? $this->m_sLogFilename : $sLogFilename_default;
	$sFolder = '';
	$iPos = strripos($sPath, '/');
	if ( $iPos !== FALSE )
	{
	$sFolder = substr($sPath, 0, $iPos);
	}
	if ( $sFolder!=='' && is_dir($sFolder) )
	{
	$this->fp = fopen($sPath, 'a') or exit('Can\'t open ' . $sPath . '!');
	$bOK = true;
	}
	return $bOK;
    }
}
	function is_session_started()
	{
	if ( php_sapi_name() !== 'cli' ) {
	if ( version_compare(phpversion(), '5.4.0', '>=') )
	{
	return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	}
	else
	{
	return session_id() === '' ? FALSE : TRUE;
	}
	}
	return FALSE;
	}
	function SafeStartSession()
	{
	if (is_session_started()===FALSE)
	{
	session_name('webconfig');
	session_start();
	}
	}
	function IsSessionSettingTrue($sName)
	{
	$sValue = SafeGetInternalArrayParameter($_SESSION, $sName, 'false');
	$bReturn = strIsTrue($sValue);
	return $bReturn;
	}
    function _glt($sLookup)
	{
	global $gLog;
	global $g_aLocaleText;
	$sReturn = $sLookup;
	if (isset($g_aLocaleText))
	{
	if ( is_array($g_aLocaleText) )
	{
	if (array_key_exists($sLookup, $g_aLocaleText))
	{
	$sReturn = $g_aLocaleText[$sLookup];
	}
	else
	{
	}
	}
	}
	return $sReturn;
	}
	function strIsEmpty($sValue)
	{
	$bReturn = true;
	if (isset($sValue) === true)
	{
	if ($sValue !== '')
	$bReturn = false;
	}
	return $bReturn;
	}
	function strIsTrue($sValue)
	{
	$bReturn = false;
	if (isset($sValue) === true)
	{
	$sValue = mb_strtolower($sValue);
	if ($sValue === '1' || $sValue === 't' || $sValue === 'true')
	{
	$bReturn = true;
	}
	}
	return $bReturn;
	}
	function SafeGetArrayItem1Dim($a, $sItemName, $sDefault = '')
	{
	$sReturn = '';
	if ((is_array($a)) && ($a !== null))
	{
	if (array_key_exists($sItemName, $a))
	{
	$sReturn = $a[$sItemName];
	}
	}
	if ((is_array($sReturn)) && (empty($sReturn)))
	{
	$sReturn = '';
	}
	if ((strIsEmpty($sReturn)) && (!strIsEmpty($sDefault)))
	{
	$sReturn = $sDefault;
	}
	return $sReturn;
	}
	function SafeGetArrayItem1DimInt($a, $sItemName)
	{
	$iReturn = (int)0;
	if ($a !== null)
	{
	if (array_key_exists($sItemName, $a))
	{
	$iReturn = (int)$a[$sItemName];
	}
	}
	return $iReturn;
	}
	function SafeGetArrayItem2Dim($a, $iRow, $sItemName)
	{
	$sReturn = '';
	if ($iRow < 0)
	{
	$iRow = 0;
	}
	if ($a !== null)
	{
	if (count($a) > $iRow)
	{
	if (array_key_exists($sItemName, $a[$iRow]))
	{
	$sReturn = $a[$iRow][$sItemName];
	}
	}
	}
	return $sReturn;
	}
	function SafeGetArrayItem2DimByName($a, $sName, $sItemName, $sDefault='')
	{
	$sReturn = $sDefault;
	if ( !strIsEmpty($sName) )
	{
	if ($a !== null)
	{
	if (count($a) > 0)
	{
	if (array_key_exists($sName, $a))
	{
	if (array_key_exists($sItemName, $a[$sName]))
	{
	$sReturn = $a[$sName][$sItemName];
	}
	}
	}
	}
	}
	return $sReturn;
	}
	function SafeGetArrayItem2DimByNameValuePair($a, $sInName, $sSearchItem, $sReturnFieldName)
	{
	$sReturn = '';
	if ( !strIsEmpty($sInName) && !strIsEmpty($sReturnFieldName) )
	{
	if ($a !== null)
	{
	$iCnt = count($a);
	if ($iCnt > 0)
	{
	for ($i=0;$i<$iCnt;$i++)
	{
	if ( $sSearchItem === SafeGetArrayItem2Dim($a, $i, $sInName))
	{
	$sReturn = SafeGetArrayItem2Dim($a, $i, $sReturnFieldName);
	break;
	}
	}
	}
	}
	}
	return $sReturn;
	}
	function SafeGetInternalArrayParameter($a, $sParameterName, $sDefault='')
	{
	$sReturn = $sDefault;
	if (array_key_exists($sParameterName, $a))
	{
	if (isset($a[$sParameterName]))
	$sReturn = $a[$sParameterName];
	}
	return $sReturn;
	}
	$sRootPath = dirname(__FILE__);
	function SafeXMLLoad(&$xmlDoc, $sXML, $bHuge=false)
	{
	$sHideErrors = SafeGetInternalArrayParameter($_SESSION, 'hide_xml_errors', 'true');
	if (strIsTrue($sHideErrors))
	$bPrevState = libxml_use_internal_errors(true);
	else
	$bPrevState = libxml_use_internal_errors(false);
	if ($bHuge)
	$isValid = $xmlDoc->loadXML($sXML, LIBXML_PARSEHUGE);
	else
	$isValid = $xmlDoc->loadXML($sXML);
	libxml_clear_errors();
	libxml_use_internal_errors($bPrevState);
	return ($isValid !== false);
	}
	function SafeHTMLLoad(&$dDoc, $sHTML, $bHuge=false)
	{
	$sHideErrors = SafeGetInternalArrayParameter($_SESSION, 'hide_xml_errors', 'true');
	if (strIsTrue($sHideErrors))
	$bPrevState = libxml_use_internal_errors(true);
	else
	$bPrevState = libxml_use_internal_errors(false);
	if ($bHuge)
	$bIsValid = $dDoc->loadHTML($sXML, LIBXML_PARSEHUGE);
	else
	$bIsValid = $dDoc->loadHTML($sXML);
	libxml_clear_errors();
	libxml_use_internal_errors($bPrevState);
	return ($bIsValid !== false);
	}
	function GetXMLFirstChild($xn)
	{
	$x = null;
	if ($xn !== null)
	{
	$x = $xn->firstChild;
	if ($x !== null)
	{
	while ($x->nodeType === XML_TEXT_NODE || $x->nodeType === XML_COMMENT_NODE)
	{
	$x = $x->nextSibling;
	}
	}
	}
	return $x;
	}
	function GetXMLNodeValue($xn, $sName, &$sValue)
	{
	if ($xn !== null)
	{
	if ($xn->nodeName === $sName)
	{
	$sValue = $xn->nodeValue;
	}
	}
	}
	function GetXMLNodeAttribute($xn, $sAttributeName)
	{
	$sValue = '';
	if ($xn !== null)
	{
	if ($xn->nodeType !== XML_TEXT_NODE || $xn->nodeType !== XML_COMMENT_NODE)
	{
	if ( $xn->hasAttribute($sAttributeName) )
	{
	$sValue = $xn->getAttribute($sAttributeName);
	}
	}
	}
	return $sValue;
	}
	function GetXMLNodeBoolValue($xn, $sName, &$sValue)
	{
	if ($xn !== null)
	{
	if ($xn->nodeName === $sName)
	{
	$sValue = 'False';
	$s = $xn->nodeValue;
	if ( strIsTrue($s) )
	{
	$sValue = 'True';
	}
	}
	}
	}
	function GetXMLNodeValueAttr($xn, $sName, $sAttr, &$sValue)
	{
	if ($xn !== null)
	{
	if ($xn->nodeName === $sName)
	{
	$sValue = $xn->getAttribute($sAttr);
	}
	}
	}
	function GetAuthorFromXMLNode($xn, &$sValue)
	{
	if ($xn !== null)
	{
	if ($xn->nodeName === "dcterms:creator")
	{
	$xnFC = $xn->firstChild;
	$xnFC = $xnFC->firstChild;
	$sValue = $xnFC->nodeValue;
	}
	}
	}
	function RemoveTime($sDateTime)
	{
	$sReturn = $sDateTime;
	$iPos = stripos($sDateTime, ' ', 0);
	if ( $iPos !== FALSE )
	{
	$sReturn = substr($sDateTime, 0, $iPos);
	}
	return $sReturn;
	}
	function RemoveDate($sDateTime)
	{
	$sReturn = $sDateTime;
	$iPos = stripos($sDateTime, ' ', 0);
	if ( $iPos !== FALSE )
	{
	$sReturn = substr($sDateTime, $iPos+1);
	}
	return $sReturn;
	}
	function microSecondsToMilli($t)
	{
	$ms = round($t * 1000);
	$format = array_sum( explode( ' ' , $ms ) ) . 'ms';
	return $format;
	}
	function IsDateValid(&$sDate)
	{
	$bRet = false;
	$s = trim($sDate);
	if (mb_strlen($s)>=1 && mb_strlen($s)<=2)
	{
	$s = date("m") . '-' . $s;
	}
	if (mb_strlen($s)>=3 && mb_strlen($s)<=5)
	{
	$s = date("Y") . '-' . $s;
	}
	$s = str_replace('/', '-', $s);
	$s = str_replace(' ', '-', $s);
	$s = str_replace('.', '-', $s);
	if (mb_strlen($s)==8 && mb_substr($s,2,1)=='-' && mb_substr($s,5,1)=='-')
	{
	$s = mb_substr(date("Y"), 0, 2) . $s;
	}
	$aDateParts  = explode('-', $s);
	if (count($aDateParts) == 3) {
	if (checkdate($aDateParts[1], $aDateParts[2], $aDateParts[0])) {
	$sDate = date('Y-m-d', mktime(0, 0, 0, $aDateParts[1], $aDateParts[2], $aDateParts[0]));
	$bRet = true;
	}
	}
	return $bRet;
	}
	function setResponseCode($code, $reason = null)
	{
	$code = intval($code);
	if (version_compare(phpversion(), '5.4', '>') && is_null($reason))
	{
	http_response_code($code);
	}
	else
	{
	header(trim("HTTP/1.0 $code $reason"));
	echo $reason;
	}
	}
	function HandleKnownErrors($sError)
	{
	$sReturn = $sError;
	$sError = mb_strtolower($sError);
	if ( mb_strpos($sError, 'error:140770fc', 0) !== false )
	{
	$sReturn = _glt('unknown protocol error');
	}
	if ( mb_strpos($sError, 'connection refused', 0) !== false ) {
	$sReturn = _glt('no response from the server');
	}
	if ( $sError === 'recv failure: connection reset by peer' ) {
	$sReturn = _glt('invalid connection configuration');
	}
	if ( $sError === 'request does not contain user id' ) {
	$sReturn = _glt('Security is enabled but no credentials were provided');
	}
	if ( ((mb_strpos($sError, 'operation timed out after', 0) !== false) ||
	  (mb_strpos($sError, 'connection timed out after', 0) !== false) ) &&
	   mb_strpos($sError, 'milliseconds', 0) !== false )
	{
	$sReturn = _glt('Server could not be found');
	}
	return $sReturn;
	}
	function GetHTTPErrorMessage($httpCode, $sBody)
	{
	$sReturn = '';
	if ($httpCode == 401)
	{
	$sReturn = _glt('Unauthorized Credentials. Click <a href="" onclick="onClickButton(\'logout\',\'logout\')">here</a> to login');
	}
	elseif ($httpCode == 403)
	{
	if(!strIsEmpty($sBody))
	{
	$sReturn = $sBody;
	}
	else
	{
	$sReturn = 'Error 403';
	}
	}
	elseif ($httpCode == 455)
	{
	$sReturn = _glt('selected database is shutdown');
	}
	elseif ($httpCode == 456)
	{
	$sReturn = _glt('selected database is not defined');
	}
	elseif ($httpCode == 457)
	{
	$sReturn = _glt('selected database does not support pro');
	}
	elseif ($httpCode == 458)
	{
	$sReturn = _glt('invalid cloud connection string');
	}
	elseif ($httpCode == 501)
	{
	$sReturn = _glt('server is not configured to support OSLC');
	}
	return $sReturn;
	}
	function GetOSLCErrorFromXML($sXML)
	{
	$bNoError = true;
	$xmlDoc = null;
	if ( !strIsEmpty($sXML) && $sXML !== false)
	{
	$xmlDoc = new DOMDocument();
	SafeXMLLoad($xmlDoc, $sXML);
	}
	if ($xmlDoc != null)
	{
	$xnRoot = $xmlDoc->documentElement;
	$xnDesc = GetXMLFirstChild($xnRoot);
	$bNoError = GetOSLCError($xnDesc);
	}
	return $bNoError;
	}
	function GetOSLCSuccess($xmlDoc)
	{
	$sReturn = '';
	if ($xmlDoc != null)
	{
	$xnRoot 	= $xmlDoc->documentElement;
	$xn 	= $xnRoot->firstChild;
	$sReturn 	= GetXMLNodeAttribute($xn, 'rdf:about');
	}
	return $sReturn;
	}
	function GetOSLCError($xn)
	{
	$GLOBALS['g_sLastOSLCErrorCode'] = '';
	$GLOBALS['g_sLastOSLCErrorMsg']  = '';
	$bNoError = true;
	if ($xn != null && $xn->childNodes != null)
	{
	foreach ($xn->childNodes as $xnC)
	{
	GetXMLNodeValue($xnC, 'oslc:statusCode', $GLOBALS['g_sLastOSLCErrorCode']);
	GetXMLNodeValue($xnC, 'oslc:message', $GLOBALS['g_sLastOSLCErrorMsg']);
	}
	if ( !strIsEmpty($GLOBALS['g_sLastOSLCErrorCode']) || !strIsEmpty($GLOBALS['g_sLastOSLCErrorMsg']) )
	{
	$bNoError = false;
	}
	}
	return $bNoError;
	}
	function Check4OSLCErrorFromXML($sXML, &$sErrorCode, &$sErrorMsg)
	{
	$bNoError = true;
	$xmlDoc = null;
	if ( !strIsEmpty($sXML) && $sXML !== false)
	{
	$xmlDoc = new DOMDocument();
	SafeXMLLoad($xmlDoc, $sXML);
	}
	if ($xmlDoc != null)
	{
	$xnRoot = $xmlDoc->documentElement;
	$xnDesc = GetXMLFirstChild($xnRoot);
	$bNoError = ReadOSLCError($xnDesc, $sErrorCode, $sErrorMsg);
	}
	return $bNoError;
	}
	function ReadOSLCError($xn, &$sErrorCode, &$sErrorMsg)
	{
	$sErrorCode = '';
	$sErrorMsg  = '';
	$bNoError = true;
	if ($xn != null && $xn->childNodes != null)
	{
	foreach ($xn->childNodes as $xnC)
	{
	GetXMLNodeValue($xnC, 'oslc:statusCode', $sErrorCode);
	GetXMLNodeValue($xnC, 'oslc:message', $sErrorMsg);
	}
	}
	return $bNoError;
	}
	function BuildOSLCErrorString()
	{
	if ( $GLOBALS['g_sLastOSLCErrorMsg'] === 'Request does not contain User ID' )
	{
	$GLOBALS['g_sLastOSLCErrorMsg'] = _glt('Security credentials were not provided');
	}
	$sErrorMsg = '';
	if ( !strIsEmpty($GLOBALS['g_sLastOSLCErrorMsg']) )
	{
	if ( !strIsEmpty($GLOBALS['g_sLastOSLCErrorCode']) )
	$sErrorMsg = $GLOBALS['g_sLastOSLCErrorCode'] . ' - ' . $GLOBALS['g_sLastOSLCErrorMsg'];
	else
	$sErrorMsg = $GLOBALS['g_sLastOSLCErrorMsg'];
	}
	elseif  ( !strIsEmpty($GLOBALS['g_sLastOSLCErrorCode']) )
	{
	$sErrorMsg = $GLOBALS['g_sLastOSLCErrorCode'] . ' - Unknown OSLC error';
	}
	return $sErrorMsg;
	}
	function GetOSLCCallName($sURL)
	{
	$sRet = '';
	if ( !strIsEmpty($sURL) )
	{
	$iPos = mb_strpos($sURL, '/oslc/am/');
	if ($iPos!==false)
	{
	$sRet = mb_substr($sURL, $iPos+8);
	}
	$iPos = mb_strpos($sRet, '/?');
	if ($iPos!==false)
	{
	$sRet = mb_substr($sRet, 0, $iPos+1);
	}
	}
	return $sRet;
	}
	function TrimInternalURL($sFullURL, $sFind)
	{
	$sNew = '';
	if ( !strIsEmpty($sFullURL))
	{
	$iPos = strpos($sFullURL, $sFind);
	if ( $iPos !== FALSE )
	{
	$sFullURL = substr($sFullURL, $iPos+strlen($sFind));
	$sNew = trim($sFullURL, "/");
	}
	else
	{
	$sNew = $sFullURL;
	}
	$sNew = urldecode($sNew);
	}
	return $sNew;
	}
	function CookieNameEncoding($sCookieName)
	{
	$sReturn = $sCookieName;
	$sReturn = str_replace('\t', '0d09', $sReturn);
	$sReturn = str_replace('\r', '0d13', $sReturn);
	$sReturn = str_replace('\n', '0d10', $sReturn);
	$sReturn = str_replace(' ',  '0d32', $sReturn);
	$sReturn = str_replace('!',  '0d33', $sReturn);
	$sReturn = str_replace('#',  '0d35', $sReturn);
	$sReturn = str_replace('%',  '0d37', $sReturn);
	$sReturn = str_replace('\'', '0d39', $sReturn);
	$sReturn = str_replace('&',  '0d38', $sReturn);
	$sReturn = str_replace('*',  '0d42', $sReturn);
	$sReturn = str_replace('+',  '0d43', $sReturn);
	$sReturn = str_replace(',',  '0d44', $sReturn);
	$sReturn = str_replace(':',  '0d58', $sReturn);
	$sReturn = str_replace(';',  '0d59', $sReturn);
	$sReturn = str_replace('=',  '0d61', $sReturn);
	$sReturn = str_replace('?',  '0d63', $sReturn);
	$sReturn = str_replace('@',  '0d64', $sReturn);
	$sReturn = str_replace('.',  '0d89', $sReturn);
	$sReturn = str_replace('/',  '0d90', $sReturn);
	$sReturn = str_replace('[',  '0d91', $sReturn);
	$sReturn = str_replace("\\", '0d92', $sReturn);
	$sReturn = str_replace(']',  '0d93', $sReturn);
	$sReturn = str_replace('^',  '0d94', $sReturn);
	$sReturn = str_replace('{',  '0d123', $sReturn);
	$sReturn = str_replace('|',  '0d124', $sReturn);
	$sReturn = str_replace('}',  '0d125', $sReturn);
	$sReturn = str_replace('~',  '0d126', $sReturn);
	return $sReturn;
	}
	function AddURLParameter(&$sParaString, $sParaName, $sParaValue, $bIncludeBlank=false)
	{
	if ( !strIsEmpty($sParaValue) || $bIncludeBlank )
	{
	$iPos = strpos($sParaString, '?');
	if ( strIsEmpty($sParaString) || $iPos === false)
	{
	$sParaString .= '?';
	}
	else
	{
	$sParaString .= '&';
	}
	$sParaString .= $sParaName . '=' . $sParaValue;
	}
	}
	function EncloseInCDATA($sString)
	{
	$sReturn = $sString;
	if ( !strIsEmpty($sString) )
	{
	$sReturn = '<![CDATA[' . $sString . ']]>';
	}
	return $sReturn;
	}
	function ParseINIFile2Array($sFilename, $bEncrypt=true)
	{
	global $gLog;
	$aSettings = array();
	if ( file_exists ( $sFilename ) )
	{
	$p_ini = parse_ini_file($sFilename, true);
	foreach ($p_ini as $sNamespace => $properties)
	{
	$sName = $sNamespace;
	$sExtends = '';
	$iPos = stripos($sNamespace, ':');
	if ($iPos!==false)
	{
	list($sName, $sExtends) = explode(':', $sNamespace);
	$sName = trim($sName);
	$sExtends = trim($sExtends);
	}
	if (!isset($aSettings[$sName]))
	{
	$aSettings[$sName] = array();
	}
	if (isset($p_ini[$sExtends]))
	{
	foreach ($p_ini[$sExtends] as $sProp => $sVal)
	{
	if ( $sProp === 'password' && $bEncrypt)
	{
	$sVal = md5($sVal);
	}
	$aSettings[$sName][$sProp] = $sVal;
	}
	}
	$bFoundAuthCode = false;
	foreach($properties as $sProp => $sVal)
	{
	if ( $sProp === 'auth_code' && $bEncrypt)
	{
	$sVal = md5($sVal);
	$bFoundAuthCode = true;
	}
	elseif ( $sProp === 'auth_code' && !$bEncrypt)
	{
	$bFoundAuthCode = true;
	}
	$aSettings[$sName][$sProp] = $sVal;
	}
	if (!$bFoundAuthCode)
	{
	if ($sName !== 'model_list')
	{
	if($bEncrypt)
	{
	$aSettings[$sName]['auth_code'] = md5('');
	}
	else
	{
	$aSettings[$sName]['auth_code'] = '';
	}
	}
	}
	}
	}
        return $aSettings;
	}
	function LimitDisplayString($sStr, $iMaxLen=30)
	{
	$sNew = $sStr;
	if ($iMaxLen<=0)
	{
	$iMaxLen = 30;
	}
	if (strlen($sStr) > $iMaxLen+3)
	{
	$sNew = substr($sStr, 0, $iMaxLen) . '...';
	}
	elseif ( strIsEmpty($sNew) )
	{
	$sNew = _glt('<Unnamed object>');
	}
	return $sNew;
	}
	function ConvertStringToParameter($sName)
	{
	$sName = addslashes($sName);
	$sName  = htmlspecialchars($sName);
	$sName = preg_replace('~[\r\n]+~', '\\\r\n', $sName);
	return $sName;
	}
	function BuildHTMLComboFromArray($aItems, $aAttribs, $bAllowBlank=false, $sDefaultValue='')
	{
	$sHTML 	= '';
	$iCnt = count($aItems);
	if ( $iCnt>0 )
	$sHTML 	= '<select ';
	else
	$sHTML 	= '<input ';
	if (is_array($aAttribs))
	{
	foreach ($aAttribs as $sKey => $sValue)
	{
	if ( $iCnt>0 || ($iCnt===0 && $sValue!=='webea-main-styled-combo') )
	{
	$sHTML .= ' ' . $sKey . '="' . $sValue . '"';
	}
	elseif ( $iCnt===0 && $sValue==='webea-main-styled-combo' )
	{
	$sHTML .= ' class="webea-main-styled-textbox2"';
	}
	}
	}
	if ( $iCnt>0 )
	{
	$sHTML .= '>'. PHP_EOL;
	if ($bAllowBlank)
	{
	$sHTML .= '<option value="[blank]">&nbsp;</option>';
	}
	if (is_array($aItems))
	{
	foreach ($aItems as $sKey => $sValue)
	{
	$bSelectedFound = false;
	if ( !strIsEmpty($sDefaultValue) && $sDefaultValue===$sValue)
	{
	$sHTML .= '<option value="' . htmlspecialchars($sValue) . '" selected>' . htmlspecialchars($sValue) . '</option>';
	$bSelectedFound = true;
	}
	else
	$sHTML .= '<option value="' . htmlspecialchars($sValue) . '">' . htmlspecialchars($sValue) . '</option>';
	}
	if ( !$bSelectedFound && !strIsEmpty($sDefaultValue) && $bAllowBlank)
	{
	$sHTML .= '<option value="' . htmlspecialchars($sDefaultValue) . '" selected>' . htmlspecialchars($sDefaultValue) . '</option>';
	}
	}
	$sHTML .= '</select>';
	}
	else
	{
	if ( !strIsEmpty($sDefaultValue) )
	{
	$sHTML .= ' value="' . $sDefaultValue . '"';
	}
	$sHTML .= '>'. PHP_EOL;
	}
	return $sHTML;
	}
	function BuildHTMLComboFrom2DimArray($aItems, $aAttribs, $bAllowBlank=false, $sDefaultValue='')
	{
	$sHTML 	= '';
	$iCnt = count($aItems);
	if ( $iCnt>0 )
	$sHTML 	= '<select ';
	else
	$sHTML 	= '<input ';
	if (is_array($aAttribs))
	{
	foreach ($aAttribs as $sKey => $sValue)
	{
	if ( $iCnt>0 || ($iCnt===0 && $sValue!=='webea-main-styled-combo') )
	{
	$sHTML .= ' ' . $sKey . '="' . $sValue . '"';
	}
	elseif ( $iCnt===0 && $sValue==='webea-main-styled-combo' )
	{
	$sHTML .= ' class="webea-main-styled-textbox2"';
	}
	}
	}
	if ( $iCnt>0 )
	{
	$sHTML .= '>'. PHP_EOL;
	if ($bAllowBlank)
	{
	$sHTML .= '<option value="[blank]">&nbsp;</option>';
	}
	if (is_array($aItems))
	{
	for ($i=0; $i<$iCnt; $i++)
	{
	$sLabel = SafeGetArrayItem2Dim($aItems, $i, 'label');
	$sValue = SafeGetArrayItem2Dim($aItems, $i, 'value');
	$bSelectedFound = false;
	if ( !strIsEmpty($sDefaultValue) && $sDefaultValue===$sValue)
	{
	$sHTML .= '<option value="' . htmlspecialchars($sValue) . '" selected>' . htmlspecialchars($sLabel) . '</option>';
	$bSelectedFound = true;
	}
	else
	$sHTML .= '<option value="' . htmlspecialchars($sValue) . '">' . htmlspecialchars($sLabel) . '</option>';
	}
	if ( !$bSelectedFound && !strIsEmpty($sDefaultValue) && $bAllowBlank)
	{
	$sHTML .= '<option value="' . htmlspecialchars($sDefaultValue) . '" selected>' . htmlspecialchars($sDefaultValue) . '</option>';
	}
	}
	$sHTML .= '</select>';
	}
	else
	{
	if ( !strIsEmpty($sDefaultValue) )
	{
	$sHTML .= ' value="' . $sDefaultValue . '"';
	}
	$sHTML .= '>'. PHP_EOL;
	}
	return $sHTML;
	}
	function mb_ucfirst($string)
	{
	    $strlen = mb_strlen($string);
	    $firstChar = mb_substr($string, 0, 1);
	    $otherChars = mb_substr($string, 1, $strlen - 1);
	    return mb_strtoupper($firstChar) . $otherChars;
	}
	function BuildWebUID($sFeatureAbbr, $sParentGUID, $iItemNo)
	{
	$sNum 	= str_pad((string)$iItemNo, 5, "0", STR_PAD_LEFT);
	$sParent 	= substr($sParentGUID, 4);
	$sParent 	= trim($sParent, '}');
	return $sFeatureAbbr . '-' . $sParent . '-' . $sNum;
	}
	function GetCurrentURL()
	{
	$sFullURL  = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
	$sFullURL .= '://' . $_SERVER['HTTP_HOST'];
	$sDocPath = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	$iLastPos = strripos($sDocPath, '?');
	if ( $iLastPos !== FALSE && $iLastPos >= 0 )
	{
	$sDocPath = substr($sDocPath, 0, $iLastPos);
	}
	$iLastPos = strripos($sDocPath, '/');
	if ( $iLastPos !== FALSE && $iLastPos >= 0 )
	{
	$sDocPath = substr($sDocPath, 0, $iLastPos);
	}
	$iLastPos = strripos($sDocPath, '/data_api');
	if ( $iLastPos !== FALSE && $iLastPos >= 0 )
	{
	$sDocPath = substr($sDocPath, 0, $iLastPos);
	}
	$sFullURL .= $sDocPath;
	return $sFullURL;
	}
	function GetOpenIDRedirectURL()
	{
	return GetCurrentURL() . '/login_sso.php';
	}
	if (!function_exists('http_parse_headers'))
	{
	function http_parse_headers($raw_headers)
	{
	$headers = array();
	$key = '';
	foreach(explode("\n", $raw_headers) as $i => $h)
	{
	$h = explode(':', $h, 2);
	if (isset($h[1])) {
	if (!isset($headers[$h[0]]))
	{
	$headers[$h[0]] = trim($h[1]);
	}
	elseif (is_array($headers[$h[0]]))
	{
	$headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
	}
	else
	{
	$headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
	}
	$key = $h[0];
	}
	else
	{
	if (substr($h[0], 0, 1) == "\t")
	$headers[$key] .= "\r\n\t".trim($h[0]);
	elseif (!$key)
	$headers[0] = trim($h[0]);
	}
	}
	return $headers;
	}
	}
	function getBearerParams($autheticateHeader)
	{
	global $gLog;
	$params[] = array();
	$autheticateHeader = str_replace('Bearer ', '', $autheticateHeader);
	foreach(explode(',', $autheticateHeader) as $i => $param)
	{
	$param = explode('=', $param, 2);
	$params[$param[0]] = trim($param[1], '"');
	}
	return $params;
	}
	function HTTPGetXML($sURL)
	{
	global $gLog;
	if ( strIsEmpty($sURL) )
	{
	return null;
	}
	$sOSLCCallName = '';
	$sPHPPage = '';
	$aSystemOutput = array();
	$bShowingSysOut = (IsSessionSettingTrue('show_system_output') && g_cbDebugging);
	if ( $bShowingSysOut )
	{
	$sPHPPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	$sOSLCCallName = GetOSLCCallName($sURL);
	$aSystemOutput 	= GetSystemOutputSessionArray();
	$aSystemOutput[]	= ' --  (' . $sPHPPage . ') start - GET  ' . $sOSLCCallName ;
	$timeStart = microtime(true);
	}
	$xmlDoc = null;
	$sErrorMsg = '';
	$sXML = HTTPGetXMLRaw($sURL, $sErrorMsg);
	if ( !strIsEmpty($sErrorMsg) )
	{
	if (
	($sErrorMsg === _glt('selected database does not support pro')) ||
	($sErrorMsg === _glt('selected database is shutdown')) ||
	($sErrorMsg === 'Request Error: ' . _glt('Server could not be found')) ||
	($sErrorMsg === _glt('A secure connection is required'))
	)
	{
	echo '<div id="webea-display-warning-message" class="http-error" style="display: block;">';
	echo '<div class="webea-display-warning-message-text" id="webea-display-warning-message-text">';
	echo $sErrorMsg. ' Click <a href="javascript:OnLogoff()">here</a> to re-login.';
	echo '</div>';
	echo '</div>';
	}
	else
	{
	echo $sErrorMsg;
	}
	}
	if ( !strIsEmpty($sXML) && $sXML !== false)
	{
	$xmlDoc = new DOMDocument();
	SafeXMLLoad($xmlDoc, $sXML, true);
	}
	if ( $bShowingSysOut )
	{
	$timeEnd = microtime(true);
	$timeDiff = $timeEnd - $timeStart;
	$sRunTime = microSecondsToMilli($timeDiff);
	ExtractSystemOutputDetails($xmlDoc, $aSystemOutput);
	$aSystemOutput[]	= ' --  (' . $sPHPPage . ') end  - ' . $sRunTime . ' to run GET ' . $sOSLCCallName;
	SaveSystemOutputSessionArray($aSystemOutput);
	}
	return $xmlDoc;
	}
	function HTTPGetXMLRaw($sURL, &$sErrorMsg)
	{
	return HTTPSendRequest(CURLOPT_HTTPGET, $sURL, '', $sErrorMsg);
	}
	function HTTPPostXML($sURL, $sInXML, $bCheckOSLCError=true)
	{
	global $gLog;
	if ( strIsEmpty($sURL) )
	{
	return null;
	}
	$sOSLCCallName = '';
	$sPHPPage = '';
	$aSystemOutput = array();
	$bShowingSysOut = (IsSessionSettingTrue('show_system_output') && g_cbDebugging);
	if ( $bShowingSysOut )
	{
	$sPHPPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	$sOSLCCallName = GetOSLCCallName($sURL);
	$aSystemOutput 	= GetSystemOutputSessionArray();
	$aSystemOutput[]	= ' --  (' . $sPHPPage . ') start - POST  ' . $sOSLCCallName ;
	$timeStart = microtime(true);
	}
	$xmlDoc = null;
	$sErrorMsg = '';
	$sOutXML = HTTPPostXMLRaw($sURL, $sInXML, $sErrorMsg, $bCheckOSLCError);
	if ( !strIsEmpty($sErrorMsg) )
	echo $sErrorMsg;
	if ( !strIsEmpty($sOutXML) && $sOutXML !== false)
	{
	$xmlDoc = new DOMDocument();
	SafeXMLLoad($xmlDoc, $sOutXML);
	}
	if ( $bShowingSysOut )
	{
	$timeEnd = microtime(true);
	$timeDiff = $timeEnd - $timeStart;
	$sRunTime = microSecondsToMilli($timeDiff);
	$gLog->Write2Log('HTTPPostXML:    ' . $sRunTime . '    ' . $sURL);
	ExtractSystemOutputDetails($xmlDoc, $aSystemOutput);
	$aSystemOutput[]	= ' --  (' . $sPHPPage . ') end  - ' . $sRunTime . ' to run POST ' . $sOSLCCallName;
	SaveSystemOutputSessionArray($aSystemOutput);
	}
	return $xmlDoc;
	}
	function HTTPPostXMLRaw($sURL, $sPostBody, &$sErrorMsg, $bCheckOSLCError=true)
	{
	return HTTPSendRequest(CURLOPT_POST, $sURL, $sPostBody, $sErrorMsg, $bCheckOSLCError);
	}
	function HTTPSendRequest($method, $sURL, $sPostBody, &$sErrorMsg, $bCheckOSLCError=true)
	{
	SafeStartSession();
	global $gLog;
	$sBody = '';
	if ( strIsEmpty($sURL) )
	{
	return $sBody;
	}
	$iMaxCommTime = (int)SafeGetInternalArrayParameter($_SESSION, 'max_communication_time', '30');
	ini_set('max_execution_time', ($iMaxCommTime*3));
	$aHeaders = array();
	$sLicense = 'IMPORTANT!!     Creating software that imitates or pretends to be the Pro Cloud Server WebConfig is not permitted.';
	$sLicense = 'Circumvention, reverse engineering or otherwise bypassing any restrictions imposed by this license';
	$sLicense = 'is NOT permitted and will be considered a breach of copyright.';
	$sRemoteAddress = '';
	$sRemoteAddress = $_SERVER['REMOTE_ADDR'];
	$sRemoteAddress = EncryptDecrypt($sRemoteAddress, true);
	$aHeaders[] = 'kiritaki: ' . $sRemoteAddress;
	$login = 'admin';
	$password = SafeGetInternalArrayParameter($_SESSION, 'pcsa');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $sURL);
	if ($method == CURLOPT_POST)
	{
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $sPostBody);
	$aHeaders[] = 'Content-type: text/xml;';
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, $iMaxCommTime);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$sEnforceCerts = SafeGetInternalArrayParameter($_SESSION, 'enforce_certs', 'true');
	if (strIsTrue($sEnforceCerts))
	{
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	}
	else
	{
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	}
	$aHeaders[] = 'Authentication-Context: ' . session_id();
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	$resend = true;
	while ($resend)
	{
	$resend = false;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
	$sResponse = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curlInfo = curl_getinfo($ch);
	$headerSize = $curlInfo['header_size'];
	$sHeaders = substr($sResponse, 0, $headerSize);
	$headers = http_parse_headers($sHeaders);
	$sBody = substr($sResponse, $headerSize);
	if (curl_errno($ch))
	{
	$sErrorMsg = 'Request Error: ' . HandleKnownErrors(curl_error($ch));
	}
	else
	{
	$sErrorMsg = GetHTTPErrorMessage($httpCode, $sBody);
	if ( !strIsEmpty($httpCode) && $bCheckOSLCError )
	{
	$sErrCode = '';
	$sErrMsg = '';
	Check4OSLCErrorFromXML($sBody, $sErrCode, $sErrMsg);
	if ( !strIsEmpty($sErrMsg) )
	{
	$GLOBALS['g_sLastOSLCErrorCode'] = $sErrCode;
	$GLOBALS['g_sLastOSLCErrorMsg']  = $sErrMsg;
	$sErrorMsg = BuildOSLCErrorString();
	}
	}
	}
	}
	curl_close($ch);
	return $sBody;
	}
	function WriteButton($sText, $sID = '', $sClass = '', $sAttributes = '')
	{
	if ($sClass === '')
	$sClass = 'class="button"';
	else
	$sClass = 'class="' .$sClass. '"';
	echo '<button '. $sClass . ' ' . $sAttributes.'>'.$sText.'</button>';
	}
	function WriteCheckBox($sText, $sID = '', $sClass = '', $sAttributes = '', $bIsChecked = false, $bCheckboxFirst = false)
	{
	$sChecked = '';
	if (($bIsChecked === 'true') || ($bIsChecked === 't') || ($bIsChecked === 'True')|| ($bIsChecked === '1'))
	$sChecked = 'checked';
	$sName = mb_strtolower($sText);
	$sName= str_replace(' ', '-', $sName);
	$sName= str_replace('(', '', $sName);
	$sName= str_replace(')', '', $sName);
	$sName = $sName . '_checkbox';
	if ($bCheckboxFirst === true)
	{
	echo '<input type="checkbox" '.$sAttributes.' value="1" '.$sChecked.'> '.$sText;
	}
	else
	{
	WriteLabel($sText);
	echo '<input type="checkbox" '.$sAttributes.' value="1" '.$sChecked.'>';
	}
	}
	function WriteRadio($sText, $sID = '', $sClass = '', $sAttributes = '', $sName = 'radio_button', $bIsSelected = false)
	{
	$sChecked = '';
	if ($bIsSelected)
	$sChecked = 'checked';
	echo '<input id="'.$sID.'" class="config-radio" type="radio" name="'.$sName.'" '.$sAttributes.' '.$sChecked .'><a class="config-radio-label" onclick="selectRadio(this)">' . $sText.'</a>';
	}
	function WriteDropDown($aList, $sID = '', $sClass = 'config-dropdown', $sAttributes = '', $sDefault = '', $bIncludeValues = false)
	{
	if(empty($sDefault))
	$sDefault = '';
	if(strIsEmpty($sClass))
	{
	$sClass = 'config-dropdown';
	}
	if(!strIsEmpty($sID))
	{
	$sID = ' id="' . $sID . '"';
	}
	echo '<select '.$sID.' class="'.$sClass.'" '.$sAttributes.'>';
	foreach ($aList as $sItem)
	{
	if($bIncludeValues)
	{
	$sItemValue = $sItem[1];
	$sItem = $sItem[0];
	}
	else
	{
	$sItemValue = $sItem;
	}
	if ($sItem === $sDefault)
	{
	$sSelected = 'selected';
	}
	else
	{
	$sSelected = '';
	}
	echo '<option value="'.$sItemValue.'" '.$sSelected.'>'.$sItem.'</option>';
	}
	echo '</select>';
	}
	function WriteHeading($sText, $sID = '', $sClass = '', $sAttributes = '')
	{
	if ($sClass !=='')
	$sClass = 'class="'.$sClass.'"';
	else
	$sClass = 'class="heading"';
	echo '<div '.$sClass.'>'.$sText.'</div>';
	}
	function WriteLabel($sText, $sID = '', $sClass = '', $sAttributes = '')
	{
	if(!strIsEmpty($sClass))
	{
	$sClass = 'class="'. $sClass . '"';
	}
	else
	{
	$sClass = 'class="label"';
	}
	echo '<div '. $sClass . ' ' . $sAttributes . '>' . $sText . '</div>';
	}
	function WriteValue($sText, $sID = '', $sClass = 'textvalue-medium', $sAttributes = '')
	{
	if(!strIsEmpty($sID))
	{
	$sID = 'id="'.$sID.'"';
	}
	echo '<div '.$sID.' class="'.$sClass.'" '.$sAttributes.'>'.$sText.'</div>';
	}
	function WriteTextArea($sText, $sID ='', $sClass = 'textarea', $sAttributes = '')
	{
	if ($sID !== '')
	{
	$sID = 'id="'.$sID.'"';
	}
	echo '<textarea '.$sID.' class='.$sClass.' rows="3" cols="50" '.$sAttributes.'>';
	echo $sText;
	echo '</textarea>';
	}
	function WriteTextField($sText, $sID = '', $sClass = 'textfield-medium', $sAttributes = '')
	{
	if(!strIsEmpty($sID))
	{
	$sID = 'id="'.$sID. '"';
	}
	if(strIsEmpty($sClass))
	$sClass = 'textfield-medium';
	if((is_array($sText)) && (empty($sText)))
	$sText = '';
	echo '<input '.$sID.' class="'.$sClass.'" '.$sAttributes.' value="'.$sText.'">';
	}
	function WriteCombo($sInputAttributes, $sListID, $aListItems)
	{
	echo '<input ' .$sInputAttributes . ' list="'. $sListID .'">';
	echo '<datalist id="'.$sListID .'">';
	foreach ($aListItems as $sOptionValue)
	{
	echo '<option value="'.$sOptionValue.'">';
	}
	echo '</datalist>';
	}
	function WriteTable($sTableAttr, $aHeader, $aData, $aFields)
	{
	echo '<table '.$sTableAttr.'>';
	echo '<tbody>';
	echo '<tr>';
	foreach ($aHeader as $header)
	{
	echo '<th>'.$header.'</th>';
	}
	echo '</tr>';
	foreach ($aData as $aRow)
	{
	echo '<tr '.SafeGetArrayItem1Dim($aRow,'tr_attributes').'>';
	foreach ($aFields as $field)
	{
	$sFieldContent = SafeGetArrayItem1Dim($aRow, $field);
	if(is_array($sFieldContent))
	$sFieldContent = '';
	if ($sFieldContent === "True")
	$sFieldContent = '<div><div class="table-icon-container"><img alt="" class="tick-icon" src="images/spriteplaceholder.png"></div></div>';
	if ($sFieldContent === "False")
	$sFieldContent = "";
	echo '<td>'.$sFieldContent.'</td>';
	}
	echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	}
	function SafeGetArray(&$aArray, $sKey)
	{
	if (!empty($aArray))
	{
	if(array_key_exists($sKey, $aArray))
	{
	if(array_key_exists(0, $aArray[$sKey]))
	{
	$aArray = $aArray[$sKey];
	}
	}
	}
	else
	{
	$aArray = [];
	}
	}
	function GetPostResults($sURL, $sPostBody, &$sError, $aDataPath = [], $bIsList = false)
	{
	$aData = [];
	$xmlDoc = HTTPPostXMLRaw($sURL,$sPostBody, $sPostError);
	$aData = json_decode(json_encode(simplexml_load_string($xmlDoc)), true);
	if(!strIsEmpty($sPostError))
	{
	$sError = $sPostError;
	echo $sError;
	exit;
	}
	else if ($aData['return']['return-code'] !== '0')
	{
	$sError = 'Error: ' . $aData['return']['return-code'] . ' - ' . $aData['return']['return-message'];
	echo $sError;
	exit;
	}
	if(empty($aDataPath))
	{
	return $aData['results'];
	}
	else
	{
	$aData = $aData['results'];
	$i=0;
	if(empty($aData))
	{
	return $aData;
	}
	if(count($aDataPath) == 1)
	{
	$aData = $aData[$aDataPath[$i]];
	}
	else
	{
	while ($i < count($aDataPath))
	{
	if ($i !== (count($aDataPath) - 1))
	{
	$aData = SafeGetArrayItem1Dim($aData, $aDataPath[$i]);
	}
	else
	{
	if ($bIsList)
	{
	SafeGetArray($aData, $aDataPath[$i]);
	}
	else
	{
	if(is_array($aData))
	{
	$aData = $aData[$aDataPath[$i]];
	}
	}
	}
	$i++;
	}
	}
	return $aData;
	}
	}
	function GetData($aData = [], $aDataPath = [], $bIsList = false)
	{
	$i=0;
	if(empty($aData))
	{
	return $aData;
	}
	if(count($aDataPath) == 1)
	{
	$aData = $aData[$aDataPath[$i]];
	}
	else
	{
	while ($i < count($aDataPath))
	{
	if ($i !== (count($aDataPath) - 1))
	{
	$aData = SafeGetArrayItem1Dim($aData, $aDataPath[$i]);
	}
	else
	{
	if ($bIsList)
	{
	SafeGetArray($aData, $aDataPath[$i]);
	}
	else
	{
	if(is_array($aData))
	{
	$aData = $aData[$aDataPath[$i]];
	}
	}
	}
	$i++;
	}
	}
	return $aData;
	}
	function WriteBreadcrumb($sHeader, $sHelpURL = 'model_repository/cloud_server_client_web.html')
	{
	$sHelpURL = g_csHelpLocation . $sHelpURL;
	echo '<div class="breadcrumb">';
	echo '<div class="heading-breadcrumb">';
	if($sHeader === 'Home')
	{
	echo '<a>'.$sHeader.'</a>';
	}
	else if($sHeader === 'Login')
	{
	echo '<a>'.$sHeader.'</a>';
	}
	else
	{
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'home.php\',null,null,true,true)">Home</a>';
	}
	if (
	($sHeader === 'Server Settings') ||
	($sHeader === 'Ports') ||
	($sHeader === 'Integration') ||
	($sHeader === 'Manage EA Floating Licenses') ||
	($sHeader === 'Logs') ||
	($sHeader === 'Select Connection Type') ||
	($sHeader === 'Edit Model Connection')
	)
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if (($sHeader === 'Add Port') ||
	($sHeader === 'Edit Port'))
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'ports.php\',null,null,true,true)">Ports</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if (($sHeader === 'Add Data Provider') ||
	($sHeader === 'Edit Data Provider') ||
	($sHeader === 'Edit Bindings')
	)
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'integrations.php\',null,null,true,true)">Integration</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if (($sHeader === 'Add Floating Licenses') || ($sHeader === 'Floating License Groups'))
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'floating-licenses.php\',null,null,true,true)">Manage EA Floating Licenses</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if (($sHeader === 'Pro Cloud Server Licenses') || ($sHeader === 'Change Password') || ($sHeader === 'Manage Licenses'))
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-config.php\',null,null,true,true)">Server Settings</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if ($sHeader === 'Add Pro Cloud Server License')
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-config.php\',null,null,true,true)">Server Settings</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-licenses.php\',null,null,true,true)">Pro Cloud Server Licenses</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if ($sHeader === 'Allocate Tokens')
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-config.php\',null,null,true,true)">Server Settings</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'allocate-tokens.php\',null,null,true,true)">Allocate Tokens</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if (($sHeader === 'New License Request') ||
	(($sHeader === 'Renew License Request')))
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-config.php\',null,null,true,true)">Server Settings</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-licenses.php\',null,null,true,true)">Pro Cloud Server Licenses</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if ($sHeader === 'Manage Allocations')
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-config.php\',null,null,true,true)">Server Settings</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'pcs-licenses.php\',null,null,true,true)">Pro Cloud Server Licenses</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if ($sHeader === 'Add Model Connection')
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'dbmanager-select-type.php\',null,null,true,true)">Select Connection Type</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	else if (($sHeader === 'Add Group') || ($sHeader === 'Edit Group'))
	{
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'floating-licenses.php\',null,null,true,true)">Manage EA Floating Licenses</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a class="config-bc-link" onclick="loadConfigPage(\'fls-groups.php\',null,null,true,true)">Floating License Groups</a>';
	echo '<img alt="" src="images/separator.png" class="propsprite-separator">';
	echo '<a>'.$sHeader.'</a>';
	}
	echo '<a href="'.$sHelpURL.'" class="config-help-icon"  target="_blank"><img alt=""  src="images/help.png" title="Help"></a>';
	echo '</div>';
	echo '</div>';
	}
	function WriteCollapsibleHeading($sText)
	{
	echo '<img alt="" class="collapsed-icon" src="images/spriteplaceholder.png" onclick="collapse(this)"><div class="collapsible-heading collapsed" onclick="collapse(this)">'.$sText.'</div>';
	}
	function GetBindings($sPCS_URL, $sPostBody, &$sError, $aDataPath)
	{
	$aBindings = GetPostResults($sPCS_URL.'/config/getsbpibindings/', $sPostBody, $sError,  $aDataPath);
	if (is_array($aBindings))
	{
	if (array_key_exists('bindingid',$aBindings))
	{
	$aNewBindingArray[] = $aBindings;
	$aBindings = $aNewBindingArray;
	}
	}
	return $aBindings;
	}
	function GetArrayParameterAsXML($a, $sParameterName, $sDefault='')
	{
	$sReturn = $sDefault;
	if (array_key_exists($sParameterName, $a))
	{
	if (isset($a[$sParameterName]))
	{
	$sReturn = $a[$sParameterName];
	$sReturn = htmlentities($sReturn , ENT_XML1);
	}
	}
	return $sReturn;
	}
	function EncryptDecrypt($sInput, $bIsEncrypt)
	{
	$key = ['t', 'e', 'P', 'i', 'W', '6', 'S', 'h', 'q', 'H', '7', '3', 'C', 'G', 'h', 'o'];
	$sOutput = '';
	if ( $bIsEncrypt === false )
	{
	$i = 0;
	$a = explode(',', $sInput);
	foreach ($a as $s)
	{
	$iChr = hexdec('0x' . $s);
	$sOutput .= chr($iChr ^ ord($key[$i % count($key)]));
	$i++;
	}
	}
	else
	{
	for ($i = 0; $i < strlen($sInput); $i++)
	{
	$iChr = (ord($sInput[$i]) ^ ord($key[$i % count($key)]));
	$sOutput .= str_pad(dechex($iChr), 4, '0', STR_PAD_LEFT);
	if ($i < strlen($sInput) - 1)
	$sOutput .= ',';
	}
	}
	return $sOutput;
	}
	function GetFriendlyDBType(&$sDBName)
	{
	if(isset($sDBName))
	{
	if($sDBName == 'FIREBIRD')
	$sDBName = '<div>Firebird</div>';
	else if($sDBName == 'ORACLE')
	$sDBName = '<div>Oracle</div>';
	else if($sDBName == 'sqlserver1')
	$sDBName = '<div title="SQL Server (old) refers to the SQL Server driver which is included with Windows by default. More recent SQL Server drivers have added TLS 1.2 support.">SQL Server (old)</div>';
	else if(($sDBName == 'SQLSVR') || ($sDBName == 'sqlserver2'))
	$sDBName = '<div title="SQL Server driver which includes TLS 1.2 support">SQL Server</div>';
	else if($sDBName == 'MYSQL')
	$sDBName = '<div>MySQL</div>';
	else if($sDBName == 'POSTGRES')
	$sDBName = '<div>Postgres</div>';
	}
	else
	{
	$sDBName = '';
	}
	}
	function GetFriendlyEdition(&$sEdition)
	{
	if(isset($sEdition))
	{
	$sEdition = mb_strtolower($sEdition);
	if ($sEdition == 'free')
	$sEdition = 'Free';
	else if ($sEdition == 'express')
	$sEdition = 'Express';
	else if ($sEdition == 'team')
	$sEdition = 'Team Server';
	else if ($sEdition == 'enterprise')
	$sEdition = 'Enterprise Server';
	}
	else
	{
	$sEdition = '';
	}
	}
	function IsMobileBrowser()
	{
	$bReturn = false;
        $sUserAgent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
	if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $sUserAgent ) )
	{
	$bReturn = true;
	}
	else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $sUserAgent ) )
	{
	$bReturn = true;
	}
        return $bReturn;
	}
	function CheckDirectNavigation()
	{
	if ($_SERVER["REQUEST_METHOD"] !== "POST")
	{
	$sErrorMsg = _glt('Direct navigation is not allowed. Click <a href="index.php">here</a> to login');
	setResponseCode(405, $sErrorMsg);
	exit();
	}
	}
	function GetProviderTypeName(&$aSbpiprovider, $aSupportedProviders)
	{
	$sProviderName = '';
	foreach ($aSupportedProviders as $aSupportedProvider)
	{
	if ($aSupportedProvider[1] === $aSbpiprovider['typekey'])
	{
	$aSbpiprovider['typename'] = $aSupportedProvider[0];
	}
	}
	return;
	}
	function WriteLicenseRequestForm($sCompany = '', $sEmail = '', $sRenewDate = '', $sKey = '')
	{
	$sSubmitAction = 'createlicenserequest';
	echo '<form id="config-license-req-form" role="form" onsubmit="onFormSubmit(event, \'#config-license-req-form\', \''.$sSubmitAction.'\')">';
	echo '<div class="config-section-grey">';
	echo '<div class="config-line">';
	WriteLabel('Company Name<span class="field-label-required">&nbsp;*</span>');
	WriteTextField($sCompany,'','textfield-large','name="company" title="Enter the name of your Company" required');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Email Address<span class="field-label-required">&nbsp;*</span>');
	WriteTextField($sEmail,'','textfield-large','name="email" title="Enter your email address" required');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Installation ID<span class="field-label-required">&nbsp;*</span>');
	WriteTextField('','','textfield-large','name="ponumber" title="Enter the Installation ID which was provided by the Sparx Sales team when purchasing the Pro Cloud Server License." required');
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Start Date');
	WriteTextField('','config-lic-req-startdate','','name="startdate" value="'.$sRenewDate.'" placeholder="yyyy-mm-dd" onblur="OnDateFieldLostFocus(\'config-lic-req-start-date\')" title="(Optional) Enter a start date for the license."');
	echo '<div style="display: none;"><img id="config-lic-req-startdate-img" class="ss-calendar-icon" alt="" title="Choose date from calendar" src="images/spriteplaceholder.png"></div>';
	echo '</div>';
	echo '<div class="config-line">';
	WriteLabel('Comments');
	WriteTextArea('','config-lic-req-comment','','name="comment" title="(Optional) Provide additional information regarding the request."');
	echo '</div>';
	echo '<input name="key" hidden value="' . $sKey . '">';
	WriteButton('Create License Request','','button','');
	echo '</div>';
	echo '</form>';
	}
	function ConvertToChildArrayItem($sKey, &$aArray)
	{
	if (
	(is_array($aArray)) &&
	(array_key_exists($sKey, $aArray))
	)
	{
	$aArray = [$aArray];
	}
	if (!is_array($aArray))
	{
	$aArray = [];
	}
	}
?>