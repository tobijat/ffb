<?php

/**
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.3
 *
 * Secrets come from .env (see .env.example). Do not put credentials in this file.
 */

// PHP 8: log errors; do not print them into HTML/XML responses
$ffbLogDir = dirname(__FILE__) . '/logs';
if (!is_dir($ffbLogDir)) {
    @mkdir($ffbLogDir, 0755, true);
}
ini_set('log_errors', '1');
ini_set('error_log', $ffbLogDir . '/php_errors.log');
ini_set('display_errors', '0');
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/classes/FFB_Env.php';
FFB_Env::load(dirname(__FILE__) . '/.env');

//Root-Pfad
define('BASE_PATH','http://'.$_SERVER['SERVER_NAME'].'/');
//Root-Pfad für FFB
define('FFB_BASE_PATH','http://'.$_SERVER['SERVER_NAME'].'/');
//Root-Pfad für PICTORY
define('PIC_BASE_PATH','http://'.$_SERVER['SERVER_NAME'].'/');

//Folder der Klassen
define('FFB_CLASS_PATH','classes/');

//Folder der FFB Bilder
define('FFB_IMAGE_PATH','images/ffb/');
//Folder der PICTORY Bilder
define('PIC_IMAGE_PATH','images/pictory/');
//Folder der ADMIN Bilder
define('ADM_IMAGE_PATH','images/admin/');

//Folder der allgemeinen Includes/Css
define('INCLUDE_PATH','include/');
//Folder der ADMIN Includes/Css
define('ADM_INCLUDE_PATH','include/admin/');
//Folder der PICTORY Includes/Css
define('PIC_INCLUDE_PATH','include/pictory/');

//Folder für allgemeine JS
define('SCRIPT_PATH','script/');
//Folder für die ADMIN JS
define('ADM_SCRIPT_PATH','script/admin/');
//Folder für die PICTORY JS
define('PIC_SCRIPT_PATH','script/pictory/');

//Folder für allgemeine VIEWER-Files
define('VIEWER_PATH','viewer/');
//Folder für die FFB VIEWER
define('FFB_VIEWER_PATH','viewer/ffb/');
//Folder für die PICTORY VIEWER
define('PIC_VIEWER_PATH','viewer/pictory/');
//Folder für die PICTORY VIEWER
define('ADM_VIEWER_PATH','viewer/administration/');

//Folder für die MODULE
define('FFB_MODULE_PATH','modules/');

//Subdomains
define('FFB_SUBDOMAIN','ffb');
define('PIC_SUBDOMAIN','pictory');

//Default-Modul: Start-Modul, wird auch aufgerufen, wenn ungültige URL eingegeben wird
define('FFB_DEFAULT_MODULE','welcome');

//Pfad zum Propel-Config-File
define('PROPEL_CONFIG_FILE', 'classes/propel/build/conf/ffb-conf.php');


//Comments
define('DEFAULT_COMMENT_NUMBER', 30);

// Secrets / environment-backed settings
$dbHost = FFB_Env::require('FFB_DB_HOST');
$dbName = FFB_Env::require('FFB_DB_NAME');
$dbUser = FFB_Env::require('FFB_DB_USER');
$dbPassword = FFB_Env::require('FFB_DB_PASSWORD');
$dbCharset = FFB_Env::get('FFB_DB_CHARSET', 'utf8mb4');

define('FFB_RECAPTCHA_PUBLICKEY', FFB_Env::require('FFB_RECAPTCHA_PUBLICKEY'));
define('FFB_RECAPTCHA_PRIVATEKEY', FFB_Env::require('FFB_RECAPTCHA_PRIVATEKEY'));

define('FFB_DATABASE_NAME', $dbName);
define(
    'FFB_DB_LOGIN',
    'mysqli://' . rawurlencode($dbUser) . ':' . rawurlencode($dbPassword) . '@' . $dbHost . '/' . $dbName
);
define('FFB_DB_DSN', 'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=' . $dbCharset);
define('FFB_DB_USER', $dbUser);
define('FFB_DB_PASSWORD', $dbPassword);

//zusätzliche INCLUDE-Paths
set_include_path("classes/propel/build/classes" . PATH_SEPARATOR . get_include_path());

// Local vendor paths (Propel runtime, PEAR core, PEAR DB, PEAR Log)
$vendorBase = dirname(__FILE__) . '/vendor';
set_include_path($vendorBase . PATH_SEPARATOR . get_include_path());
set_include_path($vendorBase . '/pear-core/src' . PATH_SEPARATOR . get_include_path());
set_include_path($vendorBase . '/pear-db' . PATH_SEPARATOR . get_include_path());
set_include_path($vendorBase . '/pear-log' . PATH_SEPARATOR . get_include_path());
set_include_path($vendorBase . '/pear-exception' . PATH_SEPARATOR . get_include_path());
set_include_path($vendorBase . '/pear-xml-util' . PATH_SEPARATOR . get_include_path());
set_include_path($vendorBase . '/pear-xml-serializer' . PATH_SEPARATOR . get_include_path());
set_include_path($vendorBase . '/pear-validate' . PATH_SEPARATOR . get_include_path());

?>
