<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder {
    public function run(): void {
        $studentUser = User::where('email', 'student@comelobos.test')->first();

        if (!$studentUser) {
            return;
        }

        Student::updateOrCreate(
            ['student_code' => '202320912'],
            [
                'user_id' => $studentUser->id,
                'first_name' => 'Alfonso Arnulfo',
                'last_name' => 'Cuevas',
                'second_last_name' => 'Reynoso',
            ]
        );
    }
}
