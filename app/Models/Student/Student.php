<?php

namespace App\Models\Student;

use App\Core\Model;

class Student extends Model
{
    // ── Student info ──

    public function getByUserId(int $userId): ?array
    {
        $row = $this->query(
            "SELECT s.id, u.name, u.email, g.name AS grade, sec.name AS section
             FROM users u
             INNER JOIN students s ON u.id = s.user_id
             LEFT JOIN student_grade_section sgs ON s.id = sgs.student_id
             LEFT JOIN grades g ON sgs.grade_id = g.id
             LEFT JOIN sections sec ON sgs.section_id = sec.id
             WHERE u.id = ? LIMIT 1",
            [$userId]
        )->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // ── Modules ──

    private function getTeacherUserId(int $studentUserId): ?int
    {
        // Primary: via teachers table (teachers.user_id = modules.teacher_id)
        $id = $this->query("
            SELECT t.user_id
            FROM students s
            INNER JOIN student_teacher st ON st.student_id = s.id
            INNER JOIN teachers t         ON t.id = st.teacher_id
            WHERE s.user_id = ?
            LIMIT 1
        ", [$studentUserId])->fetchColumn();

        if ($id) return (int)$id;

        // Fallback: teacher has no teachers row — get user_id directly from student_teacher
        // by finding which user created modules and is linked to this student
        $id = $this->query("
            SELECT u.id
            FROM students s
            INNER JOIN student_teacher st ON st.student_id = s.id
            INNER JOIN users u ON u.role = 'teacher'
            WHERE s.user_id = ?
              AND EXISTS (SELECT 1 FROM modules WHERE teacher_id = u.id)
            LIMIT 1
        ", [$studentUserId])->fetchColumn();

        return $id ? (int)$id : null;
    }

    public function getModules(int $userId): array
    {
        $teacherUserId = $this->getTeacherUserId($userId);
        if (!$teacherUserId) return [];

        // Get raw modules ordered by unit_number
        $modules = $this->query("
            SELECT m.id, m.title, m.outcome, m.content, m.unit_number, m.file_path,
                   COALESCE(up.status, 'not_started') AS db_status,
                   COALESCE(up.quiz_score, 0)          AS quiz_score,
                   COALESCE(up.quiz_attempts, 0)        AS quiz_attempts,
                   up.completed_date,
                   (SELECT COUNT(*) FROM quiz_questions WHERE module_id = m.id AND test_type = 'pre')  AS pre_count,
                   (SELECT COUNT(*) FROM quiz_questions WHERE module_id = m.id AND test_type = 'post') AS post_count,
                   (SELECT SUM(qa.answer = qq.correct_answer)
                    FROM quiz_answers qa
                    JOIN quiz_questions qq ON qq.id = qa.question_id
                    WHERE qa.user_id = ? AND qa.module_id = m.id AND qa.test_type = 'pre') AS pre_correct,
                   (SELECT SUM(qa.answer = qq.correct_answer)
                    FROM quiz_answers qa
                    JOIN quiz_questions qq ON qq.id = qa.question_id
                    WHERE qa.user_id = ? AND qa.module_id = m.id AND qa.test_type = 'post') AS post_correct
            FROM modules m
            LEFT JOIN user_progress up ON up.module_id = m.id AND up.user_id = ?
            WHERE m.teacher_id = ?
            ORDER BY m.id ASC
        ", [$userId, $userId, $userId, $teacherUserId])->fetchAll(\PDO::FETCH_ASSOC);

        // Re-index to guarantee 0-based sequential keys
        $modules = array_values($modules);

        // Build completed IDs set
        $completedIds = [];
        foreach ($modules as $m) {
            if ($m['db_status'] === 'completed') {
                $completedIds[] = (int)$m['id'];
            }
        }

        // Find the last CONSECUTIVE completed index from index 0
        // (same logic as original: maxCompletedIdx = highest i where all 0..i are completed)
        $maxCompletedIdx = -1;
        foreach ($modules as $i => $m) {
            if (in_array((int)$m['id'], $completedIds)) {
                $maxCompletedIdx = $i;
            } else {
                break; // stop at first gap
            }
        }

        foreach ($modules as $i => &$m) {
            $isCompleted = in_array((int)$m['id'], $completedIds);
            $isLocked    = ($i === 0) ? false : ($i > $maxCompletedIdx + 1);

            if ($isCompleted) {
                $m['status'] = 'completed';
            } elseif (!$isLocked) {
                $m['status'] = 'available';
            } else {
                $m['status'] = 'locked';
            }
        }
        unset($m);

        // Attach pre-test completion flag for progress steps
        $moduleIds = array_column($modules, 'id');
        $presDone  = $this->getPreTestDoneMap($userId, $moduleIds);
        foreach ($modules as &$m) {
            $m['pre_done'] = $presDone[(int)$m['id']] ?? false;
        }
        unset($m);

        return $modules;
    }

    /**
     * Returns a map of module_id => bool indicating whether the student
     * has submitted at least one pre-test answer for each module.
     */
    public function getPreTestDoneMap(int $userId, array $moduleIds): array
    {
        if (empty($moduleIds)) return [];

        $map = [];
        try {
            $placeholders = implode(',', array_fill(0, count($moduleIds), '?'));
            $rows = $this->query(
                "SELECT DISTINCT module_id FROM quiz_answers
                 WHERE user_id = ? AND test_type = 'pre' AND module_id IN ({$placeholders})",
                array_merge([$userId], $moduleIds)
            )->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($moduleIds as $id) {
                $map[(int)$id] = in_array((string)$id, $rows) || in_array((int)$id, $rows);
            }
        } catch (\Exception $e) {
            foreach ($moduleIds as $id) $map[(int)$id] = false;
        }
        return $map;
    }

    public function getModule(int $moduleId): ?array
    {
        $row = $this->query(
            "SELECT * FROM modules WHERE id = ? LIMIT 1",
            [$moduleId]
        )->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            $rate = $this->query(
                "SELECT passing_rate FROM quiz_questions WHERE module_id = ? AND test_type = 'post' LIMIT 1",
                [$moduleId]
            )->fetchColumn();
            $row['passing_rate'] = $rate !== false ? (int)$rate : 50;
        }
        return $row ?: null;
    }

    // ── Progress ──

    public function getProgress(int $userId): array
    {
        return $this->query(
            "SELECT module_id, status, quiz_score, completed_date
             FROM user_progress WHERE user_id = ?",
            [$userId]
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ensureProgress(int $userId, int $moduleId, string $status = 'available'): void
    {
        $this->query("
            INSERT INTO user_progress (user_id, module_id, status)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                status = IF(status = 'locked', VALUES(status), status)
        ", [$userId, $moduleId, $status]);
    }

    public function unlockModule(int $userId, int $moduleId): void
    {
        $this->query("
            INSERT INTO user_progress (user_id, module_id, status)
            VALUES (?, ?, 'available')
            ON DUPLICATE KEY UPDATE
                status = IF(status = 'locked' OR status = 'available', 'available', status)
        ", [$userId, $moduleId]);
    }

    public function incrementPostAttempt(int $userId, int $moduleId): void
    {
        $this->query("
            INSERT INTO user_progress (user_id, module_id, status, quiz_attempts)
            VALUES (?, ?, 'available', 1)
            ON DUPLICATE KEY UPDATE quiz_attempts = quiz_attempts + 1
        ", [$userId, $moduleId]);
    }

    public function completeModule(int $userId, int $moduleId, int $score): void
    {
        $this->query("
            INSERT INTO user_progress (user_id, module_id, status, quiz_score, completed_date)
            VALUES (?, ?, 'completed', ?, NOW())
            ON DUPLICATE KEY UPDATE
                status = 'completed', quiz_score = VALUES(quiz_score), completed_date = NOW()
        ", [$userId, $moduleId, $score]);
    }

    // ── Quiz ──

    public function getQuestions(int $moduleId, string $testType): array
    {
        return $this->query(
            "SELECT id, question_text, option_a, option_b, option_c, option_d, correct_answer
             FROM quiz_questions WHERE module_id = ? AND test_type = ? ORDER BY id ASC",
            [$moduleId, $testType]
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTimeLimit(int $moduleId, string $testType): ?int
    {
        $val = $this->query(
            "SELECT time_limit_minutes FROM quiz_questions WHERE module_id = ? AND test_type = ? LIMIT 1",
            [$moduleId, $testType]
        )->fetchColumn();
        return ($val !== false && $val !== null) ? (int)$val : null;
    }

    public function saveAnswers(int $userId, int $moduleId, string $testType, array $answers): void
    {
        // Ensure table exists
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS quiz_answers (
                id         INT PRIMARY KEY AUTO_INCREMENT,
                user_id    INT NOT NULL,
                module_id  INT NOT NULL,
                test_type  ENUM('pre','post') NOT NULL,
                question_id INT NOT NULL,
                answer     ENUM('A','B','C','D') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_answer (user_id, module_id, test_type, question_id),
                FOREIGN KEY (user_id)     REFERENCES users(id)          ON DELETE CASCADE,
                FOREIGN KEY (module_id)   REFERENCES modules(id)        ON DELETE CASCADE,
                FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        foreach ($answers as $questionId => $answer) {
            $this->query("
                INSERT INTO quiz_answers (user_id, module_id, test_type, question_id, answer)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE answer = VALUES(answer)
            ", [$userId, $moduleId, $testType, (int)$questionId, $answer]);
        }
    }

    public function clearAnswers(int $userId, int $moduleId, string $testType): void
    {
        try {
            $this->query(
                "DELETE FROM quiz_answers WHERE user_id = ? AND module_id = ? AND test_type = ?",
                [$userId, $moduleId, $testType]
            );
        } catch (\Exception $e) {}
    }

    public function getAnswers(int $userId, int $moduleId, string $testType): array
    {
        // Return empty if table doesn't exist yet
        try {
            return $this->query(
                "SELECT question_id, answer FROM quiz_answers
                 WHERE user_id = ? AND module_id = ? AND test_type = ?",
                [$userId, $moduleId, $testType]
            )->fetchAll(\PDO::FETCH_KEY_PAIR);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function isLessonDone(int $userId, int $moduleId): bool
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS lesson_progress (
                    id         INT PRIMARY KEY AUTO_INCREMENT,
                    user_id    INT NOT NULL,
                    module_id  INT NOT NULL,
                    done_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_lesson (user_id, module_id),
                    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
                    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            return (bool)$this->query(
                "SELECT COUNT(*) FROM lesson_progress WHERE user_id = ? AND module_id = ?",
                [$userId, $moduleId]
            )->fetchColumn();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function setLessonDone(int $userId, int $moduleId): void
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS lesson_progress (
                    id         INT PRIMARY KEY AUTO_INCREMENT,
                    user_id    INT NOT NULL,
                    module_id  INT NOT NULL,
                    done_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_lesson (user_id, module_id),
                    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
                    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            $this->query(
                "INSERT IGNORE INTO lesson_progress (user_id, module_id) VALUES (?, ?)",
                [$userId, $moduleId]
            );
        } catch (\Exception $e) {
            // silently fail
        }
    }

    // ── Stats ──

    public function getStats(int $userId): array
    {
        $teacherUserId = $this->getTeacherUserId($userId);

        $total = $teacherUserId
            ? (int)$this->query("SELECT COUNT(*) FROM modules WHERE teacher_id = ?", [$teacherUserId])->fetchColumn()
            : 0;

        $completed = (int)$this->query(
            "SELECT COUNT(*) FROM user_progress WHERE user_id = ? AND status = 'completed'",
            [$userId]
        )->fetchColumn();
        $available = (int)$this->query(
            "SELECT COUNT(*) FROM user_progress WHERE user_id = ? AND status = 'available'",
            [$userId]
        )->fetchColumn();

        return [
            'total'     => $total,
            'completed' => $completed,
            'available' => $available,
            'locked'    => max(0, $total - $completed - $available),
            'pct'       => $total > 0 ? round($completed / $total * 100) : 0,
        ];
    }
}
