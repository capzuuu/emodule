<?php

if (!function_exists('is_active')) {
    function is_active(string $path, bool $partial = false): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $base = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
        $currentPath = rtrim(str_replace($base, '', $uri), '/');

        $path = rtrim('/' . ltrim($path, '/'), '/');

        return $partial
            ? (str_starts_with($currentPath, $path) ? 'active' : '')
            : ($currentPath === $path ? 'active' : '');
    }
}
