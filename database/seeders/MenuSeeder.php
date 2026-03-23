<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder {

    public function run(): void {
        Menu::updateOrCreate(
            ['menu_date' => now()->toDateString()],
            [
                'description' => 'Huevo con jamón, frijoles, tortilla y café con leche',
                'price' => 65.00,
                'available_portions' => 120,
                'status' => 'available',
            ]
        );
    }

}
