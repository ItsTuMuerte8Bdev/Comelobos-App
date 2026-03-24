<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder {

    public function run(): void {
        $reservation = Reservation::first();

        if (!$reservation) {
            return;
        }

        Payment::updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'amount' => 65.00,
                'paid_at' => now(),
                'payment_folio' => 'PAY-0001',
                'payment_method' => 'qr',
                'payment_status' => 'paid',
                'external_reference' => 'TXN-DEMO-0001',
            ]
        );
    }

}
