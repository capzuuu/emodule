<?php

use App\Controllers\Errors\ErrorsController;

if (!function_exists('check_maintenance_mode')) {
    function check_maintenance_mode(): void
    {
        if (isset($_ENV['MAINTENANCE_MODE']) && $_ENV['MAINTENANCE_MODE'] === 'on') {
            $controller = new ErrorsController();
            $controller->maintenance();
            exit;
        }
    }
}
