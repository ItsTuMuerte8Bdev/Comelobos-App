<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ==========================================
    // SEGURIDAD DEL CONTROLADOR
    // ==========================================
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            // Si no está logueado o no es admin, lo pateamos al login
            if (! $user || $user->role !== 'administrativo') {
                return redirect()->route('login')->with('error', 'Acceso denegado: privilegios administrativos requeridos.');
            }
            return $next($request);
        });
    }

    // ==========================================
    // SECCIÓN: PANTALLAS PRINCIPALES
    // ==========================================
    public function index()
    {
        // Obtenemos la fecha y los menús activos de hoy para mostrarlos en la pantalla de bienvenida
        $fechaHoy = now()->toDateString();
        $menuDesayuno = Menu::where('menu_date', $fechaHoy)->where('type', 'desayuno')->first();
        $menuComida = Menu::where('menu_date', $fechaHoy)->where('type', 'comida')->first();
        
        return view('admin.index', compact('menuDesayuno', 'menuComida', 'fechaHoy'));
    }

    public function checkin()
    {
        return view('admin.checkin');
    }

    // ==========================================
    // LÓGICA DEL ESCÁNER QR (CHECK-IN)
    // ==========================================

    // 1. Recibe el texto del QR por Javascript y devuelve los datos del alumno
    public function getCheckinInfo(Request $request)
    {
        // Buscamos la reserva que coincida con el QR escaneado
        $reservation = \App\Models\Reservation::with(['user', 'menu', 'shift'])
            ->where('qr_code', $request->qr_code)
            ->first();

        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Código QR inválido o no encontrado en el sistema.']);
        }

        if ($reservation->status === 'consumed') {
            return response()->json(['success' => false, 'message' => 'Este platillo ya fue entregado anteriormente.']);
        }

        if ($reservation->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Esta reserva fue cancelada por el usuario.']);
        }

        // Si todo está bien y está "paid", devolvemos los datos para armar el Modal
        return response()->json([
            'success' => true,
            'reservation' => $reservation
        ]);
    }

    // 2. Ejecuta el cambio de estado a "Consumido"
    public function consumeCheckin(Request $request)
    {
        $reservation = \App\Models\Reservation::where('id', $request->reservation_id)->firstOrFail();
        
        $reservation->status = 'consumed';
        $reservation->save();

        return back()->with('success', '¡Check-In exitoso! Platillo marcado como entregado.');
    }

    public function creditos(Request $request)
    {
        $usuario = null;
        
        if ($request->filled('matricula')) {
            $usuario = \App\Models\User::where('matriculation_number', $request->matricula)->first();
            
            if (!$usuario) {
                return back()->withErrors('No se encontró ningún usuario con esa matrícula.');
            }
        }
        
        return view('admin.creditos', compact('usuario'));
    }

    // 2. Procesar el depósito de créditos
    public function addCreditos(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:1', 'max:5000'] // Límite de seguridad
        ]);

        $usuario = \App\Models\User::findOrFail($request->user_id);
        
        // Sumamos los créditos
        $usuario->credits += $request->amount;
        $usuario->save();

        // Redirigimos de vuelta con la misma matrícula para que vea el nuevo saldo en pantalla
        return redirect()->route('admin.creditos', ['matricula' => $usuario->matriculation_number])
                         ->with('success', '¡Transacción exitosa! Se han depositado $' . number_format($request->amount, 2) . ' a ' . $usuario->first_name . '.');
    }

    // ==========================================
    // SECCIÓN: MENÚ DE CUENTA
    // ==========================================
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

    public function cuentaReporte()
    {
        return view('admin.reporte_movimientos');
    }

    // ==========================================
    // SECCIÓN: LÓGICA DE NEGOCIO (Guardar Menú)
    // ==========================================
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
            $partes = explode('-', $rango);
            $start = trim($partes[0]);
            $end = isset($partes[1]) ? trim($partes[1]) : $start;
            
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
    
    // ==========================================
    // SECCIÓN: GESTIÓN DE USUARIOS Y ROLES
    // ==========================================
    public function asignacionRoles(Request $request)
    {
        $usuario = null;
        
        if ($request->filled('matricula')) {
            $usuario = \App\Models\User::where('matriculation_number', $request->matricula)->first();
            
            if (!$usuario) {
                return back()->withErrors('No se encontró ningún usuario con esa matrícula.');
            }
        }
        
        return view('admin.asignacion_roles', compact('usuario'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => ['required', 'in:cliente,administrativo']
        ]);

        $usuario = \App\Models\User::findOrFail($id);
        
        if ($usuario->id === Auth::id()) {
            return back()->withErrors('Por seguridad, no puedes cambiar tu propio nivel de acceso.');
        }

        $usuario->role = $request->role;
        $usuario->save();

        return redirect()->route('admin.asignacion_roles')->with('success', '¡Éxito! El usuario ' . $usuario->first_name . ' ahora es ' . ucfirst($usuario->role) . '.');
    }
}