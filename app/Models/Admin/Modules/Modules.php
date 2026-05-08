<?php

namespace App\Models\Admin\Modules;

use App\Core\Model;

class Modules extends Model
{
    private string $table      = 'modules';
    private string $quizTable  = 'quiz_questions';
    private string $usersTable = 'users';

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT m.id, m.title, m.unit_number, m.outcome, m.file_path,
                   u.name AS teacher_name, m.created_at,
                   (SELECT COUNT(*) FROM {$this->quizTable} q WHERE q.module_id = m.id AND q.test_type = 'pre')  AS pre_count,
                   (SELECT COUNT(*) FROM {$this->quizTable} q WHERE q.module_id = m.id AND q.test_type = 'post') AS post_count
            FROM {$this->table} m
            LEFT JOIN {$this->usersTable} u ON u.id = m.teacher_id
            ORDER BY m.unit_number ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->query("
            SELECT m.*, u.name AS teacher_name
            FROM {$this->table} m
            LEFT JOIN {$this->usersTable} u ON u.id = m.teacher_id
            WHERE m.id = ? LIMIT 1
        ", [$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $this->query("
            INSERT INTO {$this->table}
                (title, outcome, content, quiz, answer, unit_number, file_path, teacher_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $data['title'],
            $data['outcome'],
            $data['content'],
            $data['quiz']       ?? '',
            $data['answer']     ?? '',
            $data['unit_number'],
            $data['file_path']  ?? null,
            $data['teacher_id'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return $this->query("
            UPDATE {$this->table}
            SET title = ?, outcome = ?, content = ?, quiz = ?, answer = ?,
                unit_number = ?, file_path = ?, teacher_id = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $data['title'],
            $data['outcome'],
            $data['content'],
            $data['quiz']       ?? '',
            $data['answer']     ?? '',
            $data['unit_number'],
            $data['file_path']  ?? null,
            $data['teacher_id'] ?? null,
            $id,
        ])->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id])->rowCount() > 0;
    }

    public function isUnitNumberTaken(int $unitNumber, ?int $excludeId = null): bool
    {
        $sql    = "SELECT COUNT(*) FROM {$this->table} WHERE unit_number = ?";
        $params = [$unitNumber];
        if ($excludeId) { $sql .= " AND id != ?"; $params[] = $excludeId; }
        return (int) $this->query($sql, $params)->fetchColumn() > 0;
    }

    public function getTeachers(): array
    {
        $stmt = $this->db->query("SELECT id, name FROM {$this->usersTable} WHERE role = 'teacher' ORDER BY name ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // ── Quiz Questions ──

    public function getQuestions(int $moduleId, string $testType): array
    {
        return $this->query(
            "SELECT * FROM {$this->quizTable} WHERE module_id = ? AND test_type = ? ORDER BY id ASC",
            [$moduleId, $testType]
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveQuestions(int $moduleId, string $testType, array $questions): void
    {
        $this->query(
            "DELETE FROM {$this->quizTable} WHERE module_id = ? AND test_type = ?",
            [$moduleId, $testType]
        );
        foreach ($questions as $q) {
            $this->query("
                INSERT INTO {$this->quizTable}
                    (module_id, test_type, question_text, option_a, option_b, option_c, option_d, correct_answer)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $moduleId,
                $testType,
                $q['question_text'],
                $q['option_a'],
                $q['option_b'],
                $q['option_c'],
                $q['option_d'],
                $q['correct_answer'],
            ]);
        }
    }
}
