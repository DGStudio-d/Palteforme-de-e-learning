<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        
        foreach ($courses as $course) {
            // Create 2-3 quizzes for each course
            $quizCount = rand(2, 3);
            
            for ($i = 1; $i <= $quizCount; $i++) {
                Quiz::create([
                    'title' => "{$course->title} Quiz {$i}",
                    'description' => "Test your knowledge of {$course->title} concepts",
                    'course_id' => $course->id,
                    'duration' => rand(15, 30), // Duration in minutes
                    'passing_score' => 70,
                    'is_published' => true
                ]);
            }
        }
    }

    private function createQuiz(Course $course, array $quizData): void
    {
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => $quizData['title'],
            'duration_minutes' => $quizData['duration_minutes'],
            'is_active' => $quizData['is_active'],
        ]);

        foreach ($quizData['questions'] as $questionData) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question_text'],
                'options' => $questionData['options'],
                'correct_answer' => $questionData['correct_answer'],
            ]);
        }
    }
} 