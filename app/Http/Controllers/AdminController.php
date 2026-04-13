<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Shift;

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


    public function menu() // (Usa el nombre que ya tengas en tu controlador)
    {
        $fechaHoy = now()->toDateString();
        $menuDesayuno = Menu::where('menu_date', $fechaHoy)->where('type', 'desayuno')->first();
        $menuComida = Menu::where('menu_date', $fechaHoy)->where('type', 'comida')->first();
        return view('admin.menu', compact('menuDesayuno', 'menuComida', 'fechaHoy'));
    }
    
    // Función para guardar o sobreescribir el menú del día
    public function storeMenu(Request $request)
    {

        $request->merge([
            'entrada' => $request->entrada ? mb_convert_case($request->entrada, MB_CASE_TITLE, "UTF-8") : null,
            'platillo_principal' => $request->platillo_principal ? mb_convert_case($request->platillo_principal, MB_CASE_TITLE, "UTF-8") : null,
            'bebida' => $request->bebida ? mb_convert_case($request->bebida, MB_CASE_TITLE, "UTF-8") : null,
            'description' => $request->description ? ucfirst(strtolower($request->description)) : null
        ]);


        $validated = $request->validateWithBag($request->type,[
            'type' => ['required', 'in:desayuno,comida'], 
            'menu_date' => ['required', 'date'], 
            'entrada' => ['required', 'string', 'max:255','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'], 
            'platillo_principal' => ['required', 'string', 'max:255','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'bebida' => ['required', 'string', 'max:255','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'description' => ['required', 'string', 'max:255','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s,.]+$/u'],
            'available_portions' => ['required', 'integer', 'min:1'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:16384'],
            'shifts' => ['required', 'array', 'min:1']
        ],[
            'type.required' => 'Tipo de Menú es obligatorio.',
            'type.in' => 'El Tipo de Menú debe ser "Desayuno" o "Comida".',
            'menu_date.required' => 'Asigne una fecha.',
            'menu_date.date' => 'Fecha no válida.',
            'entrada.required' => 'Entrada obligatoria.',
            'platillo_principal.required' => 'Platillo Principal obligatorio.',
            'bebida.required' => 'Bebida obligatoria.',
            'description.required' => 'Descripción obligatoria.',
            'available_portions.required' => 'Porciones Disponibles obligatorias.',
            'available_portions.integer' => 'Número de porciones no válido.',
            'available_portions.min' => 'Debe haber al menos 1 porción disponible.',
            'shifts.required' => 'Selecciona al menos un horario.'
        ]);

        $validated['status'] = 'available'; 
        $validated['price'] = 35.00;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image_path'] = '/storage/' . $path; 
        }

        Menu::updateOrCreate(
            [
                'menu_date' => $validated['menu_date'],
                'type' => $validated['type']
            ],
            $validated
        );

        $cupoPorHora = floor($validated['available_portions'] / count($request->shifts));
        
        foreach ($request->shifts as $rango) {
            // Separamos "09:00-10:00" en inicio y fin
            list($start, $end) = explode(' - ', $rango);
            
            $shift = Shift::firstOrNew([
                'shift_date' => $validated['menu_date'],
                'start_time' => trim($start) . ':00',
                'end_time' => trim($end) . ':00',
            ]);

            if (!$shift->exists) {
                $shift->max_capacity = $cupoPorHora;
                $shift->available_capacity = $cupoPorHora;
                $shift->status = 'open';
                $shift->save();
            }
        }
        return back()->with('success', '¡El menú del día se ha publicado/actualizado con éxito!');
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
