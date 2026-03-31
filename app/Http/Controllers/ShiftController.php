<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShiftController extends Controller {
    public function index()
    {
        $shifts = Shift::with([
            'reservations',
            'consumptionLogs'
        ])->get();

        return response()->json($shifts);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'shift_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'max_capacity' => ['required', 'integer', 'min:1'],
            'available_capacity' => ['required', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in([
                'open',
                'full',
                'closed'
            ])]
        ]);

        if ($validated['end_time'] <= $validated['start_time']) {
            return response()->json([
                'message' => 'La hora de fin debe ser mayor que la hora de inicio'
            ], 422);
        }

        if ($validated['available_capacity'] > $validated['max_capacity']) {
            return response()->json([
                'message' => 'La capacidad disponible no puede ser mayor que la capacidad máxima'
            ], 422);
        }

        $exists = Shift::where('shift_date', $validated['shift_date'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya existe un turno con esa fecha y rango de horario'
            ], 422);
        }

        if ($validated['available_capacity'] == 0) {
            $validated['status'] = 'full';
        } else {
            $validated['status'] = 'open';
        }

        $shift = Shift::create($validated);

        return response()->json([
            'message' => 'Turno creado correctamente',
            'data' => $shift
        ], 201);
    }

    public function show(string $id) {
        $shift = Shift::with([
            'reservations',
            'consumptionLogs'
        ])->findOrFail($id);

        return response()->json($shift);
    }

    public function update(Request $request, string $id) {
        $shift = Shift::with([
            'reservations',
            'consumptionLogs'
        ])->findOrFail($id);

        $validated = $request->validate([
            'shift_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'max_capacity' => ['required', 'integer', 'min:1'],
            'available_capacity' => ['required', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in([
                'open',
                'full',
                'closed'
            ])]
        ]);

        if ($validated['end_time'] <= $validated['start_time']) {
            return response()->json([
                'message' => 'La hora de fin debe ser mayor que la hora de inicio'
            ], 422);
        }

        if ($validated['available_capacity'] > $validated['max_capacity']) {
            return response()->json([
                'message' => 'La capacidad disponible no puede ser mayor que la capacidad máxima'
            ], 422);
        }

        $reservedCount = $shift->reservations()->count();

        if ($validated['max_capacity'] < $reservedCount) {
            return response()->json([
                'message' => 'La capacidad máxima no puede ser menor que las reservaciones existentes'
            ], 422);
        }

        if ($validated['available_capacity'] < 0) {
            return response()->json([
                'message' => 'La capacidad disponible no puede ser negativa'
            ], 422);
        }

        if ($validated['available_capacity'] < ($reservedCount > 0 ? 0 : $validated['available_capacity'])) {
            return response()->json([
                'message' => 'La capacidad disponible es inválida'
            ], 422);
        }

        if ($shift->reservations()->exists() && $validated['shift_date'] !== $shift->shift_date) {
            return response()->json([
                'message' => 'No se puede cambiar la fecha de un turno que ya tiene reservaciones'
            ], 422);
        }

        $exists = Shift::where('shift_date', $validated['shift_date'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->where('id', '!=', $shift->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya existe un turno con esa fecha y rango de horario'
            ], 422);
        }

        if ($validated['available_capacity'] == 0) {
            $validated['status'] = 'full';
        } else {
            $validated['status'] = 'open';
        }

        $shift->update($validated);

        return response()->json([
            'message' => 'Turno actualizado correctamente',
            'data' => $shift->fresh()->load([
                'reservations',
                'consumptionLogs'
            ])
        ]);
    }

    public function destroy(string $id) {
        $shift = Shift::with([
            'reservations',
            'consumptionLogs'
        ])->findOrFail($id);

        if ($shift->reservations()->exists() || $shift->consumptionLogs()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar un turno que ya tiene reservaciones o consumos asociados'
            ], 422);
        }

        $shift->delete();

        return response()->json([
            'message' => 'Turno eliminado correctamente'
        ]);
    }
}
