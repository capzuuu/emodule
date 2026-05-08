<?php

namespace App\Controllers\Admin\Dashboard;

use App\Core\Controller;
use App\Models\Admin\Dashboard\Dashboard;

class DashboardController extends Controller
{
    private Dashboard $model;

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

        $this->model = new Dashboard();
    }

    public function index()
    {
        $counts = $this->model->getCounts();

        $this->view('admin/dashboard/dashboard', [
            'pageTitle'        => 'Dashboard',
            'pageSubtitle'     => 'Here\'s an overview of your E-Module LMS.',
            'counts'           => $counts,
            'recentUsers'      => $this->model->getRecentUsers(),
            'progressSummary'  => $this->model->getStudentProgressSummary(),
            'moduleStats'      => $this->model->getModuleCompletionStats(),
        ]);
    }

    public function json()
    {
        json_response([
            'counts'          => $this->model->getCounts(),
            'recentUsers'     => $this->model->getRecentUsers(),
            'progressSummary' => $this->model->getStudentProgressSummary(),
        ]);
    }
}
