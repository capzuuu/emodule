<?php
if (!function_exists('start_session')) {
    function start_session()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Set session cookie to last 7 days
            session_set_cookie_params([
                'lifetime' => 60 * 60 * 24 * 7, // 7 days
                'path'     => '/',
                'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // true if HTTPS
                'httponly' => true,
                'samesite' => 'Lax' // prevent CSRF issues
            ]);

            session_start();
        }
    }
}
