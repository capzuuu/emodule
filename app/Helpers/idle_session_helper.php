<?php
if (!function_exists('check_idle_session')) {
    function check_idle_session($timeout = 600) // 5 minutes
    {
        start_session();

        // Only check if logged in
        if (empty($_SESSION['user'])) {
            return;
        }

        if (!isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
            return;
        }

        if ((time() - $_SESSION['last_activity']) > $timeout) {

            $flashMessage = 'You have been logged out due to inactivity.';

            session_unset();
            session_destroy();
            session_write_close();

            session_start();
            session_regenerate_id(true);
            $_SESSION['flash']['error'] = $flashMessage;

            redirect('/');
            exit;
        }

        $_SESSION['last_activity'] = time();
    }
}
