<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //  Administrativo
        User::create([
            'matriculation_number' => '202324071',
            'first_name' => 'Yahir',
            'last_name' => 'Flores',
            'second_last_name' => 'Vera',
            'phone' => '2220000000',
            'email' => 'fv202324071@alm.buap.mx',
            'password' => Hash::make('password123'),
            'role' => 'administrativo',
            'credits' => 0.00
        ]);

        //  Cliente
        User::create([
            'matriculation_number' => '202300001',
            'first_name' => 'Marta',
            'last_name' => 'Benitez',
            'second_last_name' => 'Guzman',
            'phone' => '2225267634',
            'email' => 'marta@correo.buap.mx',
            'password' => Hash::make('password123'),
            'role' => 'cliente',
            'credits' => 180.00
        ]);
    }
}