<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $subjects = [
        'ENG101' => 'English Communication Skills',
        'MATH101' => 'College Algebra',
        'CS101' => 'Introduction to Programming',
        'SCI101' => 'General Physics',
        'SOC101' => 'Philippine History',
        'MATH102' => 'Trigonometry',
        'CS102' => 'Database Management',
        'ENG102' => 'Technical Writing',
        'SCI102' => 'Chemistry',
        'SOC102' => 'General Psychology'
    ];

    public function definition(): array
    {
        // Get a random subject code and name
        $code = $this->faker->unique()->randomElement(array_keys($this->subjects));
        $name = $this->subjects[$code];

        return [
            'code' => $code,
            'name' => $name,
            'units' => $this->faker->randomElement([2, 3, 4, 5]), // Most college subjects are 2-5 units
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
} 