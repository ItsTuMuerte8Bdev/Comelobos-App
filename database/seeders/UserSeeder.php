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
                'matriculation_number' => '202300001',
                'first_name' => 'Administrador',
                'last_name' => 'Principal',
                'second_last_name' => 'Del Sistema',
                'phone' => '2221000001',
                'password' => Hash::make('password123'),
                'role' => 'administrativo',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cliente@comelobos.test'],
            [
                'matriculation_number' => '202300002',
                'first_name' => 'Pedro Ramón',
                'last_name' => 'Gómez',
                'second_last_name' => 'Bonilla',
                'phone' => '2227543576',
                'password' => Hash::make('password123'),
                'role' => 'cliente',

            ]
        );
    }
}
