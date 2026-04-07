<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PageController extends Controller
{
    public function index() {
        // Si hay un usuario autenticado, usar su nombre completo.
        $user = Auth::user();

        if ($user) {
            $nombreUsuario = $user->full_name;
        } else {
            // Respaldo: tomar el primer usuario disponible en la tabla `users`.
            $fallback = User::first();
            $nombreUsuario = $fallback ? $fallback->full_name : 'Usuario';
        }

        return view('index', compact('nombreUsuario'));

    }
}
