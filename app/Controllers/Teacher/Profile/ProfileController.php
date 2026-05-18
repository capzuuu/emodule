<?php

namespace App\Controllers\Teacher\Profile;

use App\Core\Controller;
use App\Models\Teacher\Profile\Profile;

class ProfileController extends Controller
{
    private Profile $model;

    public function __construct()
    {
        start_session();
        check_auth();
        check_idle_session();
        remove_cache();

        if ($_SESSION['user']['role'] !== 'teacher') {
            redirect('/');
            exit;
        }

        $this->model = new Profile();
    }

    public function index()
    {
        $user = $this->model->getById((int) $_SESSION['user']['id']);

        $this->view('teacher/profile/profile', [
            'pageTitle'    => 'My Profile',
            'pageSubtitle' => 'Manage your account information.',
            'userName'     => $_SESSION['user']['full_name'],
            'user'         => $user,
        ]);
    }

    public function serveProfilePicture()
    {
        $id   = (int) $_SESSION['user']['id'];
        $user = $this->model->getById($id);

        if (empty($user['profile_picture'])) {
            http_response_code(404); exit;
        }

        $filePath = BASE_PATH . '/' . ltrim($user['profile_picture'], '/');

        if (!file_exists($filePath)) {
            http_response_code(404); exit;
        }

        $ext  = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = match($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'gif'         => 'image/gif',
            'webp'        => 'image/webp',
            default       => 'application/octet-stream',
        };

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, max-age=86400');
        header('X-Content-Type-Options: nosniff');
        readfile($filePath);
        exit;
    }

    public function uploadPicture()
    {
        $id = (int) $_SESSION['user']['id'];

        if (empty($_FILES['profile_picture']['tmp_name'])) {
            json_response(['status' => 'error', 'message' => 'No file uploaded.']);
            return;
        }

        $file     = $_FILES['profile_picture'];
        $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize  = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowed)) {
            json_response(['status' => 'error', 'message' => 'Only JPG, PNG, GIF, WEBP allowed.']);
            return;
        }

        if ($file['size'] > $maxSize) {
            json_response(['status' => 'error', 'message' => 'File must be under 2MB.']);
            return;
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'teacher_' . $id . '_' . time() . '.' . strtolower($ext);
        $dir      = BASE_PATH . '/storage/images/';
        $dest     = $dir . $filename;

        // Delete old picture if exists
        $user = $this->model->getById($id);
        if (!empty($user['profile_picture'])) {
            $old = $dir . basename($user['profile_picture']);
            if (file_exists($old)) @unlink($old);
        }

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            json_response(['status' => 'error', 'message' => 'Failed to save file.']);
            return;
        }

        $relativePath = 'storage/images/' . $filename;
        $this->model->updatePicture($id, $relativePath);
        $_SESSION['user']['profile_picture'] = $relativePath;

        json_response([
            'status'  => 'success',
            'message' => 'Profile picture updated.',
            'url'     => baseurl('/' . $relativePath),
        ]);
    }

    public function update()
    {
        $id    = (int) $_SESSION['user']['id'];
        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');

        if (!$name || !$email) {
            json_response(['status' => 'error', 'message' => 'Name and email are required.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            json_response(['status' => 'error', 'message' => 'Invalid email address.']);
            return;
        }

        if ($this->model->isEmailTaken($email, $id)) {
            json_response(['status' => 'error', 'message' => 'Email is already in use.']);
            return;
        }

        $this->model->updateInfo($id, $name, $email);

        $_SESSION['user']['full_name'] = $name;
        $_SESSION['user']['email']     = $email;

        json_response(['status' => 'success', 'message' => 'Profile updated successfully.', 'name' => $name]);
    }

    public function changePassword()
    {
        $id         = (int) $_SESSION['user']['id'];
        $current    = $_POST['current_password'] ?? '';
        $newPwd     = $_POST['new_password']      ?? '';
        $confirmPwd = $_POST['confirm_password']  ?? '';

        if (!$current || !$newPwd || !$confirmPwd) {
            json_response(['status' => 'error', 'message' => 'All password fields are required.']);
            return;
        }

        if (strlen($newPwd) < 6) {
            json_response(['status' => 'error', 'message' => 'New password must be at least 6 characters.']);
            return;
        }

        if ($newPwd !== $confirmPwd) {
            json_response(['status' => 'error', 'message' => 'New passwords do not match.']);
            return;
        }

        $hash = $this->model->getPasswordHash($id);

        if (!password_verify($current, $hash)) {
            json_response(['status' => 'error', 'message' => 'Current password is incorrect.']);
            return;
        }

        $this->model->updatePassword($id, password_hash($newPwd, PASSWORD_DEFAULT));

        json_response(['status' => 'success', 'message' => 'Password changed successfully.']);
    }
}
