<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \DateTimeImmutable;

class Vacation extends Model
{
    //------------------------------------------------------
    // Additional Methods
    //------------------------------------------------------
    
    /**
     * Determine if there is a vacation on the given date.
     *
     * @param DateTimeImmutable $date
     * @return bool
     */
    public static function exists($date)
    {
        return self::query()
            ->where('date', $date)
            ->exists();
    }
}
