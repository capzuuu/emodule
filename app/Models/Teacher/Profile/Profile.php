<?php

namespace App\Models\Teacher\Profile;

use App\Core\Model;

class Profile extends Model
{
    private string $table = 'users';

    public function getById(int $id): ?array
    {
        // Ensure column exists
        try {
            $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) NULL");
        } catch (\Exception $e) {}

        $stmt = $this->query(
            "SELECT id, name, email, role, created_at, profile_picture FROM {$this->table} WHERE id = ? LIMIT 1",
            [$id]
        );
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function updatePicture(int $id, string $path): bool
    {
        return $this->query(
            "UPDATE {$this->table} SET profile_picture = ?, updated_at = NOW() WHERE id = ?",
            [$path, $id]
        )->rowCount() > 0;
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
