<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    public function index() {
        $users = User::query()->get();

        return response()->json($users);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'matriculation_number' => ['required', 'string', 'max:20', 'unique:users,matriculation_number'],
            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:100'],
            'second_last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['student', 'administrative'])],
        ]);

        $user = User::create($validated);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data' => $user,
        ], 201);
    }

    public function show(string $id) {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, string $id) {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'matriculation_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'matriculation_number')->ignore($user->id),
            ],
            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:100'],
            'second_last_name' => ['required', 'string', 'max:100'],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['student', 'administrative'])],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $user->fresh(),
        ]);
    }

    public function destroy(string $id) {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente',
        ]);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Debe ingresar su contraseña actual.',
            'password.required' => 'Debe ingresar una nueva contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])->withInput();
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success_password', 'Contraseña actualizada correctamente.');
    }
}
