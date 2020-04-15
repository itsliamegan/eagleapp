<?php

namespace App\Actions\Auth;

use App\Models\Student;
use \Exception;
use \Google_Client as GoogleClient;
use Firebase\JWT\JWT;
use Janus\Action;
use Janus\Facades\Auth;
use Janus\Facades\Views;
use Janus\Http\Request;
use Janus\Http\Response;

class Store extends Action
{
    /**
     * Log the user in and redirect them.
     *
     * @param Request $req
     * @return string
     */
    public function perform(Request $req)
    {
        $token  = $req->input->retrieve('token');
        JWT::$leeway = 60;
        $client = new GoogleClient(['client_id' => getenv('GOOGLE_CLIENT_ID')]);

        $payload      = $client->verifyIdToken($token);
        $googleId     = $payload['sub'];
        $name         = $payload['name'];
        $email        = $payload['email'];
        $profileImage = $payload['picture'];

        $domain          = explode('@', $email)[1];
        $isAllowedDomain = $domain === 'barringtonschools.org';

        if (!$isAllowedDomain) {
            $message = 'You need a Barrington Public Schools email to access this application';
            $view    = Views::render('partials/message', compact('message'));

            return Response::html($view, 403);
        }

        $student = Student::updateOrCreate([
            'google_id' => $googleId
        ], [
            'google_id'     => $googleId,
            'name'          => $name,
            'email'         => $email,
            'profile_image' => $profileImage
        ]);

        Auth::login($student);

        return Response::redirect('/');
    }
}
