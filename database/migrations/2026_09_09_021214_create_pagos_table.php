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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');

            $table->foreignId('id_reserva')
                ->constrained('reservas', 'id_reserva')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('id_empleado')
                ->nullable()
                ->constrained('empleados', 'id_empleado')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->decimal('monto', 10, 2);

            $table->string('metodo_pago', 50);

            $table->string('numero_referencia', 100)->nullable();

            $table->text('notas')->nullable();

            $table->timestamp('fecha_pago')->nullable();

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};