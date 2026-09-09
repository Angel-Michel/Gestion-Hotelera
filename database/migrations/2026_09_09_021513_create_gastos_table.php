<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id('id_gastos');

            $table->string('categoria', 100);

            $table->string('descripcion', 255);

            $table->decimal('monto', 10, 2);

            $table->date('fecha_gasto');

            $table->string('ruta_comprobante', 255)->nullable();

            $table->foreignId('id_empleado')
                ->nullable()
                ->constrained('empleados', 'id_empleado')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};