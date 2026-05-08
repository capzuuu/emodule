<?php
// Composer autoloader (FPDI, FPDF, etc.)
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}
require_once __DIR__ . '/Core/autoload.php';

// Load .env + constants
require_once __DIR__ . '/config/config.php';
loadConfig();

// Auto-load all helper files
foreach (glob(__DIR__ . '/Helpers/*.php') as $file) {
    require_once $file;
}
check_maintenance_mode();
