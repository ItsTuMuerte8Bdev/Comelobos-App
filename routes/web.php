<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout(); 
    
    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 
    
    return redirect('/login'); 
})->name('logout');



Route::get('/index', [PageController::class, 'index']);

Route::get('/account', function () {
    return view('account');
});

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

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', function (\Illuminate\Http\Request $request) {
    // Por ahora no validamos ni guardamos; simplemente redirigimos al index
    return redirect('/index');
});

// Rutas de inicio de sesión (flujo: email -> contraseña -> redirigir al index)
Route::get('/login', function () {
    return view('login');
});

Route::post('/login/password', function (\Illuminate\Http\Request $request) {
    // Recibimos el email y mostramos la pantalla de contraseña
    return view('login_password', ['email' => $request->input('email')]);
});

Route::post('/login', function (\Illuminate\Http\Request $request) {
    // Por ahora no validamos; simplemente redirigimos al index
    return redirect('/index');
});
