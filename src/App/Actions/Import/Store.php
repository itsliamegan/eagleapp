<?php

namespace App\Actions\Import;

use App\Current;
use App\Models\Course;
use Janus\Action;
use Janus\Http\Request;
use Janus\Http\Response;

class Store extends Action
{
    public function perform(Request $req, Current $current)
    {
        $aspenSchedule = $req->input->retrieve('aspenSchedule');
        $student       = $current->student();

        Course::createAllFromAspenSchedule($aspenSchedule, $student);

        return Response::redirect('/');
    }
}
