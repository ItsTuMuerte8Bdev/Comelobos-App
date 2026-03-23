<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->string('student_code', 20)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('second_last_name', 100)->nullable();
            $table->string('faculty', 150);
            $table->unsignedTinyInteger('semester')->nullable();
            $table->string('alternate_email')->nullable();
            $table->enum('academic_status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('students');
    }

};
