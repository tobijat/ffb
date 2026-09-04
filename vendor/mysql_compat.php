<?php
/**
 * Compatibility shim: maps removed mysql_* functions to mysqli_* equivalents.
 * Include this once before any legacy code that uses the old mysql extension.
 */

if (!function_exists('mysql_connect')) {

    $GLOBALS['__mysql_compat_link'] = null;

    function mysql_connect($server = null, $username = null, $password = null) {
        $link = @mysqli_connect($server, $username, $password);
        if ($link) {
            $GLOBALS['__mysql_compat_link'] = $link;
        }
        return $link ?: false;
    }

    function mysql_pconnect($server = null, $username = null, $password = null) {
        return mysql_connect($server, $username, $password);
    }

    function mysql_select_db($database_name, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return @mysqli_select_db($link, $database_name);
    }

    function mysql_query($query, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return @mysqli_query($link, $query);
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
        if (!$result) return false;
        return @mysqli_fetch_array($result, $result_type);
    }

    function mysql_fetch_assoc($result) {
        if (!$result) return false;
        return @mysqli_fetch_assoc($result);
    }

    function mysql_fetch_row($result) {
        if (!$result) return false;
        return @mysqli_fetch_row($result);
    }

    function mysql_fetch_object($result) {
        if (!$result) return false;
        return @mysqli_fetch_object($result);
    }

    function mysql_num_rows($result) {
        if (!$result) return 0;
        return @mysqli_num_rows($result);
    }

    function mysql_affected_rows($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return @mysqli_affected_rows($link);
    }

    function mysql_insert_id($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return @mysqli_insert_id($link);
    }

    function mysql_real_escape_string($string, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return @mysqli_real_escape_string($link, $string);
    }

    function mysql_escape_string($string) {
        return mysql_real_escape_string($string);
    }

    function mysql_free_result($result) {
        if ($result instanceof mysqli_result) {
            @mysqli_free_result($result);
            return true;
        }
        return false;
    }

    function mysql_close($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        if ($link instanceof mysqli) {
            return @mysqli_close($link);
        }
        return false;
    }

    function mysql_error($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        if ($link instanceof mysqli) {
            return @mysqli_error($link);
        }
        return '';
    }

    function mysql_errno($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        if ($link instanceof mysqli) {
            return @mysqli_errno($link);
        }
        return 0;
    }

    function mysql_result($result, $row, $field = 0) {
        if (!$result) return false;
        mysqli_data_seek($result, $row);
        $r = mysqli_fetch_array($result);
        return $r[$field] ?? false;
    }

    function mysql_set_charset($charset, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return @mysqli_set_charset($link, $charset);
    }

    if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', MYSQLI_ASSOC);
    if (!defined('MYSQL_NUM'))   define('MYSQL_NUM',   MYSQLI_NUM);
    if (!defined('MYSQL_BOTH'))  define('MYSQL_BOTH',  MYSQLI_BOTH);
}
