<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (! $user || $user->role !== 'administrativo') {
                return redirect()->route('login')->with('error', 'Acceso denegado: privilegios administrativos requeridos.');
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
    // Función para guardar el menú en la base de datos
    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string'], // Desayuno o Comida
            'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'], // Ej. Huevo a la mexicana
            'menu_date' => ['required', 'date'], // ¿Qué día se sirve?
            'available_portions' => ['required', 'integer', 'min:1'], // ¿Cuántos platos hay?
        ]);

        // Por defecto, cuando creas un menú, está disponible
        $validated['status'] = 'available'; 
        $validated['description'] = mb_convert_case($validated['description'], MB_CASE_TITLE, "UTF-8");
        $validated['price'] = 50;
        
        // Guardamos en la tabla 'menus'
        Menu::create($validated);

        // Regresamos a la pantalla anterior con el mensaje verde de éxito
        return back()->with('success', 'Menú agregado correctamente al calendario.');
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
