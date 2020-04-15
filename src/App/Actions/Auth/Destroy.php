<?php

namespace App\Actions\Auth;

use Janus\Action;
use Janus\Facades\Auth;
use Janus\Http\Response;

class Destroy extends Action
{
    /**
     * Log the user out.
     *
     * @return Response
     */
    public function perform()
    {
        Auth::logout();

        return Response::redirect('/login');
    }
}
