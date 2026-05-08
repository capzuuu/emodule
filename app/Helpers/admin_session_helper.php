<?php
if (!function_exists('admin_session')) {
    function admin_session()
    {
        start_session();

        if (!empty($_SESSION['admin'])) {
            redirect('/admin/dashboard');
            exit;
        }
    }
}
