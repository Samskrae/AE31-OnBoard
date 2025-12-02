<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('lat', 10, 6);
            $table->decimal('lon', 10, 6);
            $table->text('descripcion');
            $table->enum('nivel', ['Principiante', 'Intermedio', 'Avanzado']);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spots');
    }
};
