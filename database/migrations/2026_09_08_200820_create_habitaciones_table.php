<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
   Schema::create('habitaciones', function (Blueprint $table) {
    $table->id();
    $table->foreignId('id_tipo_habitacion')->constrained('tipos_habitacion');
    $table->string('numero_habitacion', 10)->unique();
    $table->string('piso', 10)->nullable();
    $table->string('estatus', 50)->default('Disponible');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
