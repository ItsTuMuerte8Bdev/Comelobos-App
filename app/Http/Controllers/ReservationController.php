<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'reservation_date' => ['required', 'date']
        ]);

        $user = Auth::user(); // Obtenemos al alumno que está comprando
        $validated['user_id'] = $user->id;
        
        $menu = Menu::findOrFail($validated['menu_id']);
        $shift = Shift::findOrFail($validated['shift_id']);

        // REGLA 1: Verificar si ya tiene una reserva de ESE TIPO hoy
        $existingReservation = Reservation::where('user_id', $user->id)
            ->where('reservation_date', $validated['reservation_date'])
            ->where('status', '!=', 'cancelled')
            ->whereHas('menu', function($q) use ($menu) {
                $q->where('type', $menu->type); // Bloquea si intenta pedir 2 desayunos
            })
            ->exists();

        if ($existingReservation) {
            return back()->withErrors('Ya tienes un ' . ucfirst($menu->type) . ' reservado para este día. (Máximo 1 por alumno)');
        }

        // REGLA 2: Verificar que el horario y la comida tengan lugares
        if ($shift->available_capacity <= 0 || $menu->available_portions <= 0) {
            return back()->withErrors('Lo sentimos, ya no hay cupo o porciones disponibles para este horario.');
        }

        // REGLA 3: LA BILLETERA (¡La magia de los créditos!)
        if ($user->credits < $menu->price) {
            return back()->withErrors('Créditos Insuficientes. Tu saldo es de $' . number_format($user->credits, 2) . ' y el menú cuesta $' . number_format($menu->price, 2));
        }

        // Preparamos los datos del Boleto
        $validated['folio'] = 'RES-' . strtoupper(Str::random(8));
        $validated['qr_code'] = 'QR-' . $validated['folio'];
        $validated['status'] = 'paid'; // ¡La reserva nace pagada!

        // EJECUTAR TRANSACCIÓN SEGURA (Si algo falla, no se cobra)
        DB::transaction(function () use ($validated, $shift, $menu, $user) {
            
            // 1. LE COBRAMOS AL ALUMNO
            $user->credits -= $menu->price;
            $user->save();

            // 2. CREAMOS LA RESERVA
            Reservation::create($validated);

            // 3. RESTAMOS INVENTARIO (Cupo y Comida)
            $shift->decrement('available_capacity');
            if ($shift->available_capacity == 0) $shift->update(['status' => 'full']);

            $menu->decrement('available_portions');
            if ($menu->available_portions == 0) $menu->update(['status' => 'unavailable']);
        });

        // Regresamos a la pantalla de Inicio (donde la tarjeta se volverá amarilla)
        return redirect()->route('inicio');
    }

    public function cancel(Request $request, $id) {
        $user = Auth::user();
        
        // Buscamos la reserva, asegurándonos de que sea de este usuario
        $reservation = Reservation::with(['menu', 'shift'])->where('user_id', $user->id)->findOrFail($id);

        if ($reservation->status !== 'paid') {
            return back()->withErrors('Esta reserva ya fue cancelada o consumida.');
        }

        // REGLA DEL TIEMPO: Validar los 30 minutos
        // Unimos la fecha del menú con la hora del turno para tener el momento exacto
        $fechaHoraTurno = \Carbon\Carbon::parse($reservation->reservation_date . ' ' . $reservation->shift->start_time);
        $limiteCancelacion = $fechaHoraTurno->copy()->subMinutes(30);

        if (\Carbon\Carbon::now()->greaterThan($limiteCancelacion)) {
            return back()->withErrors('Ya no puedes cancelar. Faltan menos de 30 minutos para el inicio de tu servicio.');
        }

        // EJECUTAMOS EL REEMBOLSO (Todo o Nada)
        DB::transaction(function () use ($reservation, $user) {
            // 1. Matamos la reserva
            $reservation->update(['status' => 'cancelled']);

            // 2. Le devolvemos su dinero
            $user->credits += $reservation->menu->price;
            $user->save();

            // 3. Devolvemos el plato a la olla
            $reservation->menu->increment('available_portions');
            if ($reservation->menu->status === 'unavailable') {
                $reservation->menu->update(['status' => 'available']);
            }

            // 4. Devolvemos la silla a la mesa (Cupo)
            $reservation->shift->increment('available_capacity');
            if ($reservation->shift->status === 'full') {
                $reservation->shift->update(['status' => 'open']);
            }
        });

        return back()->with('success', '¡Reserva cancelada! Tus ' . number_format($reservation->menu->price, 0) . ' pts han sido devueltos a tu cuenta.');
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
