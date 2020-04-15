<?php

namespace App\Redirectors;

use Janus\Facades\Auth;
use Janus\Http\Request;

class GuardAdminPages
{
    /**
     * Redirect based on if the user is an admin.
     *
     * @param Request $req
     * @return string|null
     */
    public function redirectTo(Request $req)
    {
        $isAdminPage = preg_match('/admin/', $req->path);
        $isAdmin     = !is_null(Auth::current()) && Auth::current()->is_admin;

        if ($isAdminPage && !$isAdmin) {
            return '/';
        }
    }
}
