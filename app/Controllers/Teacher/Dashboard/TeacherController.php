<?php

namespace App\Controllers\Teacher\Dashboard;

use App\Core\Controller;
use App\Models\Teacher\Teacher;

class TeacherController extends Controller
{
    private Teacher $model;
    private int     $userId;
    private ?int    $teacherId;

    public function __construct()
    {
        start_session();
        check_auth();
        check_idle_session();
        remove_cache();

        if ($_SESSION['user']['role'] !== 'teacher') {
            redirect('/');
            exit;
        }

        $this->userId   = (int)$_SESSION['user']['id'];
        $this->model    = new Teacher();
        $this->teacherId = $this->model->getTeacherIdByUserId($this->userId);
    }

    // ── Dashboard page ──
    public function index()
    {
        $modules  = $this->model->getModules($this->userId);
        $students = $this->teacherId ? $this->model->getStudents($this->teacherId) : [];

        $this->view('teacher/dashboard/dashboard', [
            'pageTitle'  => 'Teacher Dashboard',
            'modules'    => $modules,
            'students'   => $students,
            'teacherId'  => $this->teacherId,
            'userName'   => $_SESSION['user']['full_name'],
        ]);
    }

    // ── Modules page ──
    public function modules()
    {
        $this->view('teacher/modules/modules', [
            'pageTitle' => 'Module Management',
            'userName'  => $_SESSION['user']['full_name'],
        ]);
    }

    // ── Tests page ──
    public function tests()
    {
        $modules = $this->model->getModules($this->userId);
        $this->view('teacher/tests/tests', [
            'pageTitle' => 'Test Management',
            'modules'   => $modules,
            'userName'  => $_SESSION['user']['full_name'],
        ]);
    }

    // ── Students page ──
    public function students()
    {
        $grades   = $this->model->getGrades();
        $sections = $this->model->getSections();
        $this->view('teacher/students/students', [
            'pageTitle' => 'My Students',
            'grades'    => $grades,
            'sections'  => $sections,
            'userName'  => $_SESSION['user']['full_name'],
        ]);
    }

    // ── Grades & Sections page ──
    public function grades()
    {
        $this->view('teacher/grades/grades', [
            'pageTitle' => 'Grades & Sections',
            'userName'  => $_SESSION['user']['full_name'],
        ]);
    }

    // ── Progress page ──
    public function progress()
    {
        $modules  = $this->model->getModules($this->userId);
        $students = $this->teacherId ? $this->model->getStudents($this->teacherId) : [];
        $this->view('teacher/progress/progress', [
            'pageTitle' => 'Track Progress',
            'modules'   => $modules,
            'students'  => $students,
            'userName'  => $_SESSION['user']['full_name'],
        ]);
    }

    // ── Modules JSON ──
    public function modulesJson()
    {
        $modules = $this->model->getModules($this->userId);
        foreach ($modules as &$m) {
            $counts        = $this->model->getQuestionCounts((int)$m['id']);
            $m['pre_count']  = $counts['pre'];
            $m['post_count'] = $counts['post'];
        }
        unset($m);
        json_response(['success' => true, 'data' => $modules]);
    }

