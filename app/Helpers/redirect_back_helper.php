<?php
if (!function_exists('redirect_back')) {
    function redirect_back(): void
    {
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';
        $base = rtrim(baseurl(), '/');

        if ($referrer && str_starts_with($referrer, $base)) {
            header('Location: ' . $referrer);
        } else {
            header('Location: ' . $base); // fallback to home
        }

        exit;
    }
}
