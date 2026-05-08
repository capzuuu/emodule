<?php
if (!function_exists('set_flash')) {
    function set_flash($key, $message)
    {
        start_session(); // ensure session exists
        $_SESSION['flash'][$key] = $message;
    }
}

if (!function_exists('get_flash')) {
    function get_flash($key)
    {
        start_session(); // ensure session exists
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }

        return null;
    }
}
