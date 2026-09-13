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
            $table->string('numero_habitacion', 10)->unique();
            $table->foreignId('tipo_habitacion_id')
                ->constrained('tipos_habitacion')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->enum('estado', ['Disponible', 'Ocupada', 'Mantenimiento', 'Limpieza'])->default('Disponible');
            $table->unsignedInteger('piso')->default(1);
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
