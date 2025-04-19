<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        
        foreach ($courses as $course) {
            // Create 3-5 lessons for each course
            $lessonCount = rand(3, 5);
            
            for ($i = 1; $i <= $lessonCount; $i++) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => "Lesson {$i} - {$course->title}",
                    'description' => "Description for Lesson {$i} of {$course->title}",
                    'content' => "Content for Lesson {$i} of {$course->title}. This is where the main lesson content would go.",
                    'order' => $i,
                    'is_published' => true
                ]);
            }
        }
    }
} 