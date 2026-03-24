<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            $table->date('shift_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('max_capacity');
            $table->unsignedInteger('available_capacity');
            $table->enum('status', ['open', 'full', 'closed'])->default('open');

            $table->timestamps();

            $table->unique(['shift_date', 'start_time', 'end_time']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('shifts');
    }

};
