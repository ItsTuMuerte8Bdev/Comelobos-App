<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Revisamos quién acaba de iniciar sesión
        $user = Auth::user();

        // 2. Si el gafete dice 'administrativo', lo mandamos a su panel
        if ($user->role === 'administrativo') {
            return view('admin.dashboard'); // Ajusta esta vista a como la haya nombrado Jovan
        }

        // 3. Si el gafete dice 'cliente', lo mandamos a ver el menú y reservar
        if ($user->role === 'cliente') {
            return view('index');
        }

        // Por seguridad, si el rol está vacío o mal escrito, lo cerramos
        Auth::logout();
        return redirect('/login')->with('error', 'Rol no reconocido');
    }
}