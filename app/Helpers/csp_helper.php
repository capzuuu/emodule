<?php

if (!function_exists('csp_nonce')) {
    /**
     * Returns the CSP nonce for the current request.
     * Generated once per request and stored in $GLOBALS.
     */
    function csp_nonce(): string
    {
        if (empty($GLOBALS['_csp_nonce'])) {
            $GLOBALS['_csp_nonce'] = base64_encode(random_bytes(16));
        }
        return $GLOBALS['_csp_nonce'];
    }
}
