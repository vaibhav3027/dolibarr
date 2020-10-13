<?php
/* Copyright (C) 2002-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Xavier Dutoit        <doli@sydesy.com>
 * Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2011 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2005 	   Simon Tosser         <simon@kornog-computing.com>
 * Copyright (C) 2006 	   Andre Cianfarani     <andre.cianfarani@acdeveloppement.net>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2015      Bahfir Abbes         <bafbes@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *	\file       htdocs/filefunc.inc.php
 * 	\ingroup	core
 *  \brief      File that include conf.php file and commons lib like functions.lib.php
 */

if (!defined('DOL_APPLICATION_TITLE')) define('DOL_APPLICATION_TITLE', 'DigitalProspects');
if (!defined('DOL_VERSION')) define('DOL_VERSION', '12.0.3'); // a.b.c-alpha, a.b.c-beta, a.b.c-rcX or a.b.c

if (!defined('EURO')) define('EURO', chr(128));

// Define syslog constants
if (!defined('LOG_DEBUG'))
{
	if (!function_exists("syslog")) {
		// For PHP versions without syslog (like running on Windows OS)
		define('LOG_EMERG', 0);
		define('LOG_ALERT', 1);
		define('LOG_CRIT', 2);
		define('LOG_ERR', 3);
		define('LOG_WARNING', 4);
		define('LOG_NOTICE', 5);
		define('LOG_INFO', 6);
		define('LOG_DEBUG', 7);
	}
}

// End of common declaration part
if (defined('DOL_INC_FOR_VERSION_ERROR')) return;


// Define vars
$conffiletoshowshort = "conf.php";
// Define localization of conf file
// --- Start of part replaced by DigitalProspects packager makepack-DigitalProspects
$conffile = "conf/conf.php";
$conffiletoshow = "htdocs/conf/conf.php";
// For debian/redhat like systems
//$conffile = "/etc/DigitalProspects/conf.php";
//$conffiletoshow = "/etc/DigitalProspects/conf.php";


// Include configuration
// --- End of part replaced by DigitalProspects packager makepack-DigitalProspects


// Include configuration
$result = @include_once $conffile; // Keep @ because with some error reporting this break the redirect done when file not found

if (!$result && !empty($_SERVER["GATEWAY_INTERFACE"]))    // If install not done and we are in a web session
{
    if (!empty($_SERVER["CONTEXT_PREFIX"]))    // CONTEXT_PREFIX and CONTEXT_DOCUMENT_ROOT are not defined on all apache versions
    {
        $path = $_SERVER["CONTEXT_PREFIX"]; // example '/DigitalProspects/' when using an apache alias.
        if (!preg_match('/\/$/', $path)) $path .= '/';
    }
    elseif (preg_match('/index\.php/', $_SERVER['PHP_SELF']))
    {
        // When we ask index.php, we MUST BE SURE that $path is '' at the end. This is required to make install process
        // when using apache alias like '/DigitalProspects/' that point to htdocs.
    	// Note: If calling page was an index.php not into htdocs (ie comm/index.php, ...), then this redirect will fails,
    	// but we don't want to change this because when URL is correct, we must be sure the redirect to install/index.php will be correct.
        $path = '';
    }
    else
    {
        // If what we look is not index.php, we can try to guess location of root. May not work all the time.
    	// There is no real solution, because the only way to know the apache url relative path is to have it into conf file.
    	// If it fails to find correct $path, then only solution is to ask user to enter the correct URL to index.php or install/index.php
        $TDir = explode('/', $_SERVER['PHP_SELF']);
    	$path = '';
    	$i = count($TDir);
    	while ($i--)
    	{
    		if (empty($TDir[$i]) || $TDir[$i] == 'htdocs') break;
            if ($TDir[$i] == 'DigitalProspects') break;
            if (substr($TDir[$i], -4, 4) == '.php') continue;

    		$path .= '../';
    	}
    }

	header("Location: ".$path."install/index.php");
	exit;
}

// Force PHP error_reporting setup (DigitalProspects may report warning without this)
if (!empty($DigitalProspects_strict_mode))
{
	error_reporting(E_ALL | E_STRICT);
}
else
{
	error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_DEPRECATED));
}

// Disable php display errors
if (!empty($DigitalProspects_main_prod)) ini_set('display_errors', 'Off');

