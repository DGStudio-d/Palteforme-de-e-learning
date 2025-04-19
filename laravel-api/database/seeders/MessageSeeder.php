<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();

        // Create messages between teachers and students
        foreach ($teachers as $teacher) {
            foreach ($students as $student) {
                // Create 2-3 messages from teacher to student
                for ($i = 0; $i < rand(2, 3); $i++) {
                    Message::create([
                        'sender_id' => $teacher->id,
                        'receiver_id' => $student->id,
                        'content' => fake()->paragraph(2),
                        'read_at' => fake()->boolean(70) ? now() : null, // 70% chance of being read
                        'created_at' => now()->subDays(rand(1, 30)),
                    ]);
                }

                // Create 1-2 messages from student to teacher
                for ($i = 0; $i < rand(1, 2); $i++) {
                    Message::create([
                        'sender_id' => $student->id,
                        'receiver_id' => $teacher->id,
                        'content' => fake()->paragraph(2),
                        'read_at' => fake()->boolean(80) ? now() : null, // 80% chance of being read
                        'created_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        // Create some messages between students
        foreach ($students as $student1) {
            foreach ($students as $student2) {
                if ($student1->id !== $student2->id) {
                    // Create 1-2 messages between students
                    for ($i = 0; $i < rand(1, 2); $i++) {
                        Message::create([
                            'sender_id' => $student1->id,
                            'receiver_id' => $student2->id,
                            'content' => fake()->paragraph(2),
                            'read_at' => fake()->boolean(60) ? now() : null, // 60% chance of being read
                            'created_at' => now()->subDays(rand(1, 30)),
                        ]);
                    }
                }
            }
        }
    }
} 