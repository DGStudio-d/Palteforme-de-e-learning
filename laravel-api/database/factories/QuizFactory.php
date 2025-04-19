<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'duration' => fake()->numberBetween(15, 60), // Duration in minutes
            'passing_score' => fake()->numberBetween(60, 80),
            'is_published' => fake()->boolean(80), // 80% chance of being published
        ];
    }
} 