// Clean parameters
$DigitalProspects_main_data_root = trim($DigitalProspects_main_data_root);
$DigitalProspects_main_url_root = trim(preg_replace('/\/+$/', '', $DigitalProspects_main_url_root));
$DigitalProspects_main_url_root_alt = (empty($DigitalProspects_main_url_root_alt) ? '' : trim($DigitalProspects_main_url_root_alt));
$DigitalProspects_main_document_root = trim($DigitalProspects_main_document_root);
$DigitalProspects_main_document_root_alt = (empty($DigitalProspects_main_document_root_alt) ? '' : trim($DigitalProspects_main_document_root_alt));

if (empty($DigitalProspects_main_db_port)) $DigitalProspects_main_db_port = 3306; // For compatibility with old configs, if not defined, we take 'mysql' type
if (empty($DigitalProspects_main_db_type)) $DigitalProspects_main_db_type = 'mysqli'; // For compatibility with old configs, if not defined, we take 'mysql' type

// Mysql driver support has been removed in favor of mysqli
if ($DigitalProspects_main_db_type == 'mysql') $DigitalProspects_main_db_type = 'mysqli';
if (empty($DigitalProspects_main_db_prefix)) $DigitalProspects_main_db_prefix = 'llx_';
if (empty($DigitalProspects_main_db_character_set)) $DigitalProspects_main_db_character_set = ($DigitalProspects_main_db_type == 'mysqli' ? 'utf8' : ''); // Old installation
if (empty($DigitalProspects_main_db_collation)) $DigitalProspects_main_db_collation = ($DigitalProspects_main_db_type == 'mysqli' ? 'utf8_unicode_ci' : ''); // Old installation
if (empty($DigitalProspects_main_db_encryption)) $DigitalProspects_main_db_encryption = 0;
if (empty($DigitalProspects_main_db_cryptkey)) $DigitalProspects_main_db_cryptkey = '';
if (empty($DigitalProspects_main_limit_users)) $DigitalProspects_main_limit_users = 0;
if (empty($DigitalProspects_mailing_limit_sendbyweb)) $DigitalProspects_mailing_limit_sendbyweb = 0;
if (empty($DigitalProspects_mailing_limit_sendbycli)) $DigitalProspects_mailing_limit_sendbycli = 0;
if (empty($DigitalProspects_strict_mode)) $DigitalProspects_strict_mode = 0; // For debug in php strict mode

// Security: CSRF protection
// This test check if referrer ($_SERVER['HTTP_REFERER']) is same web site than DigitalProspects ($_SERVER['HTTP_HOST'])
// when we post forms (we allow GET to allow direct link to access a particular page).
// Note about $_SERVER[HTTP_HOST/SERVER_NAME]: http://shiflett.org/blog/2006/mar/server-name-versus-http-host
// See also option $conf->global->MAIN_SECURITY_CSRF_WITH_TOKEN for a stronger CSRF protection.
if (!defined('NOCSRFCHECK') && empty($DigitalProspects_nocsrfcheck))
{
	if (!empty($_SERVER['REQUEST_METHOD']) && !in_array($_SERVER['REQUEST_METHOD'], array('GET', 'HEAD')) && !empty($_SERVER['HTTP_HOST']))
    {
    	$csrfattack = false;
    	if (empty($_SERVER['HTTP_REFERER'])) $csrfattack = true; // An evil browser was used
    	else
    	{
    		$tmpa = parse_url($_SERVER['HTTP_HOST']);
    		$tmpb = parse_url($_SERVER['HTTP_REFERER']);
    		if ((empty($tmpa['host']) ? $tmpa['path'] : $tmpa['host']) != (empty($tmpb['host']) ? $tmpb['path'] : $tmpb['host'])) $csrfattack = true;
    	}
    	if ($csrfattack)
    	{
    		//print 'NOCSRFCHECK='.defined('NOCSRFCHECK').' REQUEST_METHOD='.$_SERVER['REQUEST_METHOD'].' HTTP_HOST='.$_SERVER['HTTP_HOST'].' HTTP_REFERER='.$_SERVER['HTTP_REFERER'];
    		// Note: We can't use dol_escape_htmltag here to escape output because lib functions.lib.ph is not yet loaded.
    		print "Access refused by CSRF protection in main.inc.php. Referer of form (".htmlentities($_SERVER['HTTP_REFERER'], ENT_COMPAT, 'UTF-8').") is outside the server that serve this page (with method = ".htmlentities($_SERVER['REQUEST_METHOD'], ENT_COMPAT, 'UTF-8').").\n";
        	print "If you access your server behind a proxy using url rewriting, you might check that all HTTP headers are propagated (or add the line \$DigitalProspects_nocsrfcheck=1 into your conf.php file to remove this security check).\n";
    		die;
    	}
    }
    // Another test is done later on token if option MAIN_SECURITY_CSRF_WITH_TOKEN is on.
}
if (empty($DigitalProspects_main_db_host))
{
	print '<div class="center">DigitalProspects setup is not yet complete.<br><br>'."\n";
	print '<a href="install/index.php">Click here to finish DigitalProspects install process</a> ...</div>'."\n";
	die;
}
if (empty($DigitalProspects_main_url_root))
{
	print 'Value for parameter \'DigitalProspects_main_url_root\' is not defined in your \'htdocs\conf\conf.php\' file.<br>'."\n";
	print 'You must add this parameter with your full DigitalProspects root Url (Example: http://myvirtualdomain/ or http://mydomain/myDigitalProspectsurl/)'."\n";
	die;
}
if (empty($DigitalProspects_main_data_root))
{
	// Si repertoire documents non defini, on utilise celui par defaut
	$DigitalProspects_main_data_root = str_replace("/htdocs", "", $DigitalProspects_main_document_root);
	$DigitalProspects_main_data_root .= "/documents";
}

