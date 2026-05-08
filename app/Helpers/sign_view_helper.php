<?php

if (!function_exists('sign_view_layout')) {
    function sign_view_layout(array $parts = ['header', 'script']): void
    {
        $base = __DIR__ . '/../Views/sign/includes';

        foreach ($parts as $part) {
            $file = "{$base}/{$part}.php";
            if (file_exists($file)) {
                include $file;
            }
        }
    }
}
