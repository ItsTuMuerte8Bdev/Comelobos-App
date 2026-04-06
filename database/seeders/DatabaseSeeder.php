<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    public function run(): void {
        $this->call([
            UserSeeder::class,
            ShiftSeeder::class,
            MenuSeeder::class,
            ReservationSeeder::class,
            PaymentSeeder::class,
            ConsumptionLogSeeder::class,
        ]);
    }

}
