<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void{
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['desayuno', 'comida']);
            $table->date('menu_date');
            $table->string('entrada');
            $table->string('platillo_principal');
            $table->string('bebida');
            $table->string('description');
            $table->string('image_path');
            $table->decimal('price', 8, 2)->default(35.00);
            $table->integer('available_portions');
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menus');
    }

};