    public function moduleCreate()
    {
        $title      = trim($_POST['title']       ?? '');
        $outcome    = trim($_POST['outcome']      ?? '');
        $content    = trim($_POST['content']      ?? '');
        $unitNumber = (int)($_POST['unit_number'] ?? 0);

        if (!$title || !$content || !$unitNumber) {
            json_response(['success' => false, 'message' => 'Title, content and unit number are required.']);
            return;
        }

        if ($this->model->isUnitNumberTaken($unitNumber, $this->userId)) {
            json_response(['success' => false, 'message' => "Unit number {$unitNumber} is already taken."]);
            return;
        }

        $filePath = null;
        if (!empty($_FILES['module_file']['name'])) {
            $uploadDir = BASE_PATH . '/storage/documents/';
            $ext       = strtolower(pathinfo($_FILES['module_file']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') { json_response(['success' => false, 'message' => 'Only PDF files are allowed.']); return; }
            $fileName  = 'doc_' . bin2hex(random_bytes(8)) . '.pdf';
            if (move_uploaded_file($_FILES['module_file']['tmp_name'], $uploadDir . $fileName)) {
                $filePath = 'storage/documents/' . $fileName;
            }
        }

        $id = $this->model->createModule([
            'title'       => $title,
            'outcome'     => $outcome,
            'content'     => $content,
            'quiz'        => '',
            'answer'      => '',
            'unit_number' => $unitNumber,
            'file_path'   => $filePath,
            'teacher_id'  => $this->userId,
        ]);

        json_response(['success' => true, 'message' => 'Module created successfully.', 'id' => $id]);
    }

    public function moduleEdit()
    {
        $input      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id         = (int)($input['id']          ?? 0);
        $title      = trim($input['title']        ?? '');
        $outcome    = trim($input['outcome']       ?? '');
        $content    = trim($input['content']       ?? '');
        $unitNumber = (int)($input['unit_number']  ?? 0);

        if (!$id || !$title || !$content || !$unitNumber) {
            json_response(['success' => false, 'message' => 'All required fields must be filled.']);
            return;
        }

        if ($this->model->isUnitNumberTaken($unitNumber, $this->userId, $id)) {
            json_response(['success' => false, 'message' => "Unit number {$unitNumber} is already taken."]);
            return;
        }

        $existing = $this->model->getModuleById($id, $this->userId);
        $this->model->updateModule($id, $this->userId, [
            'title'       => $title,
            'outcome'     => $outcome,
            'content'     => $content,
            'quiz'        => $existing['quiz']   ?? '',
            'answer'      => $existing['answer'] ?? '',
            'unit_number' => $unitNumber,
        ]);
        json_response(['success' => true, 'message' => 'Module updated successfully.']);
    }

    public function moduleDelete()
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $id    = (int)($input['id'] ?? $_POST['id'] ?? 0);
        if (!$id) { json_response(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->deleteModule($id, $this->userId);
        json_response(['success' => true, 'message' => 'Module deleted.']);
    }

    // ── Quiz Questions ──
    public function questionsGet()
    {
        $moduleId = (int)($_GET['module_id'] ?? 0);
        $testType = $_GET['test_type'] ?? '';
        if (!$moduleId) { json_response(['success' => false, 'message' => 'Invalid module ID.']); return; }

        // Verify this module belongs to the requesting teacher
        if (!$this->model->getModuleById($moduleId, $this->userId)) {
            json_response(['success' => false, 'message' => 'Access denied.']);
            return;
        }

        if ($testType && in_array($testType, ['pre', 'post'])) {
            json_response([
                'success'      => true,
                'questions'    => $this->model->getQuestions($moduleId, $testType),
                'passing_rate' => $this->model->getPassingRate($moduleId),
            ]);
        } else {
            json_response([
                'success'      => true,
                'counts'       => $this->model->getQuestionCounts($moduleId),
                'passing_rate' => $this->model->getPassingRate($moduleId),
            ]);
        }
    }

    public function questionsSave()
    {
        $input     = json_decode(file_get_contents('php://input'), true);
        $moduleId  = (int)($input['module_id']   ?? 0);
        $testType  = $input['test_type']          ?? '';
        $questions = $input['questions']          ?? [];
        $passingRate = isset($input['passing_rate']) ? (int)$input['passing_rate'] : null;

        if (!$moduleId || !in_array($testType, ['pre', 'post'])) {
            json_response(['success' => false, 'message' => 'Invalid module ID or test type.']);
            return;
        }

        // Verify this module belongs to the requesting teacher
        if (!$this->model->getModuleById($moduleId, $this->userId)) {
            json_response(['success' => false, 'message' => 'Access denied.']);
            return;
        }

        foreach ($questions as $q) {
            if (empty($q['question_text']) || empty($q['option_a']) || empty($q['option_b']) ||
                empty($q['option_c'])      || empty($q['option_d']) || !in_array($q['correct_answer'] ?? '', ['A','B','C','D'])) {
                json_response(['success' => false, 'message' => 'All question fields are required.']);
                return;
            }
        }

        $this->model->saveQuestions($moduleId, $testType, $questions);

        // Save passing rate only for post-test
        if ($testType === 'post' && $passingRate !== null) {
            $this->model->savePassingRate($moduleId, $passingRate);
        }

        json_response(['success' => true, 'message' => ucfirst($testType) . '-test saved successfully.']);
    }

    // ── Students ──
    public function studentsJson()
    {
        if (!$this->teacherId) {
            $this->teacherId = $this->model->ensureTeacherRecord($this->userId);
        }
        $students = $this->teacherId ? $this->model->getStudents($this->teacherId) : [];
        json_response(['success' => true, 'data' => $students]);
    }

    public function unassignedStudentsJson()
    {
        if (!$this->teacherId) {
            $this->teacherId = $this->model->ensureTeacherRecord($this->userId);
        }
        $students = $this->teacherId
            ? $this->model->getUnassignedStudents($this->teacherId)
            : $this->model->getAllStudents();
        json_response(['success' => true, 'data' => $students]);
    }

    public function studentCreate()
    {
        $input         = json_decode(file_get_contents('php://input'), true);
        $studentUserId = (int)($input['student_user_id'] ?? 0);
        $gradeId       = (int)($input['grade_id']        ?? 0) ?: null;
        $sectionId     = (int)($input['section_id']      ?? 0) ?: null;

        if (!$studentUserId) {
            json_response(['success' => false, 'message' => 'Please select a student.']);
            return;
        }

        if (!$this->teacherId) {
            $this->teacherId = $this->model->ensureTeacherRecord($this->userId);
        }

        if (!$this->teacherId) {
            json_response(['success' => false, 'message' => 'Unable to resolve teacher profile.']);
            return;
        }

        if (!$this->model->assignStudent($studentUserId, $this->teacherId, $gradeId, $sectionId)) {
            json_response(['success' => false, 'message' => 'Student not found or already assigned.']);
            return;
        }

        json_response(['success' => true, 'message' => 'Student assigned successfully.']);
    }

    public function studentCreateNew()
    {
        $input     = json_decode(file_get_contents('php://input'), true);
        $name      = trim($input['name']     ?? '');
        $email     = trim($input['email']    ?? '');
        $password  = $input['password']      ?? '';
        $gradeId   = (int)($input['grade_id']   ?? 0) ?: null;
        $sectionId = (int)($input['section_id'] ?? 0) ?: null;

        if (!$name || !$email || !$password) {
            json_response(['success' => false, 'message' => 'Name, email and password are required.']);
            return;
        }

        if (strlen($password) < 6) {
            json_response(['success' => false, 'message' => 'Password must be at least 6 characters.']);
            return;
        }

        if ($this->model->isEmailExists($email)) {
            json_response(['success' => false, 'message' => 'Email already exists.']);
            return;
        }

        if (!$this->teacherId) {
            $this->teacherId = $this->model->ensureTeacherRecord($this->userId);
        }

        if (!$this->teacherId) {
            json_response(['success' => false, 'message' => 'Unable to resolve teacher profile.']);
            return;
        }

        $this->model->createStudent([
            'name'       => $name,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'grade_id'   => $gradeId,
            'section_id' => $sectionId,
        ], $this->teacherId);

        json_response(['success' => true, 'message' => 'Student account created and assigned successfully.']);
    }

    public function studentEdit()
    {
        $input     = json_decode(file_get_contents('php://input'), true);
        $userId    = (int)($input['id']         ?? 0);
        $gradeId   = (int)($input['grade_id']   ?? 0);
        $sectionId = (int)($input['section_id'] ?? 0);

        if (!$userId) {
            json_response(['success' => false, 'message' => 'Invalid student.']);
            return;
        }

        $this->model->updateStudent($userId, [
            'grade_id'   => $gradeId   ?: null,
            'section_id' => $sectionId ?: null,
        ]);

        json_response(['success' => true, 'message' => 'Student updated successfully.']);
    }

    public function studentDelete()
    {
        $input  = json_decode(file_get_contents('php://input'), true);
        $userId = (int)($input['id'] ?? 0);
        if (!$userId) { json_response(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->deleteStudent($userId);
        json_response(['success' => true, 'message' => 'Student deleted.']);
    }

    // ── Grades ──
    public function gradesJson()
    {
        json_response(['success' => true, 'data' => $this->model->getGrades()]);
    }

    public function gradeCreate()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $name  = trim($input['name'] ?? '');
        if (!$name) { json_response(['success' => false, 'message' => 'Grade name is required.']); return; }
        $this->model->createGrade($name);
        json_response(['success' => true, 'message' => 'Grade created.']);
    }

    public function gradeEdit()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id    = (int)($input['id'] ?? 0);
        $name  = trim($input['name'] ?? '');
        if (!$id || !$name) { json_response(['success' => false, 'message' => 'Invalid data.']); return; }
        $this->model->updateGrade($id, $name);
        json_response(['success' => true, 'message' => 'Grade updated.']);
    }

    public function gradeDelete()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id    = (int)($input['id'] ?? 0);
        if (!$id) { json_response(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->deleteGrade($id);
        json_response(['success' => true, 'message' => 'Grade deleted.']);
    }

    // ── Sections ──
    public function sectionsJson()
    {
        $gradeId = isset($_GET['grade_id']) ? (int)$_GET['grade_id'] : null;
        json_response(['success' => true, 'data' => $this->model->getSections($gradeId)]);
    }

    public function sectionCreate()
    {
        $input   = json_decode(file_get_contents('php://input'), true);
        $name    = trim($input['name']     ?? '');
        $gradeId = (int)($input['grade_id'] ?? 0) ?: null;
        if (!$name) { json_response(['success' => false, 'message' => 'Section name is required.']); return; }
        $this->model->createSection($name, $gradeId);
        json_response(['success' => true, 'message' => 'Section created.']);
    }

    public function sectionEdit()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id    = (int)($input['id'] ?? 0);
        $name  = trim($input['name'] ?? '');
        if (!$id || !$name) { json_response(['success' => false, 'message' => 'Invalid data.']); return; }
        $this->model->updateSection($id, $name);
        json_response(['success' => true, 'message' => 'Section updated.']);
    }

    public function sectionDelete()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id    = (int)($input['id'] ?? 0);
        if (!$id) { json_response(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->deleteSection($id);
        json_response(['success' => true, 'message' => 'Section deleted.']);
    }
}
