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
                'matriculation_number' => 'ADMIN001',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'second_last_name' => 'Principal',
                'phone' => '2221000001',
                'password' => Hash::make('password123'),
                'role' => 'administrative',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@comelobos.test'],
            [
                'matriculation_number' => '202320912',
                'first_name' => 'Alfonso Arnulfo',
                'last_name' => 'Cuevas',
                'second_last_name' => 'Reynoso',
                'phone' => '2227543576',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_active' => true,
            ]
        );
    }
}
