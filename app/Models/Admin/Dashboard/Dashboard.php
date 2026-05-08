<?php

namespace App\Models\Admin\Dashboard;

use App\Core\Model;

class Dashboard extends Model
{
    public function getCounts(): array
    {
        $stmt = $this->db->query("
            SELECT
                (SELECT COUNT(*) FROM users WHERE role = 'student') AS students,
                (SELECT COUNT(*) FROM users WHERE role = 'teacher') AS teachers,
                (SELECT COUNT(*) FROM users WHERE role = 'admin')   AS admins,
                (SELECT COUNT(*) FROM modules)                      AS modules,
                (SELECT COUNT(*) FROM user_progress WHERE status = 'completed') AS completed_progress
        ");
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    public function getRecentUsers(int $limit = 8): array
    {
        $stmt = $this->db->prepare("
            SELECT id, name, email, role, created_at
            FROM users
            WHERE role IN ('student', 'teacher')
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getStudentProgressSummary(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT
                u.id,
                u.name,
                u.email,
                COUNT(CASE WHEN up.status = 'completed' THEN 1 END) AS completed,
                COUNT(up.id) AS total,
                (SELECT COUNT(*) FROM modules) AS total_modules
            FROM users u
            LEFT JOIN user_progress up ON up.user_id = u.id
            WHERE u.role = 'student'
            GROUP BY u.id, u.name, u.email
            ORDER BY completed DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getModuleCompletionStats(): array
    {
        $stmt = $this->db->query("
            SELECT
                m.id,
                m.title,
                m.unit_number,
                COUNT(CASE WHEN up.status = 'completed' THEN 1 END) AS completions
            FROM modules m
            LEFT JOIN user_progress up ON up.module_id = m.id
            GROUP BY m.id, m.title, m.unit_number
            ORDER BY m.unit_number ASC
            LIMIT 10
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
