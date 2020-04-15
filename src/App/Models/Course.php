<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'period_id',
        'day_id',
        'student_id'
    ];

    //------------------------------------------------------
    // Relations 
    //------------------------------------------------------

    /**
     * Get all the Day that this Course occurs on.
     * 
     * @return Day
     */
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    /**
     * Get the Period that this Course occurs on.
     *
     * @return Period
     */
    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * Get the Student that this course belongs to.
     * 
     * @return Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    //------------------------------------------------------
    // Additional Methods 
    //------------------------------------------------------

    /**
     * Get the Course for a given Day and Period.
     *
     * @param Period $period
     * @param Day $day
     * @param Student $student
     * @return self
     */
    public static function forPeriod($period, $day, $student)
    {
        return self::query()
            ->where('period_id', $period->id)
            ->where('day_id', $day->id)
            ->where('student_id', $student->id)
            ->first();
    }

    /**
     * Create all the Courses from the aspen schedule.
     * 
     * @param string $aspenSchedule
     * @param Student $student
     * @return void
     */
    public static function createAllFromAspenSchedule($aspenSchedule, $student)
    {
        $aspenDetails = [];

        $periodPlacement = 0;
        $dayOrder = 1;

        $aspenLines = explode("\n", $aspenSchedule);

        for ($i = 0; $i < count($aspenLines); $i++) {
            $aspenLine = trim($aspenLines[$i]);

            if (preg_match('/^\d-[ABCDEF]$/i', $aspenLine) === 1) {
                $periodPlacement++;
                continue;
            }

            if (preg_match('/- Day/i', $aspenLine) === 1) {
                continue;
            }

            if (isset($aspenLines[$i - 1]) &&
                preg_match('/^[a-zA-Z0-9]+-[a-zA-Z0-9]+$/i', trim($aspenLines[$i - 1])) === 1 &&
                preg_match('/^\d-[ABCDEF]$/i', trim($aspenLines[$i - 1])) === 0) {
                $aspenDetails[$periodPlacement][$dayOrder] = $aspenLine;
            }

            if (isset($aspenLines[$i - 3]) &&
                preg_match('/^[a-zA-Z0-9]+-[a-zA-Z0-9]+$/i', trim($aspenLines[$i - 3])) === 1 &&
                preg_match('/^\d-[ABCDEF]$/i', trim($aspenLines[$i - 3])) === 0) {
                if ($dayOrder === 7) {
                    $dayOrder = 1;
                } else {
                    $dayOrder++;
                }
            }
        }

        self::where('student_id', $student->id)->delete();

        for ($periodPlacement = 1; $periodPlacement < 7; $periodPlacement++) {
            for($dayOrder = 1; $dayOrder < 8; $dayOrder++) {
                $day    = Day::where('order', $dayOrder)->first();
                $period = Period::query()
                    ->whereHas('days', function ($query) use ($day, $periodPlacement) {
                        $query->where('days.id', $day->id)->where('placement', $periodPlacement);
                    })
                    ->first();

                $name = $aspenDetails[$periodPlacement][$dayOrder];

                self::create([
                    'name'       => $name,
                    'period_id'  => $period->id,
                    'day_id'     => $day->id,
                    'student_id' => $student->id
                ]);
            }
        }
    }
}
