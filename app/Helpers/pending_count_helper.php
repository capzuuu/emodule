<?php

if (!function_exists('get_pending_count')) {
    function get_pending_count(): int
    {
        start_session(); // ensure session is started

        if (empty($_SESSION['user']['id'])) {
            return 0;
        }

        $model = new \App\Models\Employee\Documents\PendingDocuments();
        return $model->countMyPendingOnly($_SESSION['user']['id']);
    }
}
