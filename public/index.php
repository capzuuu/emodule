<?php

declare(strict_types=1);

// ─────────────────────────────────────────────
// Project Root & Bootstrap
// ─────────────────────────────────────────────
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/bootstrap.php';

// ─────────────────────────────────────────────
// Session Initialization
// ─────────────────────────────────────────────
start_session();

// ─────────────────────────────────────────────
// CSP Nonce (per request)
// ─────────────────────────────────────────────
$nonce = csp_nonce();

// ─────────────────────────────────────────────
// Security Headers
// ─────────────────────────────────────────────
send_security_headers($nonce);

// ─────────────────────────────────────────────
// Router Setup
// ─────────────────────────────────────────────
use App\Core\Router;

$router = new Router();

require_once BASE_PATH . '/app/routes/web.php';

$router->dispatch($_GET['url'] ?? '/');


// ─────────────────────────────────────────────
// Functions
// ─────────────────────────────────────────────

/**
 * Send all security-related headers
 */
function send_security_headers(string $nonce): void
{
    header(build_csp($nonce));

    // Cross-Origin Policies
    header('Cross-Origin-Opener-Policy: same-origin');
    header('Cross-Origin-Resource-Policy: cross-origin');

    // NOTE:
    // Using 'unsafe-none' because external CDN resources
    // are not CORS-enabled. Switch to 'require-corp' if they are.
    header('Cross-Origin-Embedder-Policy: unsafe-none');
}

/**
 * Build CSP header string
 */
function build_csp(string $nonce): string
{
    $policy = [
        "default-src 'self'",
        "script-src 'self' 'nonce-{$nonce}'",
        "worker-src blob:",
        "style-src 'self' 'unsafe-inline'",
        "font-src 'self' data:",
        "img-src 'self' data: blob:",
        "connect-src 'self'",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self'",
        "frame-ancestors 'none'",
        "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com",
    ];

    return 'Content-Security-Policy: ' . implode('; ', $policy) . ';';
}
