<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::updateOrCreate(
            ['email' => 'admin@comelobos.test'],
            [
                'name' => 'System Admin',
                'phone' => '2221000001',
                'password' => Hash::make('password123'),
                'role' => 'administrative',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@comelobos.test'],
            [
                'name' => 'Test Student',
                'phone' => '2221000003',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_active' => true,
            ]
        );
    }
}
