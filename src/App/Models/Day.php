<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    //------------------------------------------------------
    // Relations
    //------------------------------------------------------
    
    /**
     * Get the normal periods for the given Day.
     *
     * @return Period[]
     */
    public function periods()
    {
        return $this
            ->belongsToMany(Period::class, 'day_periods')
            ->orderBy('placement');
    }

    /**
     * Get the next Day in the sequence. Loop around to the beginning if this is
     * the last Day in the sequence.
     *
     * @return Day
     */
    public function getNextAttribute()
    {
        $isLastInSequence = self::query()->max('order') === $this->order;

        if ($isLastInSequence) {
            return self::where('order', self::query()->min('order'))->first();
        }

        return self::where('order', $this->order + 1)->first();
    }
}
