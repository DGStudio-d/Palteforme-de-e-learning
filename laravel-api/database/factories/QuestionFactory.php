<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        $options = [
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
        ];

        return [
            'quiz_id' => Quiz::factory(),
            'question' => fake()->sentence() . '?',
            'options' => $options,
            'correct_answer' => fake()->numberBetween(0, 3),
        ];
    }
} 