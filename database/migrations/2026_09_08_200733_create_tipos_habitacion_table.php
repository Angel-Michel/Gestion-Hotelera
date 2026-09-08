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
    Schema::create('tipos_habitacion', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 100);
    $table->text('descripcion')->nullable();
    $table->integer('capacidad_adultos');
    $table->integer('capacidad_ninos');
    $table->decimal('precio_base', 10, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_habitacion');
    }
};
