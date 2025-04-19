<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'content' => fake()->paragraphs(3, true),
            'order' => fake()->numberBetween(1, 10),
            'is_published' => fake()->boolean(80), // 80% chance of being published
        ];
    }
} 