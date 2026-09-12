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
            $table->id('id_habitacion');

            $table->foreignId('id_tipo_habitacion')
                ->constrained('tipos_habitacion', 'id_tipo_habitacion')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('numero_habitacion', 10);
            $table->string('piso', 10)->nullable();
            $table->string('estatus', 20);

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
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