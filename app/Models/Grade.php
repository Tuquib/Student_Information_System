<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'grade',
        'remarks'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public static function getGradeOptions()
    {
        return [
            '1.00' => '1.00 (Excellent)',
            '1.25' => '1.25 (Superior)',
            '1.50' => '1.50 (Very Good)',
            '1.75' => '1.75 (Good)',
            '2.00' => '2.00 (Satisfactory)',
            '2.25' => '2.25 (Fair)',
            '2.50' => '2.50 (Passing)',
            '3.00' => '3.00 (Conditional)',
            '5.00' => '5.00 (Failed)',
        ];
    }
}
