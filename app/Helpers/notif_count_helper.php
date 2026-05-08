<?php

if (!function_exists('get_unread_notif_count')) {
    function get_unread_notif_count(): int
    {
        start_session();

        if (empty($_SESSION['user']['id'])) {
            return 0;
        }

        $model = new \App\Models\Employee\Notifications\Notifications();
        return $model->countUnread($_SESSION['user']['id']);
    }
}
