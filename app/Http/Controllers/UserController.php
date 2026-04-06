<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
}
