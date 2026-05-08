<?php

use App\Models\Admin\ActivityLogs\ActivityLogs;

if (!function_exists('activity_log')) {

    /**
     * Write system activity log
     *
     * @param string $action        Action performed (LOGIN, CREATE, SIGN, etc.)
     * @param string|null $module   Module name (AUTH, DOCUMENTS, USERS, etc.)
     * @param int|null $referenceId Related record ID
     * @param array|null $metadata  Extra JSON data
     */
    function activity_log(string $action, ?string $module = null, ?int $referenceId = null, ?array $metadata = null)
    {
        start_session(); // ensure session exists

        if (empty($_SESSION['user']['id'])) {
            return; // do not log if no authenticated user
        }

        $logs = new ActivityLogs();

        $logs->log(
            $_SESSION['user']['id'],
            $action,
            $module,
            $referenceId,
            $metadata
        );
    }
}
