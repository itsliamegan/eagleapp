<?php

namespace App\Actions\Admin;

use App\Models\Day;
use Janus\Action;
use Janus\Facades\Views;

class Index extends Action
{
    public function perform()
    {
        $title = 'Admin';
        $days  = Day::orderBy('order', 'asc')->get();
        
        return Views::render('admin/index', compact('title', 'days'));
    }
}
