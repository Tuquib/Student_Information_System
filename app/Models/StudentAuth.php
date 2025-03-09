<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StudentAuth extends Authenticatable
{
    use Notifiable;

    protected $table = 'student_auths';

    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id'  // Foreign key to students table
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}