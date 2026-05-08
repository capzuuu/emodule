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
}
