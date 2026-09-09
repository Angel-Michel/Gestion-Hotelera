<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_habitacion', function (Blueprint $table) {
            $table->id('id_tipo_habitacion');

            $table->string('nombre', 100);

            $table->text('descripcion')->nullable();

            $table->unsignedInteger('capacidad_adultos');

            $table->unsignedInteger('capacidad_ninos')->default(0);

            $table->decimal('precio_base', 10, 2);

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_habitacion');
    }
};