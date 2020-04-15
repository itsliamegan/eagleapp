<?php

namespace App\Actions;

use Janus\Actions\HandleError as Action;
use Janus\Http\Request;
use Janus\Facades\Views;

class HandleError extends Action
{
    public function perform(Request $req)
    {
        return Views::render('error');
    }
}
