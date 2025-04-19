<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultFactory extends Factory
{
    public function definition(): array
    {
        $quiz = Quiz::factory()->create();
        $questions = $quiz->questions;
        $answers = [];
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $answer = fake()->numberBetween(0, 3);
            $answers[] = $answer;
            if ($answer === $question->correct_answer) {
                $correctAnswers++;
            }
        }

        $score = ($correctAnswers / count($questions)) * 100;

        return [
            'user_id' => User::factory()->student(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'answers' => $answers,
        ];
    }
} 