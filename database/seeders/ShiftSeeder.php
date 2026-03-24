<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder {

    public function run(): void {
        $today = now()->toDateString();

        Shift::updateOrCreate(
            [
                'shift_date' => $today,
                'start_time' => '12:00:00',
                'end_time' => '12:30:00',
            ],
            [
                'max_capacity' => 50,
                'available_capacity' => 50,
                'status' => 'open',
            ]
        );

        Shift::updateOrCreate(
            [
                'shift_date' => $today,
                'start_time' => '12:30:00',
                'end_time' => '13:00:00',
            ],
            [
                'max_capacity' => 50,
                'available_capacity' => 50,
                'status' => 'open',
            ]
        );

        Shift::updateOrCreate(
            [
                'shift_date' => $today,
                'start_time' => '13:00:00',
                'end_time' => '13:30:00',
            ],
            [
                'max_capacity' => 50,
                'available_capacity' => 50,
                'status' => 'open',
            ]
        );
    }

}
