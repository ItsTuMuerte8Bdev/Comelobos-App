<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReservationController extends Controller {
    public function index() {
        $reservations = Reservation::with([
            'user',
            'shift',
            'menu',
            'payment',
            'consumptionLog'
        ])->get();

        return response()->json($reservations);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'reservation_date' => ['required', 'date'],
            'folio' => ['required', 'string', 'max:50', 'unique:reservations,folio'],
            'qr_code' => ['required', 'string', 'max:255', 'unique:reservations,qr_code'],
            'status' => ['required', Rule::in([
                'pending_payment',
                'paid',
                'cancelled',
                'consumed'
            ])]
        ]);

        $existingReservation = Reservation::where('user_id', $validated['user_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->exists();

        if ($existingReservation) {
            return response()->json([
                'message' => 'El usuario ya tiene una reservación en esa fecha'
            ], 422);
        }

        $shift = Shift::findOrFail($validated['shift_id']);
        $menu = Menu::findOrFail($validated['menu_id']);

        if ($shift->shift_date !== $validated['reservation_date']) {
            return response()->json([
                'message' => 'La fecha de la reservación debe coincidir con la fecha del turno'
            ], 422);
        }

        if ($menu->menu_date !== $validated['reservation_date']) {
            return response()->json([
                'message' => 'La fecha de la reservación debe coincidir con la fecha del menú'
            ], 422);
        }

        if ($shift->status !== 'open' || $shift->available_capacity <= 0) {
            return response()->json([
                'message' => 'El turno no tiene capacidad disponible'
            ], 422);
        }

        if ($menu->status !== 'available' || $menu->available_portions <= 0) {
            return response()->json([
                'message' => 'El menú no tiene porciones disponibles'
            ], 422);
        }

        $reservation = null;

        DB::transaction(function () use ($validated, $shift, $menu, &$reservation) {
            $validated['status'] = 'pending_payment';

            $reservation = Reservation::create($validated);

            $shift->available_capacity -= 1;
            if ($shift->available_capacity == 0) {
                $shift->status = 'full';
            }
            $shift->save();

            $menu->available_portions -= 1;
            if ($menu->available_portions == 0) {
                $menu->status = 'unavailable';
            }
            $menu->save();
        });

        return response()->json([
            'message' => 'Reservación creada correctamente',
            'data' => $reservation->load([
                'user',
                'shift',
                'menu',
                'payment',
                'consumptionLog'
            ])
        ], 201);
    }

    public function show(string $id) {
        $reservation = Reservation::with([
            'user',
            'shift',
            'menu',
            'payment',
            'consumptionLog'
        ])->findOrFail($id);

        return response()->json($reservation);
    }

    public function update(Request $request, string $id) {
        $reservation = Reservation::with(['payment', 'consumptionLog'])->findOrFail($id);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'reservation_date' => ['required', 'date'],
            'folio' => [
                'required',
                'string',
                'max:50',
                Rule::unique('reservations', 'folio')->ignore($reservation->id)
            ],
            'qr_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('reservations', 'qr_code')->ignore($reservation->id)
            ],
            'status' => ['required', Rule::in([
                'pending_payment',
                'paid',
                'cancelled',
                'consumed'
            ])]
        ]);

        $existingReservation = Reservation::where('user_id', $validated['user_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where('id', '!=', $reservation->id)
            ->exists();

        if ($existingReservation) {
            return response()->json([
                'message' => 'El usuario ya tiene una reservación en esa fecha'
            ], 422);
        }

        if ($reservation->status === 'consumed') {
            return response()->json([
                'message' => 'No se puede modificar una reservación consumida'
            ], 422);
        }

        if ($reservation->consumptionLog) {
            return response()->json([
                'message' => 'No se puede modificar una reservación con consumo registrado'
            ], 422);
        }

        if ($validated['status'] === 'pending_payment' && $reservation->payment && $reservation->payment->payment_status === 'paid') {
            return response()->json([
                'message' => 'No se puede regresar a pending_payment porque la reservación ya tiene un pago aprobado'
            ], 422);
        }

        if ($validated['status'] === 'consumed') {
            return response()->json([
                'message' => 'El estado consumed solo debe establecerse desde el registro de consumo'
            ], 422);
        }

        if ($validated['status'] === 'paid' && (!$reservation->payment || $reservation->payment->payment_status !== 'paid')) {
            return response()->json([
                'message' => 'No se puede marcar como paid sin un pago aprobado'
            ], 422);
        }

        $newShift = Shift::findOrFail($validated['shift_id']);
        $newMenu = Menu::findOrFail($validated['menu_id']);

        if ($newShift->shift_date !== $validated['reservation_date']) {
            return response()->json([
                'message' => 'La fecha de la reservación debe coincidir con la fecha del turno'
            ], 422);
        }

        if ($newMenu->menu_date !== $validated['reservation_date']) {
            return response()->json([
                'message' => 'La fecha de la reservación debe coincidir con la fecha del menú'
            ], 422);
        }

        $shiftChanged = (int) $reservation->shift_id !== (int) $validated['shift_id'];
        $menuChanged = (int) $reservation->menu_id !== (int) $validated['menu_id'];

        if ($shiftChanged) {
            if ($newShift->status !== 'open' || $newShift->available_capacity <= 0) {
                return response()->json([
                    'message' => 'El nuevo turno no tiene capacidad disponible'
                ], 422);
            }
        }

        if ($menuChanged) {
            if ($newMenu->status !== 'available' || $newMenu->available_portions <= 0) {
                return response()->json([
                    'message' => 'El nuevo menú no tiene porciones disponibles'
                ], 422);
            }
        }

        DB::transaction(function () use ($validated, $reservation, $shiftChanged, $menuChanged, $newShift, $newMenu) {
            if ($shiftChanged) {
                $oldShift = Shift::findOrFail($reservation->shift_id);
                $oldShift->available_capacity += 1;
                if ($oldShift->status === 'full' && $oldShift->available_capacity > 0) {
                    $oldShift->status = 'open';
                }
                $oldShift->save();

                $newShift->available_capacity -= 1;
                if ($newShift->available_capacity == 0) {
                    $newShift->status = 'full';
                }
                $newShift->save();
            }

            if ($menuChanged) {
                $oldMenu = Menu::findOrFail($reservation->menu_id);
                $oldMenu->available_portions += 1;
                if ($oldMenu->status === 'unavailable' && $oldMenu->available_portions > 0) {
                    $oldMenu->status = 'available';
                }
                $oldMenu->save();

                $newMenu->available_portions -= 1;
                if ($newMenu->available_portions == 0) {
                    $newMenu->status = 'unavailable';
                }
                $newMenu->save();
            }

            $reservation->update($validated);
        });

        return response()->json([
            'message' => 'Reservación actualizada correctamente',
            'data' => $reservation->fresh()->load([
                'user',
                'shift',
                'menu',
                'payment',
                'consumptionLog'
            ])
        ]);
    }

    public function destroy(string $id) {
        $reservation = Reservation::with(['payment', 'consumptionLog'])->findOrFail($id);

        if ($reservation->status === 'consumed' || $reservation->consumptionLog) {
            return response()->json([
                'message' => 'No se puede eliminar una reservación consumida o con consumo registrado'
            ], 422);
        }

        DB::transaction(function () use ($reservation) {
            $shift = Shift::findOrFail($reservation->shift_id);
            $menu = Menu::findOrFail($reservation->menu_id);

            $shift->available_capacity += 1;
            if ($shift->status === 'full' && $shift->available_capacity > 0) {
                $shift->status = 'open';
            }
            $shift->save();

            $menu->available_portions += 1;
            if ($menu->status === 'unavailable' && $menu->available_portions > 0) {
                $menu->status = 'available';
            }
            $menu->save();

            $reservation->delete();
        });

        return response()->json([
            'message' => 'Reservación eliminada correctamente'
        ]);
    }
}
