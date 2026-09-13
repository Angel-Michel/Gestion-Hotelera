<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos_habitacion', function (Blueprint $table) {
            $table->id('id_cargos_habitacion');

            $table->foreignId('reserva_id')
                ->constrained('reservas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('concepto', 255);

            $table->decimal('monto', 10, 2);

            $table->timestamp('fecha_cargo')->nullable();

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos_habitacion');
    }
};
