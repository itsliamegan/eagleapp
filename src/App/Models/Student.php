<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'profile_image',
        'is_admin'
    ];

    //------------------------------------------------------
    // Relations
    //------------------------------------------------------
    
    /**
     * Get the Courses that the Student is enrolled in.
     * 
     * @return Course[]
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }
}
