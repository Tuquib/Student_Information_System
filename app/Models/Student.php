<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'course',
        'year',
        'gender',
        'profile_image',
        'status',
        'user_id'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function updateEnrollmentStatus()
    {
        // Force a fresh count of enrollments from the database
        $this->loadCount('enrollments');
        
        // Update status based on enrollments count
        $this->update([
            'status' => $this->enrollments_count > 0 ? 'Enrolled' : 'Unenrolled'
        ]);
    }

    public function getTotalUnitsAttribute()
    {
        return $this->enrollments()
            ->whereHas('subject')
            ->with('subject')
            ->get()
            ->sum(function ($enrollment) {
                return $enrollment->subject->units;
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auth()
    {
        return $this->hasOne(StudentAuth::class);
    }
}
