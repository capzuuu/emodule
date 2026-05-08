<?php
if (!function_exists('json_response')) {
    /**
     * Send a JSON response and terminate the script.
     *
     * @param array $data The data to encode as JSON
     * @param int $status HTTP status code
     */
    function json_response(array $data, int $status = 200)
    {
        if (!headers_sent()) {
            header('Content-Type: application/json', true, $status);
        }
        echo json_encode($data);
        exit;
    }
}
