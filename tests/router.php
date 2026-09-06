<?php

declare(strict_types=1);

/**
 * Router for PHP built-in server — mirrors .htaccess pretty URLs for localhost.
 * Usage (from project root): php -S 127.0.0.1:8765 tests/router.php
 */

$root = dirname(__DIR__);
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$uriPath = rawurldecode($uriPath);
$queryString = ! empty($_SERVER['QUERY_STRING']) ? ('?'.$_SERVER['QUERY_STRING']) : '';

/**
 * Legacy player HTML → platform (mirrors .htaccess). XML stays on index.php.
 */
$redirectTo = null;
$isXml = str_ends_with(strtolower($uriPath), '.xml');

if (! $isXml) {
    if ($uriPath === '/' || $uriPath === '') {
        $redirectTo = '/platform/public/';
    } elseif (preg_match('#^/administration/news/?$#', $uriPath)) {
        $redirectTo = '/platform/public/admin/news';
    } elseif (preg_match('#^/users/?$#', $uriPath) || preg_match('#^/users/login(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/';
    } elseif (preg_match('#^/users/logout(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/logout';
    } elseif (preg_match('#^/users/registration/activate-email(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/registration/activate-email'.$queryString;
    } elseif (preg_match('#^/users/registration/activate(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/registration/activate'.$queryString;
    } elseif (preg_match('#^/users/registration(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/registration'.$queryString;
    } elseif (preg_match('#^/users/accountDetails(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/profile';
    } elseif (preg_match('#^/users/account(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/profile?tab=account';
    } elseif (preg_match('#^/users/help(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/help';
    } elseif (preg_match('#^/users/reference(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/reference';
    } elseif (preg_match('#^/users/mailservice/cancel(?:/.*)?$#', $uriPath)) {
        $redirectTo = '/platform/public/mailservice/cancel'.$queryString;
    } elseif (preg_match('#^/ffb/?$#', $uriPath)) {
        $redirectTo = '/platform/public/';
    } elseif (preg_match('#^/ffb/(lineup|myteam|bestteam|userscore)(?:/.*)?$#', $uriPath, $m)) {
        $redirectTo = '/platform/public/'.$m[1];
    } elseif (preg_match('#^/ffb/#', $uriPath)) {
        $redirectTo = '/platform/public/';
    }
}

if ($redirectTo !== null) {
    header('Location: '.$redirectTo, true, 301);
    return true;
}

// PDFs moved into Laravel public/
if (preg_match('#^/resource/(Registrierung|EM2016Gewinnspiel|WM2014Gewinnspiel)\.pdf$#', $uriPath, $m)) {
    header('Location: /platform/public/resource/'.$m[1].'.pdf', true, 301);
    return true;
}

// Laravel platform (Phase 2 strangler shell)
if (preg_match('#^/platform(?:/public)?(/.*)?$#', $uriPath, $m)) {
    $platformPublic = $root.DIRECTORY_SEPARATOR.'platform'.DIRECTORY_SEPARATOR.'public';
    $subPath = $m[1] ?? '/';
    if ($subPath === '') {
        $subPath = '/';
    }

    $staticFile = $platformPublic.str_replace('/', DIRECTORY_SEPARATOR, $subPath);
    if ($subPath !== '/' && is_file($staticFile)) {
        $mime = match (strtolower(pathinfo($staticFile, PATHINFO_EXTENSION))) {
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'map' => 'application/json',
            default => 'application/octet-stream',
        };
        header('Content-Type: '.$mime);
        header('Content-Length: '.(string) filesize($staticFile));
        readfile($staticFile);

        return true;
    }

    $_SERVER['SCRIPT_FILENAME'] = $platformPublic.DIRECTORY_SEPARATOR.'index.php';
    $_SERVER['SCRIPT_NAME'] = '/platform/public/index.php';
    $_SERVER['PHP_SELF'] = '/platform/public/index.php';
    $_SERVER['REQUEST_URI'] = $subPath.$queryString;

    chdir($platformPublic);
    require $platformPublic.DIRECTORY_SEPARATOR.'index.php';

    return true;
}

// Serve existing static files directly.
$staticPath = $root.$uriPath;
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
require $root.'/index.php';
