<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder {
    public function run(): void {
        $user = User::where('role', 'student')->first();
        $shift = Shift::first();
        $menu = Menu::first();

        if (!$user || !$shift || !$menu) {
            return;
        }

        Reservation::updateOrCreate(
            [
                'user_id' => $user->id,
                'reservation_date' => now()->toDateString(),
            ],
            [
                'shift_id' => $shift->id,
                'menu_id' => $menu->id,
                'folio' => 'RSV-0001',
                'qr_code' => 'QR-RSV-0001',
                'status' => 'paid',
            ]
        );
    }
}
