<?php

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        // Ensure path starts with a slash
        $cleanPath = '/' . ltrim($path, '/');

        // Redirect
        header("Location: " . baseurl($cleanPath));
        exit;
    }
}
