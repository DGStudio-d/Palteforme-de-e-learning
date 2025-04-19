<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = Quiz::all();
        
        foreach ($quizzes as $quiz) {
            // Create 5-10 questions for each quiz
            $questionCount = rand(5, 10);
            
            for ($i = 1; $i <= $questionCount; $i++) {
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => "Question {$i} for {$quiz->title}",
                    'question_type' => 'multiple_choice',
                    'points' => 1,
                    'order' => $i
                ]);
                
                // Create 4 options for each question
                $options = [];
                $correctOption = rand(0, 3);
                
                for ($j = 0; $j < 4; $j++) {
                    $question->options()->create([
                        'option_text' => "Option " . ($j + 1),
                        'is_correct' => $j === $correctOption
                    ]);
                }
            }
        }
    }
} 