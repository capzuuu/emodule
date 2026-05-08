<?php
if (!function_exists('auth_view_layout')) {

    function auth_view_layout(array $parts = ['header', 'navbar', 'sidebar', 'footer', 'script', 'logo', 'loader', 'style'], string $section = 'auth')
    {

        $base = __DIR__ . "/../views/{$section}/includes";

        foreach ($parts as $part) {
            $file = "$base/$part.php";
            if (file_exists($file)) {
                include $file;
            }
        }
    }
}
