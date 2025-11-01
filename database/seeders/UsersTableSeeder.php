<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Clear out demo emails if rerun (optional)
        // User::whereIn('email', ['student1@example.com','student2@example.com','student3@example.com'])->delete();

        $users = [
            [
                'name' => 'Student One',
                'student_name' => 'Aarav Sharma',
                'batch_no' => 'BATCH-2025-001',
                'email' => 'student1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Student Two',
                'student_name' => 'Sita Koirala',
                'batch_no' => 'BATCH-2025-002',
                'email' => 'student2@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Student Three',
                'student_name' => 'Bikash Rai',
                'batch_no' => 'BATCH-2025-003',
                'email' => 'student3@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
