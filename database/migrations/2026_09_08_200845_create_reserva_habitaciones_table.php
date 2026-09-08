<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Activar extensión GIST para índices de rango en PostgreSQL
        DB::statement('CREATE EXTENSION IF NOT EXISTS btree_gist');

        // 2. Crear la tabla usando SQL nativo para el daterange
        DB::statement('
            CREATE TABLE reserva_habitaciones (
                id BIGSERIAL PRIMARY KEY,
                id_reserva BIGINT REFERENCES reservas(id) ON DELETE CASCADE,
                id_habitacion BIGINT REFERENCES habitaciones(id),
                periodo DATERANGE NOT NULL,
                precio_noche DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT evitar_overbooking_fechas EXCLUDE USING GIST (
                    id_habitacion WITH =,
                    periodo WITH &&
                )
            );
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('reserva_habitaciones');
    }
};