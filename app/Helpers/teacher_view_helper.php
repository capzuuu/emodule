<?php
if (!function_exists('teacher_view_layout')) {
    function teacher_view_layout(array $parts = ['header', 'style', 'sidebar', 'navbar', 'footer', 'script'])
    {
        $base = __DIR__ . '/../views/teacher/includes';
        foreach ($parts as $part) {
            $file = "$base/$part.php";
            if (file_exists($file)) include $file;
        }
    }
}
