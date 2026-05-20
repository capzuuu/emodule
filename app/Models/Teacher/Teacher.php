<?php

namespace App\Models\Teacher;

use App\Core\Model;

class Teacher extends Model
{
    // ── Teacher info ──

    public function getTeacherIdByUserId(int $userId): ?int
    {
        $id = $this->query(
            "SELECT id FROM teachers WHERE user_id = ? LIMIT 1",
            [$userId]
        )->fetchColumn();
        return $id ? (int)$id : null;
    }

    public function ensureTeacherRecord(int $userId): ?int
    {
        $id = $this->query(
            "SELECT id FROM teachers WHERE user_id = ? LIMIT 1",
            [$userId]
        )->fetchColumn();

        if ($id) return (int)$id;

        $this->query(
            "INSERT INTO teachers (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())",
            [$userId]
        );
        $newId = (int)$this->db->lastInsertId();
        return $newId ?: null;
    }

    // ── Modules ──

    public function getModules(int $userId): array
    {
        return $this->query(
            "SELECT * FROM modules WHERE teacher_id = ? ORDER BY id ASC",
            [$userId]
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getModuleById(int $id, int $userId): ?array
    {
        $row = $this->query(
            "SELECT * FROM modules WHERE id = ? AND teacher_id = ? LIMIT 1",
            [$id, $userId]
        )->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function createModule(array $data): int
    {
        $this->query("
            INSERT INTO modules (title, outcome, content, quiz, answer, unit_number, file_path, youtube_url, teacher_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $data['title'],
            $data['outcome']     ?? '',
            $data['content'],
            $data['quiz']        ?? '',
            $data['answer']      ?? '',
            $data['unit_number'],
            $data['file_path']   ?? null,
            $data['youtube_url'] ?? null,
            $data['teacher_id'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateModule(int $id, int $userId, array $data): bool
    {
        return $this->query("
            UPDATE modules SET title=?, outcome=?, content=?, quiz=?, answer=?, unit_number=?, youtube_url=?, updated_at=NOW()
            WHERE id=? AND teacher_id=?
        ", [
            $data['title'],
            $data['outcome']     ?? '',
            $data['content'],
            $data['quiz']        ?? '',
            $data['answer']      ?? '',
            $data['unit_number'],
            $data['youtube_url'] ?? null,
            $id,
            $userId,
        ])->rowCount() > 0;
    }

    public function deleteModule(int $id, int $userId): bool
    {
        return $this->query(
            "DELETE FROM modules WHERE id = ? AND teacher_id = ?",
            [$id, $userId]
        )->rowCount() > 0;
    }

    public function isUnitNumberTaken(int $unitNumber, int $userId, ?int $excludeId = null): bool
    {
        $sql    = "SELECT COUNT(*) FROM modules WHERE unit_number = ? AND teacher_id = ?";
        $params = [$unitNumber, $userId];
        if ($excludeId) { $sql .= " AND id != ?"; $params[] = $excludeId; }
        return (int)$this->query($sql, $params)->fetchColumn() > 0;
    }

    // ── Quiz Questions ──

    public function getQuestions(int $moduleId, string $testType): array
    {
        return $this->query(
            "SELECT * FROM quiz_questions WHERE module_id = ? AND test_type = ? ORDER BY id ASC",
            [$moduleId, $testType]
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getQuestionCounts(int $moduleId): array
    {
        $pre  = (int)$this->query("SELECT COUNT(*) FROM quiz_questions WHERE module_id = ? AND test_type = 'pre'",  [$moduleId])->fetchColumn();
        $post = (int)$this->query("SELECT COUNT(*) FROM quiz_questions WHERE module_id = ? AND test_type = 'post'", [$moduleId])->fetchColumn();
        return ['pre' => $pre, 'post' => $post];
    }

    public function getPassingRate(int $moduleId): int
    {
        $val = $this->query(
            "SELECT passing_rate FROM quiz_questions WHERE module_id = ? AND test_type = 'post' LIMIT 1",
            [$moduleId]
        )->fetchColumn();
        return $val !== false ? (int)$val : 50;
    }

    public function savePassingRate(int $moduleId, int $rate): void
    {
        $this->query(
            "UPDATE quiz_questions SET passing_rate = ? WHERE module_id = ? AND test_type = 'post'",
            [max(1, min(100, $rate)), $moduleId]
        );
    }

    public function saveQuestions(int $moduleId, string $testType, array $questions): void
    {
        $this->query("DELETE FROM quiz_questions WHERE module_id = ? AND test_type = ?", [$moduleId, $testType]);
        foreach ($questions as $q) {
            $this->query("
                INSERT INTO quiz_questions (module_id, test_type, question_text, option_a, option_b, option_c, option_d, correct_answer)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [$moduleId, $testType, $q['question_text'], $q['option_a'], $q['option_b'], $q['option_c'], $q['option_d'], $q['correct_answer']]);
        }
    }

    // ── Students ──

    public function getAllStudents(): array
    {
        return $this->query(
            "SELECT id, name, email FROM users WHERE role = 'student' ORDER BY name ASC"
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUnassignedStudents(int $teacherId): array
    {
        return $this->query("
            SELECT u.id, u.name, u.email
            FROM users u
            WHERE u.role = 'student'
              AND u.id NOT IN (
                  SELECT s.user_id
                  FROM students s
                  INNER JOIN student_teacher st ON s.id = st.student_id
                  WHERE st.teacher_id = ?
              )
            ORDER BY u.name ASC
        ", [$teacherId])->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function assignStudent(int $studentUserId, int $teacherId, ?int $gradeId = null, ?int $sectionId = null): bool
    {
        $studentId = (int)$this->query(
            "SELECT id FROM students WHERE user_id = ? LIMIT 1",
            [$studentUserId]
        )->fetchColumn();

        if (!$studentId) {
            $this->query(
                "INSERT INTO students (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())",
                [$studentUserId]
            );
            $studentId = (int)$this->db->lastInsertId();
        }

        if (!$studentId) return false;

        $this->query(
            "INSERT IGNORE INTO student_teacher (student_id, teacher_id) VALUES (?, ?)",
            [$studentId, $teacherId]
        );

        if ($gradeId && $sectionId) {
            $this->query(
                "INSERT INTO student_grade_section (student_id, grade_id, section_id) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE grade_id = VALUES(grade_id), section_id = VALUES(section_id)",
                [$studentId, $gradeId, $sectionId]
            );
        }

        return true;
    }

    public function getStudents(int $teacherId): array
    {
        return $this->query("
            SELECT u.id, u.name, u.email,
                   g.id AS grade_id, g.name AS grade_name,
                   sec.id AS section_id, sec.name AS section_name,
                   COUNT(DISTINCT up.module_id) AS completed_modules
            FROM users u
            INNER JOIN students s ON u.id = s.user_id
            INNER JOIN student_teacher st ON s.id = st.student_id AND st.teacher_id = ?
            LEFT JOIN student_grade_section sgs ON s.id = sgs.student_id
            LEFT JOIN grades g ON sgs.grade_id = g.id
            LEFT JOIN sections sec ON sgs.section_id = sec.id
            LEFT JOIN user_progress up ON u.id = up.user_id AND up.status = 'completed'
            WHERE u.role = 'student'
            GROUP BY u.id, g.id, g.name, sec.id, sec.name
            ORDER BY u.name ASC
        ", [$teacherId])->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createStudent(array $data, int $teacherId): int
    {
        // Insert user
        $this->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?,?,?,?,NOW(),NOW())",
            [$data['name'], $data['email'], $data['password'], 'student']);
        $userId = (int)$this->db->lastInsertId();

        // Insert student record
        $this->query("INSERT INTO students (user_id, created_at, updated_at) VALUES (?,NOW(),NOW())", [$userId]);
        $studentId = (int)$this->db->lastInsertId();

        // Assign to teacher
        $this->query("INSERT INTO student_teacher (student_id, teacher_id) VALUES (?,?)", [$studentId, $teacherId]);

        // Assign grade/section if provided
        if (!empty($data['grade_id']) && !empty($data['section_id'])) {
            $this->query("INSERT INTO student_grade_section (student_id, grade_id, section_id) VALUES (?,?,?)
                ON DUPLICATE KEY UPDATE grade_id=VALUES(grade_id), section_id=VALUES(section_id)",
                [$studentId, $data['grade_id'], $data['section_id']]);
        }

        return $userId;
    }

    public function updateStudent(int $userId, array $data): void
    {
        $studentId = (int)$this->query("SELECT id FROM students WHERE user_id=? LIMIT 1", [$userId])->fetchColumn();
        if ($studentId && (!empty($data['grade_id']) || !empty($data['section_id']))) {
            $this->query("INSERT INTO student_grade_section (student_id, grade_id, section_id) VALUES (?,?,?)
                ON DUPLICATE KEY UPDATE grade_id=VALUES(grade_id), section_id=VALUES(section_id)",
                [$studentId, $data['grade_id'] ?? null, $data['section_id'] ?? null]);
        }
    }

    public function deleteStudent(int $userId): bool
    {
        return $this->query("DELETE FROM users WHERE id = ? AND role = 'student'", [$userId])->rowCount() > 0;
    }

    public function isEmailExists(string $email, ?int $excludeId = null): bool
    {
        $sql    = "SELECT COUNT(*) FROM users WHERE email = ?";
        $params = [$email];
        if ($excludeId) { $sql .= " AND id != ?"; $params[] = $excludeId; }
        return (int)$this->query($sql, $params)->fetchColumn() > 0;
    }

    // ── Grades & Sections ──

    public function getGrades(): array
    {
        return $this->db->query("SELECT * FROM grades ORDER BY name ASC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createGrade(string $name): bool
    {
        return $this->query("INSERT INTO grades (name) VALUES (?)", [$name])->rowCount() > 0;
    }

    public function updateGrade(int $id, string $name): bool
    {
        return $this->query("UPDATE grades SET name=? WHERE id=?", [$name, $id])->rowCount() > 0;
    }

    public function deleteGrade(int $id): bool
    {
        return $this->query("DELETE FROM grades WHERE id=?", [$id])->rowCount() > 0;
    }

    public function getSections(?int $gradeId = null): array
    {
        if ($gradeId) {
            return $this->query("SELECT * FROM sections WHERE grade_id=? ORDER BY name ASC", [$gradeId])->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $this->db->query("SELECT s.*, g.name AS grade_name FROM sections s LEFT JOIN grades g ON g.id=s.grade_id ORDER BY s.name ASC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createSection(string $name, ?int $gradeId = null): bool
    {
        return $this->query("INSERT INTO sections (name, grade_id) VALUES (?,?)", [$name, $gradeId])->rowCount() > 0;
    }

    public function updateSection(int $id, string $name): bool
    {
        return $this->query("UPDATE sections SET name=? WHERE id=?", [$name, $id])->rowCount() > 0;
    }

    public function deleteSection(int $id): bool
    {
        return $this->query("DELETE FROM sections WHERE id=?", [$id])->rowCount() > 0;
    }

    public function getStudentProgress(int $teacherId, int $teacherUserId, ?int $studentId = null, ?int $moduleId = null, ?int $sectionId = null, ?int $gradeId = null): array
    {
        $sql = "
            SELECT
                u.id    AS student_id,
                u.name  AS student_name,
                m.id    AS module_id,
                m.title AS module_title,
                m.unit_number,
                COALESCE(up.status, 'locked') AS status,
                up.quiz_score,
                up.quiz_attempts,
                up.completed_date,
                (SELECT COUNT(*) FROM quiz_questions WHERE module_id = m.id AND test_type = 'pre')  AS pre_count,
                (SELECT COUNT(*) FROM quiz_questions WHERE module_id = m.id AND test_type = 'post') AS post_count,
                (SELECT SUM(qa.answer = qq.correct_answer)
                 FROM quiz_answers qa
                 INNER JOIN quiz_questions qq ON qa.question_id = qq.id
                 WHERE qa.user_id = u.id AND qa.module_id = m.id AND qa.test_type = 'pre') AS pre_correct,
                (SELECT SUM(qa.answer = qq.correct_answer)
                 FROM quiz_answers qa
                 INNER JOIN quiz_questions qq ON qa.question_id = qq.id
                 WHERE qa.user_id = u.id AND qa.module_id = m.id AND qa.test_type = 'post') AS post_correct
            FROM users u
            INNER JOIN students s ON u.id = s.user_id
            INNER JOIN student_teacher st ON s.id = st.student_id AND st.teacher_id = ?
            INNER JOIN modules m ON m.teacher_id = ?
            LEFT JOIN user_progress up ON up.user_id = u.id AND up.module_id = m.id
            LEFT JOIN student_grade_section sgs ON s.id = sgs.student_id
            WHERE u.role = 'student'
        ";
        $params = [$teacherId, $teacherUserId];

        if ($studentId) { $sql .= ' AND u.id = ?';           $params[] = $studentId; }
        if ($moduleId)  { $sql .= ' AND m.id = ?';           $params[] = $moduleId; }
        if ($sectionId) { $sql .= ' AND sgs.section_id = ?'; $params[] = $sectionId; }
        if ($gradeId)   { $sql .= ' AND sgs.grade_id = ?';   $params[] = $gradeId; }

        $sql .= ' ORDER BY u.name ASC, m.unit_number ASC';

        return $this->query($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
