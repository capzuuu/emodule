<?php

if (!function_exists('auth_role')) {
    function auth_role()
    {
        return $_SESSION['admin']
            ?? $_SESSION['staff']
            ?? $_SESSION['external']
            ?? null;
    }
}
