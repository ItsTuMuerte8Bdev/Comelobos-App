<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->restrictOnDelete();
            $table->foreignId('menu_id')->constrained()->restrictOnDelete();
            $table->date('reservation_date');
            $table->string('folio', 50)->unique();
            $table->string('qr_code', 255)->unique();
            $table->enum('status', ['paid', 'cancelled', 'consumed'])->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('reservations');
        Schema::enableForeignKeyConstraints();
    }
};
