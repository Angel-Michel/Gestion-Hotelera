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
      Schema::create('empleados', function (Blueprint $table) {
    $table->id();
    $table->foreignId('id_usuario')->constrained('users')->cascadeOnDelete();
    $table->string('puesto', 100)->nullable();
    $table->decimal('salario', 10, 2)->nullable();
    $table->string('horario')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
