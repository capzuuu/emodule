<?php

namespace App\Models\Auth\Login;

use App\Core\Model;
use PDO;

class Login extends Model
{
    protected string $table = 'users';

    public function getByEmail(string $email): ?array
    {
        $stmt = $this->query(
            "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1",
            ['email' => $email]
        );
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($user) {
            // Normalize: map 'password' column to 'password_hash' for the controller
            if (!isset($user['password_hash']) && isset($user['password'])) {
                $user['password_hash'] = $user['password'];
            }
            // Normalize: map 'name' to 'full_name' for session compatibility
            if (!isset($user['full_name']) && isset($user['name'])) {
                $user['full_name'] = $user['name'];
            }
        }

        return $user;
    }

    // Stub — LMS does not use Google auth
    public function getByGoogleId(string $googleId): ?array
    {
        return null;
    }

    public function linkGoogleId(int $id, string $googleId): void {}
}
