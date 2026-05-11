<?php

namespace App\Models\Admin\Progress;

use App\Core\Model;

class Progress extends Model
{
    public function getAllStudentProgress(): array
    {
        $stmt = $this->db->query("
            SELECT
                u.id,
                u.name,
                u.email,
                COUNT(DISTINCT CASE WHEN up.status = 'completed' THEN up.module_id END) AS completed,
                (SELECT COUNT(*) FROM modules) AS total_modules
            FROM users u
            LEFT JOIN user_progress up ON up.user_id = u.id
            WHERE u.role = 'student'
            GROUP BY u.id, u.name, u.email
            ORDER BY completed DESC, u.name ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getModuleCompletionStats(): array
    {
        $stmt = $this->db->query("
            SELECT
                m.id,
                m.title,
                m.unit_number,
                COUNT(DISTINCT CASE WHEN up.status = 'completed' THEN up.user_id END) AS completions,
                (SELECT COUNT(*) FROM users WHERE role = 'student') AS total_students
            FROM modules m
            LEFT JOIN user_progress up ON up.module_id = m.id
            GROUP BY m.id, m.title, m.unit_number
            ORDER BY m.unit_number ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSummary(): array
    {
        $stmt = $this->db->query("
            SELECT
                (SELECT COUNT(*) FROM users WHERE role = 'student') AS total_students,
                (SELECT COUNT(*) FROM modules) AS total_modules,
                (SELECT COUNT(DISTINCT user_id) FROM user_progress WHERE status = 'completed') AS students_with_completion,
                (SELECT COUNT(*) FROM user_progress WHERE status = 'completed') AS total_completions
        ");
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }
}
