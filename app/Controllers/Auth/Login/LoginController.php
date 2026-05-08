<?php

namespace App\Controllers\Auth\Login;

use function set_flash;
use App\Core\Controller;
use App\Models\Auth\Login\Login;

class LoginController extends Controller
{
    private $model;

    public function __construct()
    {
        remove_cache();
        $this->model = new Login();
    }

    public function index()
    {
        start_session();

        // Redirect if already logged in
        if (!empty($_SESSION['user'])) {
            switch ($_SESSION['user']['role']) {
                case 'admin':
                    redirect('/admin/dashboard');
                    break;
                case 'teacher':
                    redirect('/teacher/dashboard');
                    break;
                case 'student':
                    redirect('/student/dashboard');
                    break;
            }
            exit;
        }

        $this->view("auth/login", [
            'pageTitle'    => 'Log In — E-Module LMS',
            'flashError'   => get_flash('error'),
            'flashSuccess' => get_flash('success'),
        ]);
    }

    public function authenticate()
    {
        start_session();
        $this->checkCSRF();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/auth/login');
            exit;
        }

        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $fail = function (string $message, int $status = 400) use ($isAjax) {
            if ($isAjax) {
                return json_response(['success' => false, 'message' => $message], $status);
            }
            set_flash('error', $message);
            redirect('/auth/login');
            exit;
        };

        // Validation
        if (!$email || empty($password)) {
            return $fail('Email and password are required.', 422);
        }

        if (strlen($password) > 72) {
            return $fail('Invalid email or password.', 401);
        }

        // IP-based rate limiting (blocks automated attacks across sessions/browsers)
        $ip          = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ipKey       = 'ip_attempts_' . md5($ip);
        $ipWindowKey = 'ip_attempts_time_' . md5($ip);
        $ipMax       = 10;
        $ipWindow    = 300; // 5 minutes

        if (!empty($_SESSION[$ipWindowKey]) && (time() - $_SESSION[$ipWindowKey]) > $ipWindow) {
            unset($_SESSION[$ipKey], $_SESSION[$ipWindowKey]);
        }

        $_SESSION[$ipKey] = ($_SESSION[$ipKey] ?? 0) + 1;
        if (empty($_SESSION[$ipWindowKey])) {
            $_SESSION[$ipWindowKey] = time();
        }

        if ($_SESSION[$ipKey] > $ipMax) {
            return $fail('Too many login attempts. Please try again later.', 429);
        }

        $maxAttempts = 5;
        $lockedMsg   = 'Your account is locked due to too many unsuccessful attempts. Please contact the administrator for help.';
        $genericMsg  = 'Invalid email or password.';

        // Attempt tracking keyed per email
        $attemptKey  = 'login_attempts_' . md5($email);

        $user = $this->model->getByEmail($email);

        // Timing attack mitigation — always run a hash operation even if user not found
        if (!$user) {
            password_verify($password, '$2y$10$dummyhashfortimingnormalisation000000000000000000000000');
            return $fail($genericMsg, 401);
        }

        // is_active check — LMS users table may not have this column, default to active
        if (array_key_exists('is_active', $user) && (int)$user['is_active'] !== 1) {
            return $isAjax
                ? json_response(['success' => false, 'message' => $lockedMsg, 'locked' => true], 403)
                : $fail($lockedMsg, 403);
        }

        $storedHash = $user['password_hash'] ?? $user['password'] ?? '';
        if (!password_verify($password, $storedHash)) {
            if ($user['role'] === 'admin') {
                return $fail($genericMsg, 401);
            }

            $_SESSION[$attemptKey] = ($_SESSION[$attemptKey] ?? 0) + 1;
            $remaining = $maxAttempts - $_SESSION[$attemptKey];
            if ($remaining <= 0) {
                unset($_SESSION[$attemptKey]);
                return $fail($lockedMsg, 403);
            }
            return $fail($genericMsg . ' ' . $remaining . ' attempt(s) remaining.', 401);
        }

        // Auth success — clear all tracking
        unset($_SESSION[$attemptKey], $_SESSION[$ipKey], $_SESSION[$ipWindowKey]);
        session_regenerate_id(true); // prevent session fixation

        $sessionData = [
            'id'        => (int) $user['id'],
            'email'     => $user['email'],
            'full_name' => $user['full_name'] ?? $user['name'] ?? '',
            'role'      => $user['role'],
        ];

        $_SESSION['user']          = $sessionData;
        $_SESSION['last_activity'] = time();

        switch ($user['role']) {
            case 'admin':
                $_SESSION['admin'] = $sessionData;
                $redirectUrl = '/admin/dashboard';
                break;
            case 'teacher':
                $redirectUrl = '/teacher/dashboard';
                break;
            case 'student':
                $redirectUrl = '/student/dashboard';
                break;
            default:
                session_unset();
                session_destroy();
                return $fail('Unauthorized role.', 403);
        }

        if ($isAjax) {
            return json_response([
                'success'  => true,
                'message'  => 'Login successful. Redirecting...',
                'redirect' => baseurl($redirectUrl)
            ]);
        }

        redirect($redirectUrl);
        exit;
    }

    public function google()
    {
        json_response(['success' => false, 'message' => 'Google sign-in is not available.'], 501);
    }

    public function heartbeat()
    {
        start_session();

        if (empty($_SESSION['user'])) {
            json_response(['alive' => false], 401);
            return;
        }

        $_SESSION['last_activity'] = time();
        json_response(['alive' => true]);
    }

    public function logout()
    {
        start_session(); // ensure session is active

        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        redirect('/');
        exit;
    }
}
