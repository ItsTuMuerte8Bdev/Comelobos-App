<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Desayuno para hoy
        Menu::create([
            'type' => 'desayuno',
            'menu_date' => Carbon::now()->toDateString(),
            'entrada' => 'Fruta Picada Con Yogurt',
            'platillo_principal' => 'Chilaquiles Verdes Con Pollo',
            'bebida' => 'Jugo De Naranja',
            'description' => 'Los chilaquiles no pican mucho. contiene lácteos.',
            'image_path' => 'https://img77.uenicdn.com/image/upload/v1563182268/service_images/shutterstock_656809333.jpg',
            'price' => 35.00,
            'available_portions' => 600,
            'status' => 'available',
        ]);

        // Comida para hoy
        Menu::create([
            'type' => 'comida',
            'menu_date' => Carbon::now()->toDateString(),
            'entrada' => 'Sopa De Lentejas',
            'platillo_principal' => 'Milanesa de Res',
            'bebida' => 'Agua De Jamaica',
            'description' => 'El puré contiene mantequilla.',
            'image_path' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR3lPtUAzyJ7Mz_yIT8hvIM8befy1HYfcuA5Q&s',
            'price' => 35.00,
            'available_portions' => 600,
            'status' => 'available',
        ]);
    }
}