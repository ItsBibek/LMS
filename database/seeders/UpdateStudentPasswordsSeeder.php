<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateStudentPasswordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Updates all students with null passwords to have default password "Academia123"
     */
    public function run(): void
    {
        $updatedCount = User::whereNull('password')
            ->orWhere('password', '')
            ->update([
                'password' => Hash::make('Academia123')
            ]);

        $this->command->info("Updated {$updatedCount} student(s) with default password 'Academia123'");
    }
}
