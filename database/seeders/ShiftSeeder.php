<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $hoy = Carbon::now()->toDateString();

        // Turnos Desayuno
        $desayunos = [
            ['start' => '09:00:00', 'end' => '10:00:00'],
            ['start' => '10:00:00', 'end' => '11:00:00'],
            ['start' => '11:00:00', 'end' => '12:00:00'],
            ['start' => '12:00:00', 'end' => '13:00:00']
        ];

        foreach ($desayunos as $turno) {
            Shift::create([
                'shift_date' => $hoy,
                'start_time' => $turno['start'],
                'end_time' => $turno['end'],
                'max_capacity' => 150,
                'available_capacity' => 150,
                'status' => 'open',
            ]);
        }

        // Turnos Comida
        $comidas = [
            ['start' => '13:00:00', 'end' => '14:00:00'],
            ['start' => '14:00:00', 'end' => '15:00:00'],
            ['start' => '15:00:00', 'end' => '16:00:00'],
            ['start' => '16:00:00', 'end' => '17:00:00'],
            ['start' => '17:00:00', 'end' => '18:00:00']
        ];

        foreach ($comidas as $turno) {
            Shift::create([
                'shift_date' => $hoy,
                'start_time' => $turno['start'],
                'end_time' => $turno['end'],
                'max_capacity' => 120,
                'available_capacity' => 120,
                'status' => 'open',
            ]);
        }
    }
}