<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('consumption_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('shift_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('checked_in_at');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('consumption_logs');
        Schema::enableForeignKeyConstraints();
    }
};
