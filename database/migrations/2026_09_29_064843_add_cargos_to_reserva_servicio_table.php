<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `reserva_servicio` es la tabla transaccional de cargos al folio: una fila
     * por cada servicio consumido por un huésped. Se le añade el precio unitario
     * que se aplicó al cobrar (que puede diferir del precio de catálogo si
     * recepción aplica un descuento) y el empleado responsable del cargo.
     */
    public function up(): void
    {
        Schema::table('reserva_servicio', function (Blueprint $table) {
            $table->decimal('precio_aplicado', 10, 2)->nullable()->after('cantidad');
            $table->foreignId('empleado_id')
                ->nullable()
                ->after('precio_aplicado')
                ->constrained('empleados', 'id_empleado')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        /*
         | Los cargos anteriores solo guardaban el subtotal. Se completa el precio
         | unitario con el precio de catálogo vigente para que el histórico siga
         | siendo interpretable sin recalcular nada.
         */
        DB::table('reserva_servicio')
            ->whereNull('precio_aplicado')
            ->where('cantidad', '>', 0)
            ->orderBy('id')
            ->get()
            ->each(function (object $cargo): void {
                DB::table('reserva_servicio')
                    ->where('id', $cargo->id)
                    ->update([
                        'precio_aplicado' => round((float) $cargo->subtotal / (int) $cargo->cantidad, 2),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('reserva_servicio', function (Blueprint $table) {
            $table->dropConstrainedForeignId('empleado_id');
            $table->dropColumn('precio_aplicado');
        });
    }
};
