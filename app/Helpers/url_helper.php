<?php
function baseurl(string $path = ''): string
{
    $host = $_SERVER['HTTP_HOST'];
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    // Detect the directory of index.php (in /public) and remove '/public'
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $baseDir = preg_replace('#/public$#', '', rtrim($scriptDir, '/'));

    // Build full base URL without trailing slash
    $url = $protocol . '://' . $host . $baseDir;

    // Append path if provided
    return $path ? $url . '/' . ltrim($path, '/') : $url;
}
