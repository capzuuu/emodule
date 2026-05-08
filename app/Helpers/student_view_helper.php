<?php
if (!function_exists('student_view_layout')) {
    function student_view_layout(array $parts = ['header', 'style', 'sidebar', 'navbar', 'footer', 'script'])
    {
        $base = __DIR__ . '/../views/student/includes';
        foreach ($parts as $part) {
            $file = "$base/$part.php";
            if (file_exists($file)) include $file;
        }
    }
}
