<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')->unique()->constrained()->cascadeOnDelete();

            $table->decimal('amount', 8, 2);
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_folio', 50)->unique();
            $table->enum('payment_method', ['creditos'])->default('creditos');
            $table->enum('payment_status', ['paid', 'refunded'])->default('paid');
            $table->string('external_reference')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('payments');
        Schema::enableForeignKeyConstraints();
    }

};
