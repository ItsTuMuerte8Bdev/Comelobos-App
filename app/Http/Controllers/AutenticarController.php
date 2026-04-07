<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AutenticarController extends Controller
{
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (Auth::attempt($credenciales)) {
            
            // Contraseña Correcta: Blindamos la sesión por seguridad
            $request->session()->regenerate();
            return redirect()->intended('/inicio');
        }

        // Contraseña Incorrecta: Regresar con un error
        return redirect()->route('login')->withErrors([
            'email' => 'Correo o contraseña incorrectos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'matriculation_number' => ['required', 'digits:9', 'unique:users,matriculation_number'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'second_last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'digits:10', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email',
                // Dominios BUAP
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@buap.mx') && 
                        !str_ends_with($value, '@alumno.buap.mx') &&
                        !str_ends_with($value, '@alm.buap.mx') &&
                        !str_ends_with($value, '@correo.buap.mx')) {
                        $fail('Utliza un correo institucional.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8'],
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'cliente';
        $user = User::create($validated);

        Auth::login($user); // Iniciar su sesión de inmediato
        return redirect()->intended('/inicio');
    }
}