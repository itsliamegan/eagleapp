<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    /**
     * Get the Days that this Period occurs on.
     *
     * @return Day[]
     */
    public function days()
    {
        return $this->belongsToMany(Day::class, 'day_periods');
    }

    public static function cpt()
    {
        return self::query()
            ->where('name', 'cpt')
            ->first();
    }

    public static function adv()
    {
        return self::query()
            ->where('name', 'adv')
            ->first();
    }
}
