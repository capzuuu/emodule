<?php
if (!function_exists('check_auth')) {
    function check_auth()
    {
        start_session();

        if (empty($_SESSION['user'])) {
            redirect('/');
            exit;
        }
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool
    {
        return !empty($_SESSION['user']);
    }
}

if (!function_exists('checkAdmin')) {
    function checkAdmin()
    {
        start_session();

        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
            redirect('/admin/dashboard');
            exit;
        }
    }
}
