<?php

namespace App;

use Janus\Facades\Auth;

class Current extends \Janus\Current
{
    public function student()
    {
        return Auth::current();
    }
}
