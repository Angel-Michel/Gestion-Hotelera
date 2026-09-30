<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * El catálogo de servicios crece con los datos que necesita el personal de
     * recepción: qué es el servicio (descripcion) y en qué área del hotel se
     * agrupa (categoria, por ejemplo minibar, restaurante o lavandería).
     */
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->string('descripcion', 255)->nullable()->after('nombre');
            $table->string('categoria', 50)->nullable()->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'categoria']);
        });
    }
};
