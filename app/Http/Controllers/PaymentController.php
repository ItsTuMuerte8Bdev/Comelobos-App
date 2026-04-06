<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller {
    public function index() {
        $payments = Payment::with([
            'reservation',
            'reservation.user',
            'reservation.shift',
            'reservation.menu'
        ])->get();

        return response()->json($payments);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'reservation_id' => [
                'required',
                'integer',
                'exists:reservations,id',
                'unique:payments,reservation_id'
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
            'payment_folio' => ['required', 'string', 'max:50', 'unique:payments,payment_folio'],
            'payment_method' => ['required', Rule::in([
                'cash',
                'card',
                'transfer',
                'qr'
            ])],
            'payment_status' => ['required', Rule::in([
                'pending',
                'paid',
                'rejected',
                'cancelled'
            ])],
            'external_reference' => ['nullable', 'string', 'max:255']
        ]);

        $reservation = Reservation::with(['menu', 'payment', 'consumptionLog'])->findOrFail($validated['reservation_id']);

        if ($reservation->payment) {
            return response()->json([
                'message' => 'La reservación ya tiene un pago registrado'
            ], 422);
        }

        if (in_array($reservation->status, ['cancelled', 'consumed'])) {
            return response()->json([
                'message' => 'No se puede registrar pago para una reservación cancelada o consumida'
            ], 422);
        }

        if ((float) $validated['amount'] !== (float) $reservation->menu->price) {
            return response()->json([
                'message' => 'El monto del pago no coincide con el precio del menú'
            ], 422);
        }

        if ($validated['payment_status'] === 'paid' && empty($validated['paid_at'])) {
            $validated['paid_at'] = now();
        }

        if ($validated['payment_status'] !== 'paid') {
            $validated['paid_at'] = null;
        }

        $payment = null;

        DB::transaction(function () use ($validated, $reservation, &$payment) {
            $payment = Payment::create($validated);

            if ($payment->payment_status === 'paid') {
                $reservation->update([
                    'status' => 'paid'
                ]);
            } else {
                $reservation->update([
                    'status' => 'pending_payment'
                ]);
            }
        });

        return response()->json([
            'message' => 'Pago creado correctamente',
            'data' => $payment->load([
                'reservation',
                'reservation.user',
                'reservation.shift',
                'reservation.menu'
            ])
        ], 201);
    }

    public function show(string $id) {
        $payment = Payment::with([
            'reservation',
            'reservation.user',
            'reservation.shift',
            'reservation.menu'
        ])->findOrFail($id);

        return response()->json($payment);
    }

    public function update(Request $request, string $id) {
        $payment = Payment::with([
            'reservation',
            'reservation.menu',
            'reservation.consumptionLog'
        ])->findOrFail($id);

        $validated = $request->validate([
            'reservation_id' => [
                'required',
                'integer',
                'exists:reservations,id',
                Rule::unique('payments', 'reservation_id')->ignore($payment->id)
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
            'payment_folio' => [
                'required',
                'string',
                'max:50',
                Rule::unique('payments', 'payment_folio')->ignore($payment->id)
            ],
            'payment_method' => ['required', Rule::in([
                'cash',
                'card',
                'transfer',
                'qr'
            ])],
            'payment_status' => ['required', Rule::in([
                'pending',
                'paid',
                'rejected',
                'cancelled'
            ])],
            'external_reference' => ['nullable', 'string', 'max:255']
        ]);

        if ($payment->reservation->status === 'consumed' || $payment->reservation->consumptionLog) {
            return response()->json([
                'message' => 'No se puede modificar el pago de una reservación consumida'
            ], 422);
        }

        $newReservation = Reservation::with(['menu', 'payment', 'consumptionLog'])->findOrFail($validated['reservation_id']);

        if ((int) $newReservation->id !== (int) $payment->reservation_id && $newReservation->payment) {
            return response()->json([
                'message' => 'La nueva reservación ya tiene un pago registrado'
            ], 422);
        }

        if (in_array($newReservation->status, ['cancelled', 'consumed'])) {
            return response()->json([
                'message' => 'No se puede asociar el pago a una reservación cancelada o consumida'
            ], 422);
        }

        if ((float) $validated['amount'] !== (float) $newReservation->menu->price) {
            return response()->json([
                'message' => 'El monto del pago no coincide con el precio del menú'
            ], 422);
        }

        if ($validated['payment_status'] === 'paid' && empty($validated['paid_at'])) {
            $validated['paid_at'] = now();
        }

        if ($validated['payment_status'] !== 'paid') {
            $validated['paid_at'] = null;
        }

        DB::transaction(function () use ($payment, $validated, $newReservation) {
            $oldReservation = Reservation::findOrFail($payment->reservation_id);

            $payment->update($validated);

            if ((int) $oldReservation->id !== (int) $newReservation->id) {
                $oldReservation->update([
                    'status' => 'pending_payment'
                ]);
            }

            if ($payment->payment_status === 'paid') {
                $newReservation->update([
                    'status' => 'paid'
                ]);
            } else {
                $newReservation->update([
                    'status' => 'pending_payment'
                ]);
            }
        });

        return response()->json([
            'message' => 'Pago actualizado correctamente',
            'data' => $payment->fresh()->load([
                'reservation',
                'reservation.user',
                'reservation.shift',
                'reservation.menu'
            ])
        ]);
    }

    public function destroy(string $id) {
        $payment = Payment::with([
            'reservation',
            'reservation.consumptionLog'
        ])->findOrFail($id);

        if ($payment->reservation->status === 'consumed' || $payment->reservation->consumptionLog) {
            return response()->json([
                'message' => 'No se puede eliminar el pago de una reservación consumida'
            ], 422);
        }

        DB::transaction(function () use ($payment) {
            $reservation = Reservation::findOrFail($payment->reservation_id);

            $payment->delete();

            $reservation->update([
                'status' => 'pending_payment'
            ]);
        });

        return response()->json([
            'message' => 'Pago eliminado correctamente'
        ]);
    }
}
