<?php

namespace App\Actions\Admin\Schedules;

use App\Models\Day;
use App\Models\Schedule;
use \DateTimeImmutable;
use Janus\Action;
use Janus\Http\Request;
use Janus\Http\Response;

class StoreNormal extends Action
{
    /**
     * Create the normal schedules between two dates.
     *
     * @param Request $req
     * @return Response
     */
    public function perform(Request $req)
    {
        $startDayId      = $req->input->retrieve('startDayId');
        $startDateString = $req->input->retrieve('startDate');
        $stopDateString  = $req->input->retrieve('stopDate');

        $startDay  = Day::find($startDayId);
        $startDate = new DateTimeImmutable($startDateString);
        $stopDate  = new DateTimeImmutable($stopDateString);

        Schedule::createNormalBetween($startDay, $startDate, $stopDate);

        return Response::redirect('/admin');
    }
}
