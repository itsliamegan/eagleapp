<?php

namespace App\Actions\Import;

use Janus\Action;
use Janus\Facades\Views;

class Index extends Action
{
    public function perform()
    {
        $title = 'Import Courses';

        return Views::render('import/index', compact('title'));
    }
}
