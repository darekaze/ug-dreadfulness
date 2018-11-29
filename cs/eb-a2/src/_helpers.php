<?php

if (!function_exists('_is_valid')) {
    function _is_valid($var) {
        if(empty($var) || is_null($var) || FALSE === $var)
            return FALSE;
        return TRUE;
    }
}

if (!function_exists('_log_die')) {
    function _log_die($msg) {
        error_log($msg);
        die($msg);
    }
}

if (!function_exists('escape')) {
    function escape($html) {
        return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }
}
