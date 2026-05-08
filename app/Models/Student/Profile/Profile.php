<?php

namespace App\Models\Student\Profile;

use App\Core\Model;

class Profile extends Model
{
    private string $table = 'users';

    public function getById(int $id): ?array
    {
        $stmt = $this->query(
            "SELECT id, name, email, role, created_at FROM {$this->table} WHERE id = ? LIMIT 1",
            [$id]
        );
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function updateInfo(int $id, string $name, string $email): bool
    {
        return $this->query(
            "UPDATE {$this->table} SET name = ?, email = ?, updated_at = NOW() WHERE id = ?",
            [$name, $email, $id]
        )->rowCount() > 0;
    }

    public function isEmailTaken(string $email, int $excludeId): bool
    {
        return (int) $this->query(
            "SELECT COUNT(*) FROM {$this->table} WHERE email = ? AND id != ?",
            [$email, $excludeId]
        )->fetchColumn() > 0;
    }

    public function getPasswordHash(int $id): string
    {
        return (string) $this->query(
            "SELECT password FROM {$this->table} WHERE id = ? LIMIT 1",
            [$id]
        )->fetchColumn();
    }

    public function updatePassword(int $id, string $hash): bool
    {
        return $this->query(
            "UPDATE {$this->table} SET password = ?, updated_at = NOW() WHERE id = ?",
            [$hash, $id]
        )->rowCount() > 0;
    }
}
