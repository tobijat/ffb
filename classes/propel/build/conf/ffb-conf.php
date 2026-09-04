<?php
// Propel runtime config — credentials come from environment / .env (loaded in config.php).
// Safe to commit: no secrets in this file.
//
// Note: many call sites still use Propel::getConnection(FFB_DATABASE_NAME).
// Keep the datasource id equal to FFB_DB_NAME.

$dbHost = getenv('FFB_DB_HOST') ?: '127.0.0.1';
$dbName = getenv('FFB_DB_NAME') ?: 'd00817fb';
$dbUser = getenv('FFB_DB_USER') ?: '';
$dbPassword = getenv('FFB_DB_PASSWORD') !== false ? getenv('FFB_DB_PASSWORD') : '';
$dbCharset = getenv('FFB_DB_CHARSET') ?: 'utf8mb4';

$conf = array(
  'datasources' => array(
    $dbName => array(
      'adapter' => 'mysql',
      'connection' => array(
        'dsn' => 'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=' . $dbCharset,
        'user' => $dbUser,
        'password' => $dbPassword,
        'settings' => array(
          'charset' => array('value' => $dbCharset),
        ),
      ),
    ),
    'default' => $dbName,
  ),
  'log' => array(
    'type' => 'file',
    'name' => './propel.log',
    'ident' => 'propel-' . $dbName,
    'level' => '7',
    'conf' => '',
  ),
  'generator_version' => '1.5.6',
);
$conf['classmap'] = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap-ffb-conf.php');
return $conf;
