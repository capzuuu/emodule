<?php

namespace App\Models\Admin\UserAccounts;

use App\Core\Model;

class UserAccounts extends Model
{
    private string $table = 'users';

    public function getAllUsers(): array
    {
        $stmt = $this->db->query("
            SELECT id, name, email, role, created_at
            FROM {$this->table}
            WHERE role IN ('student', 'teacher')
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createUser(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (name, email, password, role, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())";
        return $this->query($sql, [
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
        ])->rowCount() > 0;
    }

    public function updateUser(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET name = ?, email = ?, role = ?, updated_at = NOW() WHERE id = ?";
        return $this->query($sql, [$data['name'], $data['email'], $data['role'], $id])->rowCount() > 0;
    }

    public function deleteUser(int $id): bool
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id])->rowCount() > 0;
    }

    public function isEmailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];
        if ($excludeId) { $sql .= " AND id != :id"; $params['id'] = $excludeId; }
        return $this->query($sql, $params)->fetchColumn() > 0;
    }
}
