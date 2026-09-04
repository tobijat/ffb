<?php

declare(strict_types=1);

/**
 * Router for PHP built-in server — mirrors .htaccess pretty URLs for localhost.
 * Usage (from project root): php -S 127.0.0.1:8765 tests/router.php
 */

$root = dirname(__DIR__);
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$uriPath = rawurldecode($uriPath);

// Laravel platform (Phase 2 strangler shell)
if (preg_match('#^/platform(?:/public)?(/.*)?$#', $uriPath, $m)) {
    $platformPublic = $root . DIRECTORY_SEPARATOR . 'platform' . DIRECTORY_SEPARATOR . 'public';
    $subPath = $m[1] ?? '/';
    if ($subPath === '') {
        $subPath = '/';
    }

    $staticFile = $platformPublic . str_replace('/', DIRECTORY_SEPARATOR, $subPath);
    if ($subPath !== '/' && is_file($staticFile)) {
        return false;
    }

    $queryString = !empty($_SERVER['QUERY_STRING']) ? ('?' . $_SERVER['QUERY_STRING']) : '';
    $_SERVER['SCRIPT_FILENAME'] = $platformPublic . DIRECTORY_SEPARATOR . 'index.php';
    $_SERVER['SCRIPT_NAME'] = '/platform/public/index.php';
    $_SERVER['PHP_SELF'] = '/platform/public/index.php';
    $_SERVER['REQUEST_URI'] = $subPath . $queryString;

    chdir($platformPublic);
    require $platformPublic . DIRECTORY_SEPARATOR . 'index.php';
    return true;
}

// Serve existing static files directly.
$staticPath = $root . $uriPath;
if ($uriPath !== '/' && is_file($staticPath)) {
    return false;
}

// Ensure SERVER_NAME is set for config.php BASE_PATH constants.
if (empty($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] ?? '127.0.0.1';
}

if (preg_match('#^/([^/]+)/([^/]+)/([^/]+)\.([^.]+)$#', $uriPath, $m)) {
    $_GET['module'] = $m[1];
    $_GET['class'] = $m[2];
    $_GET['event'] = $m[3];
    $_GET['presenter'] = $m[4];
} elseif (preg_match('#^/([^/]+)/([^/]+)$#', $uriPath, $m)) {
    $_GET['module'] = $m[1];
    $_GET['class'] = $m[2];
} elseif (preg_match('#^/([^/]+)/?$#', $uriPath, $m) && $m[1] !== '') {
    $_GET['module'] = $m[1];
}

// Always force ffb area config for local contract tests (matches localhost RewriteRule).
$_GET['subdomain'] = 'ffb';
$_REQUEST['subdomain'] = 'ffb';
$_REQUEST['module'] = $_GET['module'] ?? ($_REQUEST['module'] ?? null);
$_REQUEST['class'] = $_GET['class'] ?? ($_REQUEST['class'] ?? null);
$_REQUEST['event'] = $_GET['event'] ?? ($_REQUEST['event'] ?? null);
$_REQUEST['presenter'] = $_GET['presenter'] ?? ($_REQUEST['presenter'] ?? null);

chdir($root);
require $root . '/index.php';
