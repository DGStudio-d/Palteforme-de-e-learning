<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing courses
        Course::truncate();

        $teachers = User::where('role', 'teacher')->get();
        
        if ($teachers->isEmpty()) {
            $this->command->info('No teachers found. Skipping course seeding.');
            return;
        }

        $courses = [
            [
                'title' => 'Web Development Fundamentals',
                'description' => 'Learn the basics of web development including HTML, CSS, and JavaScript.',
                'teacher_id' => $teachers->first()->id,
                'is_public' => true
            ],
            [
                'title' => 'Advanced JavaScript',
                'description' => 'Deep dive into modern JavaScript concepts and frameworks.',
                'teacher_id' => $teachers->first()->id,
                'is_public' => true
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        // Enroll some students in courses
        $students = User::where('role', 'student')->get();
        $courses = Course::all();

        foreach ($students as $student) {
            // Each student will be enrolled in 1-2 random courses
            $enrollmentCount = rand(1, min(2, $courses->count()));
            $randomCourses = $courses->random($enrollmentCount);
            
            foreach ($randomCourses as $course) {
                $student->courses()->attach($course->id);
            }
        }
    }
} 