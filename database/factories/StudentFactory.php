<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = [
            'BSIT',
            'BSCS',
            'BSCE',
            'BSAT',
        ];

        $year = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        return [
            'student_id' => $this->faker->unique()->numerify('####-####'),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'course' => $this->faker->randomElement($courses),
            'year' => $this->faker->randomElement($year),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'profile_image' => null
        ];
    }
}
