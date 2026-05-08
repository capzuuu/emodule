<?php

if (!function_exists('user_view_layout')) {
    function user_view_layout(array $parts = ['header', 'logo', 'navbar', 'style', 'script', 'mobile_nav'], string $section = 'employee'): void
    {
        $base = __DIR__ . "/../Views/{$section}/includes";

        foreach ($parts as $part) {
            $file = "{$base}/{$part}.php";
            if (file_exists($file)) {
                include $file;
            }
        }
    }
}
