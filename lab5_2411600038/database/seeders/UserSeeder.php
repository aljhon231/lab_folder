<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@school.local'],
            [
                'name' => 'School Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'student@school.local'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
