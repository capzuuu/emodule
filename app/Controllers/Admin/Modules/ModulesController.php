<?php

namespace App\Controllers\Admin\Modules;

use App\Core\Controller;
use App\Models\Admin\Modules\Modules;

class ModulesController extends Controller
{
    private Modules $model;

    public function __construct()
    {
        start_session();
        check_auth();
        check_idle_session();
        remove_cache();

        if ($_SESSION['user']['role'] !== 'admin') {
            redirect('/');
            exit;
        }

        $this->model = new Modules();
    }

    public function index()
    {
        $this->view('admin/modules/modules', [
            'pageTitle'    => 'Modules',
            'pageSubtitle' => 'Manage learning modules and quiz questions.',
            'teachers'     => $this->model->getTeachers(),
        ]);
    }

    public function json()
    {
        json_response(['data' => $this->model->getAll()]);
    }

    public function create()
    {
        $title      = trim($_POST['title']       ?? '');
        $outcome    = trim($_POST['outcome']      ?? '');
        $content    = trim($_POST['content']      ?? '');
        $quiz       = trim($_POST['quiz']         ?? '');
        $answer     = trim($_POST['answer']       ?? '');
        $unitNumber = (int)($_POST['unit_number'] ?? 0);
        $teacherId  = ($_POST['teacher_id'] ?? '') !== '' ? (int)$_POST['teacher_id'] : null;

        if (!$title || !$outcome || !$content || !$unitNumber) {
            json_response(['status' => 'error', 'message' => 'Title, outcome, content and unit number are required.']);
            return;
        }

        if ($this->model->isUnitNumberTaken($unitNumber)) {
            json_response(['status' => 'error', 'message' => "Unit number {$unitNumber} is already taken."]);
            return;
        }

        $id = $this->model->create([
            'title'       => $title,
            'outcome'     => $outcome,
            'content'     => $content,
            'quiz'        => $quiz,
            'answer'      => $answer,
            'unit_number' => $unitNumber,
            'teacher_id'  => $teacherId,
        ]);

        json_response(['status' => 'success', 'message' => 'Module created successfully.', 'id' => $id]);
    }

    public function edit()
    {
        $id         = (int)($_POST['id']          ?? 0);
        $title      = trim($_POST['title']        ?? '');
        $outcome    = trim($_POST['outcome']       ?? '');
        $content    = trim($_POST['content']       ?? '');
        $quiz       = trim($_POST['quiz']          ?? '');
        $answer     = trim($_POST['answer']        ?? '');
        $unitNumber = (int)($_POST['unit_number']  ?? 0);
        $teacherId  = ($_POST['teacher_id'] ?? '') !== '' ? (int)$_POST['teacher_id'] : null;

        if (!$id || !$title || !$outcome || !$content || !$unitNumber) {
            json_response(['status' => 'error', 'message' => 'All required fields must be filled.']);
            return;
        }

        if ($this->model->isUnitNumberTaken($unitNumber, $id)) {
            json_response(['status' => 'error', 'message' => "Unit number {$unitNumber} is already taken."]);
            return;
        }

        $this->model->update($id, [
            'title'       => $title,
            'outcome'     => $outcome,
            'content'     => $content,
            'quiz'        => $quiz,
            'answer'      => $answer,
            'unit_number' => $unitNumber,
            'teacher_id'  => $teacherId,
        ]);

        json_response(['status' => 'success', 'message' => 'Module updated successfully.']);
    }

    public function delete()
    {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { json_response(['status' => 'error', 'message' => 'Invalid ID.']); return; }

        $this->model->delete($id);
        json_response(['status' => 'success', 'message' => 'Module deleted successfully.']);
    }

    public function getQuestions()
    {
        $moduleId = (int)($_GET['module_id'] ?? 0);
        $testType = $_GET['test_type'] ?? 'pre';

        if (!$moduleId) { json_response(['status' => 'error', 'message' => 'Invalid module ID.']); return; }
        if (!in_array($testType, ['pre', 'post'])) { json_response(['status' => 'error', 'message' => 'Invalid test type.']); return; }

        json_response(['status' => 'success', 'data' => $this->model->getQuestions($moduleId, $testType)]);
    }

    public function saveQuestions()
    {
        $input     = json_decode(file_get_contents('php://input'), true);
        $moduleId  = (int)($input['module_id']  ?? 0);
        $testType  = $input['test_type']         ?? 'pre';
        $questions = $input['questions']         ?? [];

        if (!$moduleId) { json_response(['status' => 'error', 'message' => 'Invalid module ID.']); return; }
        if (!in_array($testType, ['pre', 'post'])) { json_response(['status' => 'error', 'message' => 'Invalid test type.']); return; }

        foreach ($questions as $q) {
            if (empty($q['question_text']) || empty($q['option_a']) || empty($q['option_b']) ||
                empty($q['option_c'])      || empty($q['option_d']) || empty($q['correct_answer'])) {
                json_response(['status' => 'error', 'message' => 'All question fields are required.']);
                return;
            }
            if (!in_array($q['correct_answer'], ['A', 'B', 'C', 'D'])) {
                json_response(['status' => 'error', 'message' => 'Correct answer must be A, B, C, or D.']);
                return;
            }
        }

        $this->model->saveQuestions($moduleId, $testType, $questions);
        json_response(['status' => 'success', 'message' => ucfirst($testType) . '-test questions saved successfully.']);
    }
}
