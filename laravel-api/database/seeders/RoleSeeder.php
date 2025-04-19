<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing roles
        DB::table('roles')->truncate();

        // Create roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'teacher', 'description' => 'Teacher who can create and manage courses'],
            ['name' => 'student', 'description' => 'Student who can enroll in courses']
        ];

        DB::table('roles')->insert($roles);
    }
} 