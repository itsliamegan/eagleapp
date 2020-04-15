<?php

namespace App\Actions;

use \DateTimeImmutable;
use Janus\Action;
use Janus\Http\Response;

class Index extends Action
{
    public function perform()
    {
        $date = new DateTimeImmutable;
        $date = $date->format('Y-m-d');

        return Response::redirect("/schedules/{$date}");
    }
}
