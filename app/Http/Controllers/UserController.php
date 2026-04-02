<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller {
    public function index() {
        $users = User::with('student')->get();

        return response()->json($users);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['student', 'administrative'])],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $user = User::create($validated);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data' => $user->load('student'),
        ], 201);
    }

    public function show(string $id) {
        $user = User::with('student')->findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, string $id) {
        $user = User::with('student')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
            'role' => ['sometimes', 'required', Rule::in(['student', 'administrative'])],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $user->fresh()->load('student'),
        ]);
    }

    public function destroy(string $id) {
        $user = User::with('student')->findOrFail($id);

        if ($user->student) {
            return response()->json([
                'message' => 'No se puede eliminar un usuario asociado a un estudiante',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente',
        ]);
    }
}
