<?php

namespace App\Controllers\Admin\UserAccounts;

use App\Core\Controller;
use App\Models\Admin\UserAccounts\UserAccounts;

class UserAccountsController extends Controller
{
    private UserAccounts $model;

    public function __construct()
    {
        start_session();
        check_auth();
        check_idle_session();
        remove_cache();

        if ($_SESSION['user']['role'] !== 'admin') {
            redirect('/');
            exit;
        }

        $this->model = new UserAccounts();
    }

    public function index()
    {
        $this->view('admin/userAccounts/users', [
            'pageTitle'    => 'User Management',
            'pageSubtitle' => 'Manage students and teachers.',
        ]);
    }

    public function json()
    {
        json_response(['data' => $this->model->getAllUsers()]);
    }

    public function create()
    {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role     = $_POST['role'] ?? 'student';

        if (!$name || !$email || !$password) {
            json_response(['status' => 'error', 'message' => 'All fields are required.']);
            return;
        }

        if (!in_array($role, ['student', 'teacher'])) {
            json_response(['status' => 'error', 'message' => 'Invalid role.']);
            return;
        }

        if ($this->model->isEmailExists($email)) {
            json_response(['status' => 'error', 'message' => 'Email already exists.']);
            return;
        }

        $created = $this->model->createUser([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role,
        ]);

        if ($created) {
            json_response(['status' => 'success', 'message' => ucfirst($role) . ' account created successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to create account.']);
        }
    }

    public function edit()
    {
        $id    = (int)($_POST['id'] ?? 0);
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role  = $_POST['role'] ?? '';

        if (!$id || !$name || !$email || !in_array($role, ['student', 'teacher'])) {
            json_response(['status' => 'error', 'message' => 'Invalid data.']);
            return;
        }

        if ($this->model->isEmailExists($email, $id)) {
            json_response(['status' => 'error', 'message' => 'Email already in use.']);
            return;
        }

        $updated = $this->model->updateUser($id, ['name' => $name, 'email' => $email, 'role' => $role]);

        json_response($updated
            ? ['status' => 'success', 'message' => 'Account updated successfully.']
            : ['status' => 'error',   'message' => 'No changes were made.']
        );
    }

    public function delete()
    {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { json_response(['status' => 'error', 'message' => 'Invalid ID.']); return; }

        $deleted = $this->model->deleteUser($id);
        json_response($deleted
            ? ['status' => 'success', 'message' => 'Account deleted successfully.']
            : ['status' => 'error',   'message' => 'Failed to delete account.']
        );
    }

    public function checkEmailDuplicate()
    {
        $email = trim($_POST['email'] ?? '');
        json_response(['isDuplicate' => $this->model->isEmailExists($email)]);
    }
}
