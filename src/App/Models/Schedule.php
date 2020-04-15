<?php

namespace App\Models;

use \DateInterval;
use \DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'day_id',
        'date'
    ];

    //------------------------------------------------------
    // Relations
    //------------------------------------------------------
    
    /**
     * Get the Day.
     *
     * @return Day
     */
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    /**
     * If there are any, get the Periods that override the normal ones.
     *
     * @return Period[]
     */
    public function overriddenPeriods()
    {
        return $this
            ->belongsToMany(Period::class, 'schedule_periods')
            ->orderBy('placement');
    }

    //------------------------------------------------------
    // Attributes
    //------------------------------------------------------

    /**
     * Get all of the Periods, whether they are overridden or default.
     *
     * @return Period[]
     */
    public function getPeriodsAttribute()
    {
        $hasOverriddenPeriods = $this->overriddenPeriods->isNotEmpty();

        if ($hasOverriddenPeriods) {
            return $this->overriddenPeriods;
        }

        return $this->day->periods;
    }

    /**
     * Get all of the Courses for each of the Periods.
     *
     * @return Course[]
     */
    public function courses($student)
    {
        return $this->periods->map(function ($period) use ($student) {
            return Course::forPeriod($period, $this->day, $student);
        });
    }

    //------------------------------------------------------
    // Additional Methods
    //------------------------------------------------------

    /**
     * Get the Schedule for a date or null if there isn't one.
     *
     * @param DateTimeImmutable $date
     * @return Schedule|null
     */
    public static function forDate($date)
    {
        return self::query()
            ->where('date', $date)
            ->first();
    }

    /**
     * Create the normal Schedules, starting on the given Day and date and
     * ending at the given date.
     *
     * @param Day $startDay
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $stopDate
     * @return void
     */
    public static function createNormalBetween($startDay, $startDate, $stopDate)
    {
        self::query()
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $stopDate)
            ->delete();

        $day  = $startDay;
        $date = $startDate;

        while ($date <= $stopDate) {
            $dayOfWeek = $date->format('N');
            $tuesday   = '2';
            $thursday  = '4';
            $saturday  = '6';
            $sunday    = '7';

            $isWeekend   = $dayOfWeek === $saturday || $dayOfWeek === $sunday;
            $isDayOff    = Vacation::exists($date);
            $isSchoolDay = !$isWeekend && !$isDayOff;
            $isCpt       = $isSchoolDay && $dayOfWeek === $tuesday;
            $isAdvisory  = $isSchoolDay && $dayOfWeek === $thursday;

            if ($isSchoolDay) {
                $schedule = self::create([
                    'day_id' => $day->id,
                    'date'   => $date
                ]);
            }

            if ($isCpt) {
                $schedule->overriddenPeriods()->attach(Period::cpt()->id, [
                    'placement' => 1
                ]);

                for ($i = 0; $i < count($day->periods); $i++) {
                    $period = $day->periods[$i];
                    $schedule->overriddenPeriods()->attach($period->id, [
                        'placement' => $i + 1
                    ]);
                }
            }

            if ($isAdvisory) {
                $schedule->overriddenPeriods()->attach(Period::adv()->id, [
                    'placement' => 3
                ]);

                for ($i = 0; $i < count($day->periods); $i++) {
                    $period = $day->periods[$i];
                    $placement = $i + 1;

                    if ($i > 2) {
                        $placement++;
                    }

                    $schedule->overriddenPeriods()->attach($period->id, [
                        'placement' => $placement
                    ]);
                }
            }

            if ($isSchoolDay) {
                $day = $day->next;
            }

            $oneDay = DateInterval::createFromDateString('1 day');
            $date   = $date->add($oneDay);
        }
    }
}
