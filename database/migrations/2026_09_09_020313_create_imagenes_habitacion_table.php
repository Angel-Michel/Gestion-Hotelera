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
        Schema::create('imagenes_habitacion', function (Blueprint $table) {
            $table->id('id_imagenes_habitacion');

            $table->foreignId('id_tipo_habitacion')
                ->constrained('tipos_habitacion', 'id_tipo_habitacion')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('ruta_imagen', 255);
            $table->boolean('es_principal')->default(false);

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes_habitacion');
    }
};