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
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'second_last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
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
            'password' => ['required', 'string', 'min:8']
        
        ],[    
            'matriculation_number.required' => 'Ingresa tu Matrícula.',
            'matriculation_number.digits' => 'Debe contener 9 números.',
            'matriculation_number.unique' => 'Esta matrícula ya está registrada.',

            'first_name.required' => 'Nombre es obligatorio.',
            'first_name.regex' => 'Formato no válido.',

            'last_name.required' => 'Primer Apellido es obligatorio.',
            'last_name.regex' => 'Formato no válido.',

            'second_last_name.required' => 'Segundo Apellido es obligatorio.',
            'second_last_name.regex' => 'Formato no válido.',

            'phone.required' => 'Ingresa tu Teléfono.',
            'phone.digits' => 'Debe tener 10 dígitos.',
            'phone.unique' => 'Este teléfono ya está registrado.',

            'email.required' => 'Correo Institucional es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',

            'password.required' => 'Contraseña es obligatoria.',
            'password.min' => 'Debe contener mínimo 8 caracteres.'
        ]);
        
        $validated['first_name'] = mb_convert_case($validated['first_name'], MB_CASE_TITLE, "UTF-8");
        $validated['last_name'] = mb_convert_case($validated['last_name'], MB_CASE_TITLE, "UTF-8");
        $validated['second_last_name'] = mb_convert_case($validated['second_last_name'], MB_CASE_TITLE, "UTF-8");
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'cliente';



        $user = User::create($validated);

        Auth::login($user); // Iniciar su sesión de inmediato
        return redirect()->intended('/inicio');
    }
}