<?php

namespace Database\Seeders;

use App\Models\ConsumptionLog;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConsumptionLogSeeder extends Seeder {

    public function run(): void {
        $reservation = Reservation::first();
        $cashier = User::where('role', 'cashier')->first();

        if (!$reservation) {
            return;
        }

        ConsumptionLog::updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'student_id' => $reservation->student_id,
                'shift_id' => $reservation->shift_id,
                'validated_by' => $cashier?->id,
                'checked_in_at' => now(),
                'notes' => 'Initial test consumption log',
            ]
        );
    }

}
