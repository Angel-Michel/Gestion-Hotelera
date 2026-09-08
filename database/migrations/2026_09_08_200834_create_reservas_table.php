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
    $table->id();
    $table->foreignId('id_cliente')->constrained('clientes');
    $table->string('folio_reserva', 50)->unique();
    $table->integer('cantidad_huespedes');
    $table->string('origen_reserva', 50)->nullable();
    $table->string('estatus', 50)->default('Pendiente');
    $table->decimal('monto_total', 10, 2);
    $table->decimal('monto_deposito', 10, 2)->default(0);
    $table->timestamps();
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
