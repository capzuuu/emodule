<?php
if (!function_exists('admin_view_layout')) {

    function admin_view_layout(array $parts = ['header', 'navbar', 'sidebar', 'footer', 'script', 'logo', 'loader', 'style'], string $section = 'admin')
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
