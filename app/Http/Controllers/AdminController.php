<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (! $user || $user->role !== 'administrativo') {
                return redirect('/inicio')->with('error', 'Acceso denegado: privilegios administrativos requeridos.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('admin.index');
    }

    public function menu()
    {
        return view('admin.menu');
    }

    public function creditos()
    {
        return view('admin.creditos');
    }

    public function checkin()
    {
        return view('admin.checkin');
    }

    public function cuenta()
    {
        return view('admin.cuenta');
    }

    public function cuentaInformacion()
    {
        return view('admin.informacion');
    }

    public function cuentaAjustes()
    {
        return view('admin.ajustes');
    }

    public function cuentaSeguridad()
    {
        return view('admin.asignacion_roles');
    }

    public function reporteMovimientos()
    {
        return view('admin.reporte_movimientos');
    }
}
