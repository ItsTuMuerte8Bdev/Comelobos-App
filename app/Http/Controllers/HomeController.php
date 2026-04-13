<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Shift;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'administrativo') {
            return view('admin.index');
        }

        if ($user->role === 'cliente') {
            $hoy = Carbon::now()->toDateString();
            $userId = Auth::id();


            $menuDesayuno = Menu::where('menu_date', $hoy)->where('type', 'desayuno')->where('status', 'available')->first();
            $menuComida = Menu::where('menu_date', $hoy)->where('type', 'comida')->where('status', 'available')->first();
            $turnos = Shift::where('shift_date', $hoy)->where('status', 'open')->orderBy('start_time')->get();
            $turnosDesayuno = $turnos->filter(fn($t) => $t->start_time < '13:00:00');
            $turnosComida = $turnos->filter(fn($t) => $t->start_time >= '13:00:00');


            $reservaDesayuno = Reservation::where('user_id', $userId)
                ->where('reservation_date', $hoy)
                ->whereHas('menu', fn($q) => $q->where('type', 'desayuno'))
                ->whereIn('status', ['paid', 'consumed'])
                ->first();

            $reservaComida = Reservation::where('user_id', $userId)
                ->where('reservation_date', $hoy)
                ->whereHas('menu', fn($q) => $q->where('type', 'comida'))
                ->whereIn('status', ['paid', 'consumed'])
                ->first();

            return view('index', compact(
                'menuDesayuno', 
                'menuComida', 
                'turnosDesayuno', 
                'turnosComida', 
                'hoy',
                'reservaDesayuno',
                'reservaComida'
            ));
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Rol no reconocido o acceso denegado.');
    }
}