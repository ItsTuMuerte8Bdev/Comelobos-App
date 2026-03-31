<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Función para validar credenciales en la Base de Datos
    public function login(Request $request)
    {
        // 1. Recogemos los datos que mandó Jovan desde el formulario
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. El momento de la verdad: Consultamos a MySQL
        if (Auth::attempt($credentials)) {
            // Si la contraseña es correcta, blindamos la sesión por seguridad
            $request->session()->regenerate();
            
            // Y le abrimos la puerta al inicio
            return redirect()->intended('/index');
        }

        // 3. Si se equivoca en la contraseña, lo regresamos con un error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Tu función de cerrar sesión, ahora más ordenada
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Dejamos esta vacía por el momento para el registro de nuevos usuarios
    public function register(Request $request)
    {
        // Aquí programaremos la inserción de nuevos estudiantes más adelante
    }
}