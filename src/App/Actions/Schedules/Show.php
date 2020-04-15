<?php

namespace App\Actions\Schedules;

use App\Models\Schedule;
use \DateInterval;
use \DateTimeImmutable;
use \Exception;
use Janus\Action;
use Janus\Facades\Views;
use Janus\Http\Response;

class Show extends Action
{
    /**
     * Display the schedule for the given date.
     *
     * @param string $date
     * @return string
     */
    public function perform($date)
    {
        try {
            $date = new DateTimeImmutable($date);
        } catch (Exception $e) {
            return Response::redirect('/');
        }

        $schedule = Schedule::forDate($date);

        $dayOfWeek = $date->format('N');
        $saturday  = '6';
        $sunday    = '7';

        $isWeekend   = $dayOfWeek === $saturday || $dayOfWeek === $sunday;
        $isDayOff    = is_null($schedule);
        $isSchoolDay = !$isDayOff && !$isWeekend;

        $title = $date->format('l, F jS');

        $oneDay = DateInterval::createFromDateString('1 day');
        $next   = $date->add($oneDay)->format('Y-m-d');
        $prev   = $date->sub($oneDay)->format('Y-m-d');

        return Views::render(
            'schedules/show',
            compact('title', 'next', 'prev', 'schedule', 'isWeekend', 'isDayOff', 'isSchoolDay')
        );
    }
}
