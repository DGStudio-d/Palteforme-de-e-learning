<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        // Get all students and quizzes
        $students = User::where('role', 'student')->get();
        $quizzes = Quiz::all();

        // For each student, create results for some quizzes
        foreach ($students as $student) {
            // Randomly select 2-3 quizzes for each student
            $studentQuizzes = $quizzes->random(rand(2, 3));

            foreach ($studentQuizzes as $quiz) {
                // Generate a random score between 50 and 100
                $score = rand(5000, 10000) / 100; // This gives us scores like 50.00 to 100.00

                Result::create([
                    'user_id' => $student->id,
                    'quiz_id' => $quiz->id,
                    'score' => $score,
                    'completed_at' => now()->subDays(rand(1, 30)), // Random date within last 30 days
                ]);
            }
        }
    }
} 