// Define some constants
define('DOL_CLASS_PATH', 'class/'); // Filesystem path to class dir (defined only for some code that want to be compatible with old versions without this parameter)
define('DOL_DATA_ROOT', $DigitalProspects_main_data_root); // Filesystem data (documents)
define('DOL_DOCUMENT_ROOT', $DigitalProspects_main_document_root); // Filesystem core php (htdocs)
// Try to autodetect DOL_MAIN_URL_ROOT and DOL_URL_ROOT.
// Note: autodetect works only in case 1, 2, 3 and 4 of phpunit test CoreTest.php. For case 5, 6, only setting value into conf.php will works.
$tmp = '';
$found = 0;
$real_DigitalProspects_main_document_root = str_replace('\\', '/', realpath($DigitalProspects_main_document_root)); // A) Value found into config file, to say where are store htdocs files. Ex: C:/xxx/DigitalProspects, C:/xxx/DigitalProspects/htdocs
if (!empty($_SERVER["DOCUMENT_ROOT"])) {
    $pathroot = $_SERVER["DOCUMENT_ROOT"]; // B) Value reported by web server setup (not defined on CLI mode), to say where is root of web server instance. Ex: C:/xxx/DigitalProspects, C:/xxx/DigitalProspects/htdocs
} else {
    $pathroot = 'NOTDEFINED';
}
$paths = explode('/', str_replace('\\', '/', $_SERVER["SCRIPT_NAME"])); // C) Value reported by web server, to say full path on filesystem of a file. Ex: /DigitalProspects/htdocs/admin/system/phpinfo.php
// Try to detect if $_SERVER["DOCUMENT_ROOT"]+start of $_SERVER["SCRIPT_NAME"] is $DigitalProspects_main_document_root. If yes, relative url to add before dol files is this start part.
$concatpath = '';
foreach ($paths as $tmppath)	// We check to find (B+start of C)=A
{
    if (empty($tmppath)) continue;
    $concatpath .= '/'.$tmppath;
    //if ($tmppath) $concatpath.='/'.$tmppath;
    //print $_SERVER["SCRIPT_NAME"].'-'.$pathroot.'-'.$concatpath.'-'.$real_DigitalProspects_main_document_root.'-'.realpath($pathroot.$concatpath).'<br>';
    if ($real_DigitalProspects_main_document_root == @realpath($pathroot.$concatpath))    // @ avoid warning when safe_mode is on.
    {
        //print "Found relative url = ".$concatpath;
    	$tmp3 = $concatpath;
        $found = 1;
        break;
    }
    //else print "Not found yet for concatpath=".$concatpath."<br>\n";
}
//print "found=".$found." DigitalProspects_main_url_root=".$DigitalProspects_main_url_root."\n";
if (!$found) $tmp = $DigitalProspects_main_url_root; // If autodetect fails (Ie: when using apache alias that point outside default DOCUMENT_ROOT).
else $tmp = 'http'.(((empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != 'on') && (empty($_SERVER["SERVER_PORT"]) || $_SERVER["SERVER_PORT"] != 443)) ? '' : 's').'://'.$_SERVER["SERVER_NAME"].((empty($_SERVER["SERVER_PORT"]) || $_SERVER["SERVER_PORT"] == 80 || $_SERVER["SERVER_PORT"] == 443) ? '' : ':'.$_SERVER["SERVER_PORT"]).($tmp3 ? (preg_match('/^\//', $tmp3) ? '' : '/').$tmp3 : '');
//print "tmp1=".$tmp1." tmp2=".$tmp2." tmp3=".$tmp3." tmp=".$tmp."\n";
if (!empty($DigitalProspects_main_force_https)) $tmp = preg_replace('/^http:/i', 'https:', $tmp);
define('DOL_MAIN_URL_ROOT', $tmp); // URL absolute root (https://sss/DigitalProspects, ...)
$uri = preg_replace('/^http(s?):\/\//i', '', constant('DOL_MAIN_URL_ROOT')); // $uri contains url without http*
$suburi = strstr($uri, '/'); // $suburi contains url without domain:port
if ($suburi == '/') $suburi = ''; // If $suburi is /, it is now ''
define('DOL_URL_ROOT', $suburi); // URL relative root ('', '/DigitalProspects', ...)

