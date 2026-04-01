<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller {
    public function index() {
        $menus = Menu::with('reservations')->get();

        return response()->json($menus);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'menu_date' => ['required', 'date', 'unique:menus,menu_date'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'available_portions' => ['required', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in([
                'available',
                'unavailable'
            ])]
        ]);

        if ($validated['available_portions'] == 0) {
            $validated['status'] = 'unavailable';
        } else {
            $validated['status'] = 'available';
        }

        $menu = Menu::create($validated);

        return response()->json([
            'message' => 'Menú creado correctamente',
            'data' => $menu
        ], 201);
    }

    public function show(string $id) {
        $menu = Menu::with('reservations')->findOrFail($id);

        return response()->json($menu);
    }

    public function update(Request $request, string $id) {
        $menu = Menu::with('reservations')->findOrFail($id);

        $validated = $request->validate([
            'menu_date' => [
                'required',
                'date',
                Rule::unique('menus', 'menu_date')->ignore($menu->id)
            ],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'available_portions' => ['required', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in([
                'available',
                'unavailable'
            ])]
        ]);

        if ($menu->reservations()->exists() && $validated['menu_date'] !== $menu->menu_date) {
            return response()->json([
                'message' => 'No se puede cambiar la fecha de un menú que ya tiene reservaciones'
            ], 422);
        }

        if ($validated['available_portions'] < $menu->reservations()->count()) {
            return response()->json([
                'message' => 'La cantidad de porciones disponibles no puede ser menor que las reservaciones existentes'
            ], 422);
        }

        if ($validated['available_portions'] == 0) {
            $validated['status'] = 'unavailable';
        } else {
            $validated['status'] = 'available';
        }

        $menu->update($validated);

        return response()->json([
            'message' => 'Menú actualizado correctamente',
            'data' => $menu->fresh()->load('reservations')
        ]);
    }

    public function destroy(string $id) {
        $menu = Menu::with('reservations')->findOrFail($id);

        if ($menu->reservations()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar un menú que ya tiene reservaciones'
            ], 422);
        }

        $menu->delete();

        return response()->json([
            'message' => 'Menú eliminado correctamente'
        ]);
    }
}
