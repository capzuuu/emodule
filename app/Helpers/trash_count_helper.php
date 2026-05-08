<?php

if (!function_exists('get_trash_count')) {
    function get_trash_count(): int
    {
        start_session(); // ensure session is started

        if (empty($_SESSION['user']['id'])) {
            return 0;
        }

        $model = new \App\Models\Employee\Documents\TrashDocuments();
        return $model->countMyTrash($_SESSION['user']['id']);
    }
}
