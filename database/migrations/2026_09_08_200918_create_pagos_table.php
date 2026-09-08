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
    $table->id();
    $table->foreignId('id_reserva')->constrained('reservas');
    $table->foreignId('id_usuario')->constrained('users');
    $table->decimal('monto', 10, 2);
    $table->string('metodo_pago', 50)->nullable();
    $table->string('numero_referencia', 100)->nullable();
    $table->text('notas')->nullable();
    $table->timestamp('fecha_pago')->useCurrent();
    $table->timestamps();
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
