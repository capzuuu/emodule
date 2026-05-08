<?php

namespace App\Core;

class Controller
{
    public function view($view, $data = [])
    {
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("View '{$view}.php' not found!");
        }

        // ✅ Make controller data available to all included views
        if (!empty($data) && is_array($data)) {
            extract($data, EXTR_SKIP);
            // Make pageTitle globally accessible for includes called inside functions
            if (isset($data['pageTitle'])) {
                $GLOBALS['pageTitle'] = $data['pageTitle'];
            }
        }

        // ✅ Load the actual page
        require $viewPath;
    }

    protected function checkCSRF()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // 1️⃣ first look at normal POST field
            $token = $_POST['_csrf'] ?? '';

            // 2️⃣ if empty, try JSON body
            if ($token === '') {
                $raw = file_get_contents('php://input');
                $json = json_decode($raw, true);
                if (is_array($json) && isset($json['_csrf'])) {
                    $token = $json['_csrf'];
                }
            }

            if (empty($token) || !isset($_SESSION['_csrf']) || $token !== $_SESSION['_csrf']) {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid CSRF token'
                ]);
                exit;
            }
        }
    }


    protected function generateCSRF()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }
}
