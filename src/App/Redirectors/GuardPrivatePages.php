<?php

namespace App\Redirectors;

use Janus\Facades\Auth;
use Janus\Http\Request;

class GuardPrivatePages
{
    /**
     * Redirect based on the authentication status of the user.
     *
     * @param Request $req
     * @return string|null
     */
    public function redirectTo(Request $req)
    {
        $isLoginPage = $req->path === '/login';
        $isLoggedIn  = Auth::isLoggedIn();

        if (!$isLoginPage && !$isLoggedIn) {
            return '/login';
        }

        if ($isLoginPage && $isLoggedIn) {
            return '/';
        }
    }
}
