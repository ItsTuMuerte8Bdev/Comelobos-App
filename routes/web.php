<?php

use App\Models\User;
use App\Http\Controllers\AutenticarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// =========================================================================
// 1. RUTAS PÚBLICAS Y DISTRIBUIDOR
// =========================================================================

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// EL DISTRIBUIDOR INTELIGENTE: Esta es la clave para que ambos funcionen
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if ($user->role === 'administrativo') {
        return redirect()->route('admin.index');
    }
    
    return redirect()->route('inicio');
})->middleware(['auth'])->name('dashboard');


// =========================================================================
// 2. RUTAS PARA INVITADOS (GUEST)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/autenticarLogin', [AutenticarController::class, 'login'])->name('autenticarLogin');

    Route::get('/registro', function () {
        return view('register');
    })->name('registrarse');

    Route::post('/autenticarRegistro', [AutenticarController::class, 'register'])->name('autenticarRegistro');
});


// =========================================================================
// 3. RUTAS PROTEGIDAS (AUTH)
// =========================================================================
Route::middleware('auth')->group(function () {

    // ---------------------------------------------------------------------
    // A. PANEL ADMINISTRATIVO (Solo para el Admin)
    // ---------------------------------------------------------------------
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/menu/guardar', [AdminController::class, 'storeMenu'])->name('admin.menu.store');
        
        Route::get('/creditos', [AdminController::class, 'creditos'])->name('admin.creditos');
        Route::post('/creditos/agregar', [AdminController::class, 'addCreditos'])->name('admin.creditos.add');

        Route::get('/checkin', [AdminController::class, 'checkin'])->name('admin.checkin');
        Route::post('/checkin/info', [AdminController::class, 'getCheckinInfo'])->name('admin.checkin.info');
        Route::post('/checkin/consumir', [AdminController::class, 'consumeCheckin'])->name('admin.checkin.consume');

        // Configuración y Reportes
        Route::get('/cuenta', [AdminController::class, 'cuenta'])->name('admin.cuenta');
        Route::get('/cuenta/informacion', [AdminController::class, 'cuentaInformacion'])->name('admin.cuenta.informacion');
        Route::get('/cuenta/ajustes', [AdminController::class, 'cuentaAjustes'])->name('admin.cuenta.ajustes');
        Route::post('/cuenta/ajustes', [AdminController::class, 'storeAjustes'])->name('admin.cuenta.ajustes.store');
        Route::get('/cuenta/reporte', [AdminController::class, 'cuentaReporte'])->name('admin.cuenta.reporte');
        
        // Usuarios y Roles
        Route::get('/cuenta/asignacion-roles', [AdminController::class, 'asignacionRoles'])->name('admin.asignacion_roles');
        Route::post('/cuenta/asignacion-roles/{id}', [AdminController::class, 'updateRole'])->name('admin.update_role');
        Route::post('/cuenta/asignacion-roles/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.reset_password');
    });

    // ---------------------------------------------------------------------
    // B. PANEL DE ESTUDIANTE (Solo para el Cliente)
    // ---------------------------------------------------------------------
    
    // Vista principal del alumno
    Route::get('/inicio', [HomeController::class, 'index'])->name('inicio');

    // Reservas
    Route::post('/api/crear-reserva', [ReservationController::class, 'store'])->name('api.reserva.store');
    Route::post('/api/reservas/{id}/cancelar', [ReservationController::class, 'cancel'])->name('api.reserva.cancel');

    Route::get('/reservas', function () {
        $reservas = \App\Models\Reservation::with(['menu', 'shift'])
            ->where('user_id', Auth::id())
            ->orderBy('reservation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('reservas', compact('reservas'));
    })->name('reservas');

    // QR y Check-in del alumno
    Route::get('/checkin', function () {
        $hoy = \Carbon\Carbon::now()->toDateString();
        $reservas = \App\Models\Reservation::with('menu', 'shift')
            ->where('user_id', Auth::id())
            ->where('reservation_date', $hoy)
            ->where('status', 'paid')
            ->orderBy('shift_id')
            ->get();
        return view('checkin', compact('reservas'));
    })->name('checkin');

    // Perfil y Ajustes del alumno
    Route::get('/cuenta', function () { return view('account'); })->name('cuenta');
    Route::get('/informacion', function () { return view('informacion'); })->name('informacion');
    Route::post('/informacion/password', [UserController::class, 'updatePassword'])->name('informacion.password.update');
    Route::get('/contactos', function () { return view('contactos'); })->name('contactos');
    Route::get('/ajustes', function () { return view('ajustes'); })->name('ajustes');

    // Cierre de Sesión
    Route::post('/logout', [AutenticarController::class, 'logout'])->name('logout');
});