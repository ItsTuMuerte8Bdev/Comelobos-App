<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller {
    public function index() {
        $students = Student::with([
            'user',
            'reservations'
        ])->get();

        return response()->json($students);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:students,user_id'],
            'student_code' => ['required', 'string', 'max:20', 'unique:students,student_code'],
            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:100'],
            'second_last_name' => ['nullable', 'string', 'max:100'],
        ]);

        $student = Student::create($validated);

        return response()->json([
            'message' => 'Estudiante creado correctamente',
            'data' => $student->load([
                'user',
                'reservations'
            ])
        ], 201);
    }

    public function show(string $id) {
        $student = Student::with([
            'user',
            'reservations'
        ])->findOrFail($id);

        return response()->json($student);
    }

    public function update(Request $request, string $id) {
        $student = Student::with('reservations')->findOrFail($id);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('students', 'user_id')->ignore($student->id)
            ],
            'student_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students', 'student_code')->ignore($student->id)
            ],
            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:100'],
            'second_last_name' => ['nullable', 'string', 'max:100'],
        ]);

        $student->update($validated);

        return response()->json([
            'message' => 'Estudiante actualizado correctamente',
            'data' => $student->fresh()->load([
                'user',
                'reservations'
            ])
        ]);
    }

    public function destroy(string $id) {
        $student = Student::with('reservations')->findOrFail($id);

        $hasActiveReservations = $student->reservations()
            ->whereIn('status', ['pending_payment', 'paid', 'consumed'])
            ->exists();

        if ($hasActiveReservations) {
            return response()->json([
                'message' => 'No se puede eliminar un estudiante con reservaciones asociadas'
            ], 422);
        }

        $student->delete();

        return response()->json([
            'message' => 'Estudiante eliminado correctamente'
        ]);
    }
}
