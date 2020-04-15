<?php

namespace App\Actions\Auth;

use Janus\Action;
use Janus\Facades\Views;

class Create extends Action
{
    /**
     * Display the login page.
     *
     * @return string
     */
    public function perform()
    {
        return Views::render('login');
    }
}
