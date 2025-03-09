<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class SyncStudentEnrollmentStatus extends Command
{
    protected $signature = 'students:sync-status';
    protected $description = 'Sync enrollment status for all students';

    public function handle()
    {
        Student::chunk(100, function($students) {
            foreach($students as $student) {
                $student->updateEnrollmentStatus();
            }
        });

        $this->info('Student enrollment statuses have been synchronized.');
    }
} 