//print DOL_MAIN_URL_ROOT.'-'.DOL_URL_ROOT."\n";

// Define prefix MAIN_DB_PREFIX
define('MAIN_DB_PREFIX', $DigitalProspects_main_db_prefix);


/*
 * Define PATH to external libraries
 * To use other version than embeded libraries, define here constant to path. Use '' to use include class path autodetect.
 */
// Path to root libraries
if (!defined('ADODB_PATH')) { define('ADODB_PATH', (!isset($DigitalProspects_lib_ADODB_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/adodbtime/' : (empty($DigitalProspects_lib_ADODB_PATH) ? '' : $DigitalProspects_lib_ADODB_PATH.'/')); }
if (!defined('FPDF_PATH')) { define('FPDF_PATH', (empty($DigitalProspects_lib_FPDF_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/fpdf/' : $DigitalProspects_lib_FPDF_PATH.'/'); }	// Used only for package that can't include tcpdf
if (!defined('TCPDF_PATH')) { define('TCPDF_PATH', (empty($DigitalProspects_lib_TCPDF_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/tecnickcom/tcpdf/' : $DigitalProspects_lib_TCPDF_PATH.'/'); }
if (!defined('FPDI_PATH')) { define('FPDI_PATH', (empty($DigitalProspects_lib_FPDI_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/fpdfi/' : $DigitalProspects_lib_FPDI_PATH.'/'); }
if (!defined('TCPDI_PATH')) { define('TCPDI_PATH', (empty($DigitalProspects_lib_TCPDI_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/tcpdi/' : $DigitalProspects_lib_TCPDI_PATH.'/'); }
if (!defined('NUSOAP_PATH')) { define('NUSOAP_PATH', (!isset($DigitalProspects_lib_NUSOAP_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/nusoap/lib/' : (empty($DigitalProspects_lib_NUSOAP_PATH) ? '' : $DigitalProspects_lib_NUSOAP_PATH.'/')); }
if (!defined('PHPEXCEL_PATH')) { define('PHPEXCEL_PATH', (!isset($DigitalProspects_lib_PHPEXCEL_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/phpoffice/phpexcel/Classes/' : (empty($DigitalProspects_lib_PHPEXCEL_PATH) ? '' : $DigitalProspects_lib_PHPEXCEL_PATH.'/')); }
if (!defined('PHPEXCELNEW_PATH')) { define('PHPEXCELNEW_PATH', (!isset($DigitalProspects_lib_PHPEXCELNEW_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/phpoffice/PhpSpreadsheet/' : (empty($DigitalProspects_lib_PHPEXCELNEW_PATH) ? '' : $DigitalProspects_lib_PHPEXCELNEW_PATH.'/')); }
if (!defined('GEOIP_PATH')) { define('GEOIP_PATH', (!isset($DigitalProspects_lib_GEOIP_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/geoip/' : (empty($DigitalProspects_lib_GEOIP_PATH) ? '' : $DigitalProspects_lib_GEOIP_PATH.'/')); }
if (!defined('ODTPHP_PATH')) { define('ODTPHP_PATH', (!isset($DigitalProspects_lib_ODTPHP_PATH)) ?DOL_DOCUMENT_ROOT.'/includes/odtphp/' : (empty($DigitalProspects_lib_ODTPHP_PATH) ? '' : $DigitalProspects_lib_ODTPHP_PATH.'/')); }
if (!defined('ODTPHP_PATHTOPCLZIP')) { define('ODTPHP_PATHTOPCLZIP', (!isset($DigitalProspects_lib_ODTPHP_PATHTOPCLZIP)) ?DOL_DOCUMENT_ROOT.'/includes/odtphp/zip/pclzip/' : (empty($DigitalProspects_lib_ODTPHP_PATHTOPCLZIP) ? '' : $DigitalProspects_lib_ODTPHP_PATHTOPCLZIP.'/')); }
if (!defined('JS_CKEDITOR')) { define('JS_CKEDITOR', (!isset($DigitalProspects_js_CKEDITOR)) ? '' : (empty($DigitalProspects_js_CKEDITOR) ? '' : $DigitalProspects_js_CKEDITOR.'/')); }
if (!defined('JS_JQUERY')) { define('JS_JQUERY', (!isset($DigitalProspects_js_JQUERY)) ? '' : (empty($DigitalProspects_js_JQUERY) ? '' : $DigitalProspects_js_JQUERY.'/')); }
if (!defined('JS_JQUERY_UI')) { define('JS_JQUERY_UI', (!isset($DigitalProspects_js_JQUERY_UI)) ? '' : (empty($DigitalProspects_js_JQUERY_UI) ? '' : $DigitalProspects_js_JQUERY_UI.'/')); }
if (!defined('JS_JQUERY_FLOT')) { define('JS_JQUERY_FLOT', (!isset($DigitalProspects_js_JQUERY_FLOT)) ? '' : (empty($DigitalProspects_js_JQUERY_FLOT) ? '' : $DigitalProspects_js_JQUERY_FLOT.'/')); }
// Other required path
if (!defined('DOL_DEFAULT_TTF')) { define('DOL_DEFAULT_TTF', (!isset($DigitalProspects_font_DOL_DEFAULT_TTF)) ?DOL_DOCUMENT_ROOT.'/includes/fonts/Aerial.ttf' : (empty($DigitalProspects_font_DOL_DEFAULT_TTF) ? '' : $DigitalProspects_font_DOL_DEFAULT_TTF)); }
if (!defined('DOL_DEFAULT_TTF_BOLD')) { define('DOL_DEFAULT_TTF_BOLD', (!isset($DigitalProspects_font_DOL_DEFAULT_TTF_BOLD)) ?DOL_DOCUMENT_ROOT.'/includes/fonts/AerialBd.ttf' : (empty($DigitalProspects_font_DOL_DEFAULT_TTF_BOLD) ? '' : $DigitalProspects_font_DOL_DEFAULT_TTF_BOLD)); }


/*
 * Include functions
 */

if (!defined('ADODB_DATE_VERSION')) include_once ADODB_PATH.'adodb-time.inc.php';

if (!file_exists(DOL_DOCUMENT_ROOT."/core/lib/functions.lib.php"))
{
	print "Error: DigitalProspects config file content seems to be not correctly defined.<br>\n";
	print "Please run DigitalProspects setup by calling page <b>/install</b>.<br>\n";
	exit;
}


// Included by default
include_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
include_once DOL_DOCUMENT_ROOT.'/core/lib/security.lib.php';
//print memory_get_usage();

// If password is encoded, we decode it
if (preg_match('/crypted:/i', $DigitalProspects_main_db_pass) || !empty($DigitalProspects_main_db_encrypted_pass))
{
	if (preg_match('/crypted:/i', $DigitalProspects_main_db_pass))
	{
		$DigitalProspects_main_db_pass = preg_replace('/crypted:/i', '', $DigitalProspects_main_db_pass);
		$DigitalProspects_main_db_pass = dol_decode($DigitalProspects_main_db_pass);
		$DigitalProspects_main_db_encrypted_pass = $DigitalProspects_main_db_pass; // We need to set this as it is used to know the password was initially crypted
	}
	else $DigitalProspects_main_db_pass = dol_decode($DigitalProspects_main_db_encrypted_pass);
}
