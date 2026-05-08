<?php
function loadConfig()
{
    $envFile = __DIR__ . '/../../.env';

    if (!file_exists($envFile)) {
        throw new Exception(".env file not found at {$envFile}");
    }

    // Read .env line by line
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        [$name, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");

        $_ENV[$name] = $value;
        putenv("$name=$value");
    }

    // Detect protocol (http/https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

    // Detect host (localhost or IP)
    $host = $_SERVER['HTTP_HOST'];

    // Use .env APP_URL if you want fixed value, else auto-detect
    $appPath = $_ENV['APP_URL'] ?? "/";

    define('APPROOT', dirname(dirname(__FILE__)));
    define('URLROOT', rtrim($protocol . $host, '/'));
    define('SITENAME', $_ENV['APP_NAME']);
}
