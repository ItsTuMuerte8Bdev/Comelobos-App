<?php

use App\Models\User;
use App\Http\Controllers\AutenticarController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

// Interfaz 1: Bienvenida
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return view('welcome');
})->name('welcome');

// Interfaz 2: Login(Invitado)
Route::middleware('guest')->group(function () {

    // Interfaz 2.1: Email
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // 2.2: Verifica Credenciales
    Route::post('/autenticarLogin', [AutenticarController::class, 'login'])->name('autenticarLogin');

    // Interfaz 2.3: Registrarse
    Route::get('/registro', function () {
        return view('register');
    })->name('registrarse');

    // Interfaz 2.4: Procesar Registro
    Route::post('/autenticarRegistro', [AutenticarController::class, 'register'])->name('autenticarRegistro');

});

// Interfaz 3: Login(Autenticado)
Route::middleware('auth')->group(function () {

    /* Panel administrativo (básico) */
    Route::prefix('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/menu', [\App\Http\Controllers\AdminController::class, 'menu'])->name('admin.menu');
        Route::post('/menu/guardar', [\App\Http\Controllers\AdminController::class, 'storeMenu'])->name('admin.menu.store');
        Route::get('/creditos', [\App\Http\Controllers\AdminController::class, 'creditos'])->name('admin.creditos');
        Route::get('/checkin', [\App\Http\Controllers\AdminController::class, 'checkin'])->name('admin.checkin');
        Route::get('/cuenta', [\App\Http\Controllers\AdminController::class, 'cuenta'])->name('admin.cuenta');
        Route::get('/cuenta/informacion', [\App\Http\Controllers\AdminController::class, 'cuentaInformacion'])->name('admin.cuenta.informacion');
        Route::get('/cuenta/ajustes', [\App\Http\Controllers\AdminController::class, 'cuentaAjustes'])->name('admin.cuenta.ajustes');
        Route::get('/cuenta/asignacion-roles', [\App\Http\Controllers\AdminController::class, 'cuentaSeguridad'])->name('admin.cuenta.asignacion');
        Route::get('/cuenta/reporte-movimientos', [\App\Http\Controllers\AdminController::class, 'reporteMovimientos'])->name('admin.cuenta.reporte');
    });



    // Interfaz 3.1: Pagina Principal
    Route::get('/inicio', [HomeController::class, 'index'])->name('inicio');

    // Rutas que devuelven vistas directamente para el usuario autenticado.
    Route::get('/cuenta', function () {
        return view('account');
    })->name('cuenta');

    // Interfaz: Reservar
    Route::post('/api/crear-reserva',[\App\Http\Controllers\ReservationController::class, 'store'])->name('api.reserva.store');
    
    // NUEVA RUTA PARA CANCELAR
    Route::post('/api/reservas/{id}/cancelar', [\App\Http\Controllers\ReservationController::class, 'cancel'])->name('api.reserva.cancel');


    Route::get('/reservas', function () {
        // Buscamos TODAS las reservas del alumno, de la más nueva a la más vieja
        $reservas = \App\Models\Reservation::with(['menu', 'shift'])
            ->where('user_id', Auth::id())
            ->orderBy('reservation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservas', compact('reservas'));
    })->name('reservas');


    Route::get('/informacion', function () {
        return view('informacion');
    })->name('informacion');

    // Procesar cambio de contraseña desde la página de Información personal
    Route::post('/informacion/password', [UserController::class, 'updatePassword'])->name('informacion.password.update');

    Route::get('/contactos', function () {
        return view('contactos');
    })->name('contactos');

    Route::get('/ajustes', function () {
        return view('ajustes');
    })->name('ajustes');


    Route::get('/checkin', function () {
        $hoy = \Carbon\Carbon::now()->toDateString();
        
        // Buscamos las reservas del alumno para el día de hoy que estén PAGADAS
        $reservas = \App\Models\Reservation::with('menu', 'shift')
            ->where('user_id', Auth::id())
            ->where('reservation_date', $hoy)
            ->where('status', 'paid') // <- La magia: Si ya se consumió, ya no sale aquí.
            ->orderBy('shift_id')
            ->get();

        return view('checkin', compact('reservas'));
    })->name('checkin');






    // Logout mediante AutenticarController. Cierra sesión y redirige.
    Route::post('/login', [AutenticarController::class, 'logout'])->name('logout');
});