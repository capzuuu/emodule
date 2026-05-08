<?php
if (!function_exists('remove_cache')) {
    function remove_cache()
    {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}
