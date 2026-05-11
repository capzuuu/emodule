<?php

namespace App\Controllers\Student;

use App\Core\Controller;
use App\Models\Student\Student;

class StudentController extends Controller
{
    private Student $model;
    private int     $userId;
    private string  $userName;

    public function __construct()
    {
        start_session();
        check_auth();
        check_idle_session();
        remove_cache();

        if ($_SESSION['user']['role'] !== 'student') {
            redirect('/');
            exit;
        }

        $this->userId   = (int)$_SESSION['user']['id'];
        $this->userName = $_SESSION['user']['full_name'] ?? $_SESSION['user']['name'] ?? 'Student';
        $this->model    = new Student();
    }

    // ── Dashboard ──
    public function index()
    {
        $stats   = $this->model->getStats($this->userId);
        $modules = $this->model->getModules($this->userId);

        $this->view('student/dashboard/dashboard', [
            'pageTitle' => 'My Dashboard',
            'userName'  => $this->userName,
            'stats'     => $stats,
            'modules'   => $modules,
        ]);
    }

    // ── Modules list ──
    public function modules()
    {
        $modules = $this->model->getModules($this->userId);

        $this->view('student/modules/modules', [
            'pageTitle' => 'My Modules',
            'userName'  => $this->userName,
            'modules'   => $modules,
        ]);
    }

    // ── Single module view ──
    public function moduleView(int $moduleId)
    {
        $module  = $this->model->getModule($moduleId);
        if (!$module) { redirect('/student/modules'); exit; }

        $modules  = $this->model->getModules($this->userId);
        $progress = array_column($modules, null, 'id');
        $status   = $progress[$moduleId]['status'] ?? 'locked';

        if ($status === 'locked') { redirect('/student/modules'); exit; }

        $preQuestions  = $this->model->getQuestions($moduleId, 'pre');
        $postQuestions = $this->model->getQuestions($moduleId, 'post');

        // Always check if pre-test was submitted (needed for unlock logic)
        $preDoneMap = $this->model->getPreTestDoneMap($this->userId, [$moduleId]);
        $preDone    = $preDoneMap[$moduleId] ?? false;

        // Check if lesson was marked done (stored in session or a separate flag)
        $lessonDone = $this->model->isLessonDone($this->userId, $moduleId);

        // Load saved answers for completed modules
        $preAnswers  = $status === 'completed' ? $this->model->getAnswers($this->userId, $moduleId, 'pre')  : [];
        $postAnswers = $status === 'completed' ? $this->model->getAnswers($this->userId, $moduleId, 'post') : [];

        $this->view('student/modules/view', [
            'pageTitle'     => 'Unit ' . $module['unit_number'] . ': ' . $module['title'],
            'userName'      => $this->userName,
            'module'        => $module,
            'status'        => $status,
            'preDone'       => $preDone,
            'lessonDone'    => $lessonDone,
            'preQuestions'  => $preQuestions,
            'postQuestions' => $postQuestions,
            'preAnswers'    => $preAnswers,
            'postAnswers'   => $postAnswers,
            'progress'      => $progress,
        ]);
    }

    // ── Mark lesson as done (AJAX) ──
    public function markLessonDone()
    {
        $input    = json_decode(file_get_contents('php://input'), true);
        $moduleId = (int)($input['module_id'] ?? 0);

        if (!$moduleId) {
            json_response(['success' => false, 'message' => 'Invalid module.']);
            return;
        }

        $this->model->setLessonDone($this->userId, $moduleId);
        json_response(['success' => true]);
    }

    // ── Submit test (AJAX) ──
    public function submitTest()
    {
        $input    = json_decode(file_get_contents('php://input'), true);
        $moduleId = (int)($input['module_id'] ?? 0);
        $testType = $input['test_type'] ?? '';
        $answers  = $input['answers']   ?? [];

        if (!$moduleId || !in_array($testType, ['pre', 'post'])) {
            json_response(['success' => false, 'message' => 'Invalid request.']);
            return;
        }

        $questions = $this->model->getQuestions($moduleId, $testType);
        if (empty($questions)) {
            json_response(['success' => false, 'message' => 'No questions found.']);
            return;
        }

        $correct = 0;
        foreach ($questions as $q) {
            if (isset($answers[$q['id']]) && $answers[$q['id']] === $q['correct_answer']) {
                $correct++;
            }
        }
        $total = count($questions);
        $score = (int)round($correct / $total * 100);

        // Get the module's passing rate (default 50 if not set)
        $module      = $this->model->getModule($moduleId);
        $passingRate = (int)($module['passing_rate'] ?? 50);

        // Save student answers
        $this->model->saveAnswers($this->userId, $moduleId, $testType, $answers);

        // On post-test: track attempt, complete if passed, otherwise clear answers for retry
        if ($testType === 'post') {
            $this->model->incrementPostAttempt($this->userId, $moduleId);
            if ($score >= $passingRate) {
                $this->model->completeModule($this->userId, $moduleId, $score);
            } else {
                $this->model->clearAnswers($this->userId, $moduleId, 'post');
            }
        }

        json_response([
            'success'      => true,
            'score'        => $score,
            'correct'      => $correct,
            'total'        => $total,
            'test_type'    => $testType,
            'passing_rate' => $passingRate,
            'passed'       => $score >= $passingRate,
        ]);
    }

    // ── Serve module PDF ──
    public function servePdf(int $moduleId)
    {
        $module = $this->model->getModule($moduleId);

        if (!$module || empty($module['file_path'])) {
            http_response_code(404); exit;
        }

        $modules  = $this->model->getModules($this->userId);
        $progress = array_column($modules, null, 'id');
        if (($progress[$moduleId]['status'] ?? 'locked') === 'locked') {
            http_response_code(403); exit;
        }

        $filePath = BASE_PATH . '/' . ltrim($module['file_path'], '/');

        if (!file_exists($filePath)) {
            http_response_code(404); exit;
        }

        // Override frame-ancestors so the PDF can be embedded in our own iframe
        header_remove('Content-Security-Policy');
        header('Content-Security-Policy: frame-ancestors \'self\'');
        header('X-Frame-Options: SAMEORIGIN');
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, max-age=3600');
        readfile($filePath);
        exit;
    }
    public function progress()
    {
        $modules = $this->model->getModules($this->userId);
        $stats   = $this->model->getStats($this->userId);

        $this->view('student/progress/progress', [
            'pageTitle' => 'My Progress',
            'userName'  => $this->userName,
            'modules'   => $modules,
            'stats'     => $stats,
        ]);
    }
}
