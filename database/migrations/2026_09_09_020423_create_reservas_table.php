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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('id_reserva');

            $table->string('folio_reserva', 12)->unique();

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('id_tipo_habitacion')
                ->constrained('tipos_habitacion', 'id_tipo_habitacion')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('id_temporada')
                ->nullable()
                ->constrained('temporadas', 'id_temporada')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->unsignedInteger('cantidad_huespedes');

            $table->string('origen_reserva', 50);
            $table->string('estatus', 50);

            $table->decimal('monto_total', 10, 2);
            $table->decimal('monto_deposito', 10, 2)->default(0);

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
