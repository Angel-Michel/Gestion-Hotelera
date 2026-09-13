<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reserva_habitacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')
                ->constrained('reservas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('habitacion_id')
                ->constrained('habitaciones')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('precio_por_noche', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reserva_habitacion');
    }
};
