<?php

use App\Http\Controllers\PageController; // Logica
use App\Http\Controllers\AuthController; // Autenticador
use Illuminate\Support\Facades\Route; // Direccionador
use Illuminate\Http\Request; // Lectura de datos

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
    Route::post('/login/password', function (Request $request) {
        return view('login_password', ['email' => $request->input('email')]);
    })->name('login.password');

    // 2.3: Verifica Credenciales
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    // Interfaz 2.4: Registrarse
    Route::get('/register', function () {
        return view('register');
    })->name('register');

    // Interfaz 2.5: Procesar Registro
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

});

// Interfaz 3: Login(Autenticado)
Route::middleware('auth')->group(function () {

    // Interfaz 3.1: Pagina Principal
    Route::get('/index', [PageController::class, 'index'])->name('index');

    // Rutas que devuelven vistas directamente para el usuario autenticado.
    Route::get('/account', function () {
        return view('account');
    })->name('account');

    Route::get('/informacion', function () {
        return view('informacion');
    });

    Route::get('/contactos', function () {
        return view('contactos');
    });

    Route::get('/ajustes', function () {
        return view('ajustes');
    });

    Route::get('/menu', function () {
        return view('menu');
    });

    Route::get('/reservas', function () {
        return view('reservas');
    });

    Route::get('/checkin', function () {
        return view('checkin');
    });

    Route::get('/reservar', function () {
        return view('reservar');
    });

    // Logout mediante AuthController. Cierra sesión y redirige.
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});