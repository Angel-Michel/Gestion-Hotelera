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
      Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('correo_electronico')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('rfc', 15)->nullable();
            $table->string('regimen_fiscal', 100)->nullable();
            $table->string('uso_cfdi', 100)->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
