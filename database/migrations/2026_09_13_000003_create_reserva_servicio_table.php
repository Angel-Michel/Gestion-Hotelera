<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reserva_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')
                ->constrained('reservas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('servicio_id')
                ->constrained('servicios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reserva_servicio');
    }
};
