<?php

namespace App\Controllers\Student\Profile;

use App\Core\Controller;
use App\Models\Student\Profile\Profile;

class ProfileController extends Controller
{
    private Profile $model;

    public function __construct()
    {
        start_session();
        check_auth();
        check_idle_session();
        remove_cache();

        if ($_SESSION['user']['role'] !== 'student') {
            redirect('/');
            exit;
        }

        $this->model = new Profile();
    }

    public function index()
    {
        $user = $this->model->getById((int) $_SESSION['user']['id']);

        $this->view('student/profile/profile', [
            'pageTitle'    => 'My Profile',
            'pageSubtitle' => 'Manage your account information.',
            'userName'     => $_SESSION['user']['full_name'] ?? $_SESSION['user']['name'] ?? 'Student',
            'user'         => $user,
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
        $_SESSION['user']['name']      = $name;
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

    // ── Upload profile picture ──
    public function uploadPicture()
    {
        $id = (int) $_SESSION['user']['id'];

        if (empty($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            json_response(['status' => 'error', 'message' => 'No file uploaded or upload error.']);
            return;
        }

        $file     = $_FILES['profile_picture'];
        $maxSize  = 2 * 1024 * 1024; // 2 MB
        $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mimeType = mime_content_type($file['tmp_name']);

        if ($file['size'] > $maxSize) {
            json_response(['status' => 'error', 'message' => 'File must be under 2MB.']);
            return;
        }

        if (!in_array($mimeType, $allowed, true)) {
            json_response(['status' => 'error', 'message' => 'Only JPEG, PNG, GIF, and WEBP images are allowed.']);
            return;
        }

        $storageDir = BASE_PATH . '/storage/images';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        // Remove old picture file if it exists
        $existing = $this->model->getProfilePicturePath($id);
        if ($existing) {
            $oldFile = BASE_PATH . '/' . ltrim($existing, '/');
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $filename = 'student_' . $id . '_' . time() . '.' . $ext;
        $dest     = $storageDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            json_response(['status' => 'error', 'message' => 'Failed to save image. Please try again.']);
            return;
        }

        $relativePath = 'storage/images/' . $filename;
        $this->model->updateProfilePicture($id, $relativePath);

        json_response(['status' => 'success', 'message' => 'Profile picture updated successfully.']);
    }

    // ── Serve profile picture ──
    public function serveProfilePicture()
    {
        $id   = (int) $_SESSION['user']['id'];
        $path = $this->model->getProfilePicturePath($id);

        if (!$path) {
            http_response_code(404);
            exit;
        }

        $filePath = BASE_PATH . '/' . ltrim($path, '/');

        if (!file_exists($filePath)) {
            http_response_code(404);
            exit;
        }

        $mime = mime_content_type($filePath);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, max-age=3600');
        readfile($filePath);
        exit;
    }
}