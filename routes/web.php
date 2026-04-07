<?php

use App\Models\User;
use App\Http\Controllers\AutenticarController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Interfaz 1: Bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Interfaz 2: Login(Invitado)
Route::middleware('guest')->group(function () {

    // Interfaz 2.1: Email
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Interfaz 2.2: Contraseña
    Route::post('/password', function (Request $request) {
        return view('login_password', ['email' => $request->input('email')]);
    })->name('password');

    // 2.3: Verifica Credenciales
    Route::post('/autenticarLogin', [AutenticarController::class, 'login'])->name('autenticarLogin');

    // Interfaz 2.4: Registrarse
    Route::get('/registro', function () {
        return view('register');
    })->name('registrarse');

    // Interfaz 2.5: Procesar Registro
    Route::post('/autenticarRegistro', [AutenticarController::class, 'register'])->name('autenticarRegistro');

});

// Interfaz 3: Login(Autenticado)
Route::middleware('auth')->group(function () {

    // Interfaz 3.1: Pagina Principal
    Route::get('/inicio', [HomeController::class, 'index'])->name('inicio');

    // Rutas que devuelven vistas directamente para el usuario autenticado.
    Route::get('/cuenta', function () {
        return view('account');
    })->name('cuenta');

    Route::get('/informacion', function () {
        return view('informacion');
    })->name('informacion');

    Route::get('/contactos', function () {
        return view('contactos');
    })->name('contactos');

    Route::get('/ajustes', function () {
        return view('ajustes');
    })->name('ajustes');

    Route::get('/menu', function () {
        return view('menu');
    })->name('menu');

    Route::get('/reservas', function () {
        return view('reservas');
    })->name('reservas');

    Route::get('/checkin', function () {
        return view('checkin');
    })->name('checkin');

    Route::get('/reservar', function () {
        return view('reservar');
    })->name('reservar');

    // Logout mediante AutenticarController. Cierra sesión y redirige.
    Route::post('/login', [AutenticarController::class, 'logout'])->name('logout');
});