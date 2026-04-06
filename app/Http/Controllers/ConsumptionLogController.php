<?php

namespace App\Http\Controllers;

use App\Models\ConsumptionLog;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConsumptionLogController extends Controller {
    public function index() {
        $consumptionLogs = ConsumptionLog::with([
            'reservation',
            'shift',
            'validator',
            'reservation.menu',
            'reservation.user'
        ])->get();

        return response()->json($consumptionLogs);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'reservation_id' => [
                'required',
                'integer',
                'exists:reservations,id',
                'unique:consumption_logs,reservation_id'
            ],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'validated_by' => ['nullable', 'integer', 'exists:users,id'],
            'checked_in_at' => ['required', 'date'],
            'notes' => ['nullable', 'string']
        ]);

        $reservation = Reservation::with([
            'payment',
            'consumptionLog'
        ])->findOrFail($validated['reservation_id']);

        if ($reservation->consumptionLog) {
            return response()->json([
                'message' => 'La reservación ya tiene un consumo registrado'
            ], 422);
        }

        if ((int) $reservation->shift_id !== (int) $validated['shift_id']) {
            return response()->json([
                'message' => 'El shift_id no coincide con la reservación'
            ], 422);
        }

        if ($reservation->status === 'cancelled') {
            return response()->json([
                'message' => 'No se puede registrar consumo para una reservación cancelada'
            ], 422);
        }

        if ($reservation->status === 'consumed') {
            return response()->json([
                'message' => 'La reservación ya fue consumida'
            ], 422);
        }

        if ($reservation->status !== 'paid') {
            return response()->json([
                'message' => 'Solo se puede registrar consumo para reservaciones pagadas'
            ], 422);
        }

        if (!$reservation->payment || $reservation->payment->payment_status !== 'paid') {
            return response()->json([
                'message' => 'La reservación no tiene un pago aprobado'
            ], 422);
        }

        $consumptionLog = null;

        DB::transaction(function () use ($validated, $reservation, &$consumptionLog) {
            $consumptionLog = ConsumptionLog::create($validated);

            $reservation->update([
                'status' => 'consumed'
            ]);
        });

        return response()->json([
            'message' => 'Consumo registrado correctamente',
            'data' => $consumptionLog->load([
                'reservation',
                'shift',
                'validator',
                'reservation.menu',
                'reservation.user'
            ])
        ], 201);
    }

    public function show(string $id) {
        $consumptionLog = ConsumptionLog::with([
            'reservation',
            'shift',
            'validator',
            'reservation.menu',
            'reservation.user'
        ])->findOrFail($id);

        return response()->json($consumptionLog);
    }

    public function update(Request $request, string $id) {
        $consumptionLog = ConsumptionLog::with([
            'reservation',
            'reservation.payment'
        ])->findOrFail($id);

        $validated = $request->validate([
            'reservation_id' => [
                'required',
                'integer',
                'exists:reservations,id',
                Rule::unique('consumption_logs', 'reservation_id')->ignore($consumptionLog->id)
            ],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'validated_by' => ['nullable', 'integer', 'exists:users,id'],
            'checked_in_at' => ['required', 'date'],
            'notes' => ['nullable', 'string']
        ]);

        $newReservation = Reservation::with([
            'payment',
            'consumptionLog'
        ])->findOrFail($validated['reservation_id']);

        if ((int) $newReservation->id !== (int) $consumptionLog->reservation_id && $newReservation->consumptionLog) {
            return response()->json([
                'message' => 'La nueva reservación ya tiene un consumo registrado'
            ], 422);
        }

        if ((int) $newReservation->shift_id !== (int) $validated['shift_id']) {
            return response()->json([
                'message' => 'El shift_id no coincide con la reservación'
            ], 422);
        }

        if ($newReservation->status === 'cancelled') {
            return response()->json([
                'message' => 'No se puede registrar consumo para una reservación cancelada'
            ], 422);
        }

        if (!$newReservation->payment || $newReservation->payment->payment_status !== 'paid') {
            return response()->json([
                'message' => 'La reservación no tiene un pago aprobado'
            ], 422);
        }

        DB::transaction(function () use ($consumptionLog, $validated, $newReservation) {
            $oldReservation = Reservation::findOrFail($consumptionLog->reservation_id);

            $consumptionLog->update($validated);

            if ((int) $oldReservation->id !== (int) $newReservation->id) {
                $oldReservation->update([
                    'status' => 'paid'
                ]);
            }

            $newReservation->update([
                'status' => 'consumed'
            ]);
        });

        return response()->json([
            'message' => 'Registro de consumo actualizado correctamente',
            'data' => $consumptionLog->fresh()->load([
                'reservation',
                'shift',
                'validator',
                'reservation.menu',
                'reservation.user'
            ])
        ]);
    }

    public function destroy(string $id) {
        $consumptionLog = ConsumptionLog::findOrFail($id);

        DB::transaction(function () use ($consumptionLog) {
            $reservation = Reservation::findOrFail($consumptionLog->reservation_id);

            $consumptionLog->delete();

            $reservation->update([
                'status' => 'paid'
            ]);
        });

        return response()->json([
            'message' => 'Registro de consumo eliminado correctamente'
        ]);
    }
}
