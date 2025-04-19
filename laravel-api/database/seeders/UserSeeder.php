<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing users
        DB::table('users')->truncate();

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create teacher users
        $teachers = [
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher'
            ]
        ];

        foreach ($teachers as $teacher) {
            User::create($teacher);
        }

        // Create student users
        $students = [
            [
                'name' => 'Alice Williams',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'role' => 'student'
            ],
            [
                'name' => 'Bob Davis',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'role' => 'student'
            ],
            [
                'name' => 'Charlie Wilson',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'role' => 'student'
            ],
            [
                'name' => 'Diana Miller',
                'email' => 'diana@example.com',
                'password' => Hash::make('password'),
                'role' => 'student'
            ],
            [
                'name' => 'Ethan Taylor',
                'email' => 'ethan@example.com',
                'password' => Hash::make('password'),
                'role' => 'student'
            ]
        ];

        foreach ($students as $student) {
            User::create($student);
        }
    }
} 