<?php

namespace App\Controllers\Admin\Progress;

use App\Core\Controller;
use App\Models\Admin\Progress\Progress;

class ProgressController extends Controller
{
    private Progress $model;

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

        $this->model = new Progress();
    }

    public function index()
    {
        $this->view('admin/progress/progress', [
            'pageTitle'    => 'Student Progress',
            'pageSubtitle' => 'Overview of student module completion.',
            'summary'      => $this->model->getSummary(),
            'moduleStats'  => $this->model->getModuleCompletionStats(),
        ]);
    }

    public function json()
    {
        json_response(['data' => $this->model->getAllStudentProgress()]);
    }
}
