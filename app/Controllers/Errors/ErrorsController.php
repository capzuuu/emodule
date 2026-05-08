<?php

namespace App\Controllers\Errors;

use App\Core\Controller;

class ErrorsController extends Controller
{
    public function error404()
    {
        http_response_code(404);

        $this->view('errors/404', [
            'pageTitle' => '404 - Page Not Found'
        ]);
    }

    public function error500()
    {
        http_response_code(500);

        $this->view('errors/500', [
            'pageTitle' => '500 - Server Error'
        ]);
    }

    public function maintenance()
    {
        http_response_code(503);

        $this->view('errors/maintenance', [
            'pageTitle' => '503 - Maintenance Mode'
        ]);
    }

    public function voteClosed()
    {
        $this->view('errors/vote_closed', [
            'pageTitle' => 'Voting Closed'
        ]);
    }
}
