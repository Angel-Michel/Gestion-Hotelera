<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Usuario Administrador
        |--------------------------------------------------------------------------
        */

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'activo' => true,
            ]
        );

        if (! $admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tipos de habitación iniciales
        |--------------------------------------------------------------------------
        */

        $tipos = [
            ['nombre' => 'Estándar', 'descripcion' => 'Habitación cómoda con cama matrimonial y baño privado.', 'precio_base' => 899.00, 'capacidad' => 2],
            ['nombre' => 'Deluxe', 'descripcion' => 'Habitación amplia con balcón, minibar y vista al jardín.', 'precio_base' => 1499.00, 'capacidad' => 3],
            ['nombre' => 'Suite Familiar', 'descripcion' => 'Suite con dos recámaras, sala de estar y cocineta.', 'precio_base' => 2499.00, 'capacidad' => 5],
            ['nombre' => 'Suite Presidencial', 'descripcion' => 'Suite de lujo con jacuzzi, terraza privada y servicio premium.', 'precio_base' => 4999.00, 'capacidad' => 4],
        ];

        foreach ($tipos as $tipo) {
            TipoHabitacion::firstOrCreate(['nombre' => $tipo['nombre']], $tipo);
        }

        /*
        |--------------------------------------------------------------------------
        | Habitaciones iniciales
        |--------------------------------------------------------------------------
        */

        $habitaciones = [
            ['numero_habitacion' => '101', 'tipo' => 'Estándar', 'estado' => 'Disponible', 'piso' => 1],
            ['numero_habitacion' => '102', 'tipo' => 'Estándar', 'estado' => 'Disponible', 'piso' => 1],
            ['numero_habitacion' => '103', 'tipo' => 'Estándar', 'estado' => 'Ocupada', 'piso' => 1],
            ['numero_habitacion' => '104', 'tipo' => 'Estándar', 'estado' => 'Limpieza', 'piso' => 1],
            ['numero_habitacion' => '201', 'tipo' => 'Deluxe', 'estado' => 'Disponible', 'piso' => 2],
            ['numero_habitacion' => '202', 'tipo' => 'Deluxe', 'estado' => 'Disponible', 'piso' => 2],
            ['numero_habitacion' => '203', 'tipo' => 'Deluxe', 'estado' => 'Mantenimiento', 'piso' => 2],
            ['numero_habitacion' => '301', 'tipo' => 'Suite Familiar', 'estado' => 'Disponible', 'piso' => 3],
            ['numero_habitacion' => '302', 'tipo' => 'Suite Familiar', 'estado' => 'Disponible', 'piso' => 3],
            ['numero_habitacion' => '401', 'tipo' => 'Suite Presidencial', 'estado' => 'Disponible', 'piso' => 4],
        ];

        foreach ($habitaciones as $habitacion) {
            $tipo = TipoHabitacion::where('nombre', $habitacion['tipo'])->firstOrFail();

            Habitacion::firstOrCreate(
                ['numero_habitacion' => $habitacion['numero_habitacion']],
                [
                    'tipo_habitacion_id' => $tipo->id,
                    'estado' => $habitacion['estado'],
                    'piso' => $habitacion['piso'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Clientes iniciales
        |--------------------------------------------------------------------------
        */

        $clientes = [
            ['nombre' => 'María', 'apellido' => 'Hernández', 'email' => 'maria.hernandez@example.com', 'telefono' => '5512345678', 'tipo_identificacion' => 'INE', 'numero_identificacion' => 'HEMR900101HDFRRN01'],
            ['nombre' => 'Juan', 'apellido' => 'Pérez', 'email' => 'juan.perez@example.com', 'telefono' => '5587654321', 'tipo_identificacion' => 'INE', 'numero_identificacion' => 'PEPJ850515HDFRRN02'],
            ['nombre' => 'Ana', 'apellido' => 'García', 'email' => 'ana.garcia@example.com', 'telefono' => '5545678901', 'tipo_identificacion' => 'Pasaporte', 'numero_identificacion' => 'G12345678'],
            ['nombre' => 'Carlos', 'apellido' => 'López', 'email' => 'carlos.lopez@example.com', 'telefono' => '5534567890', 'tipo_identificacion' => 'INE', 'numero_identificacion' => 'LOPC920220HDFRRN03'],
            ['nombre' => 'Laura', 'apellido' => 'Martínez', 'email' => 'laura.martinez@example.com', 'telefono' => '5523456789', 'tipo_identificacion' => 'Pasaporte', 'numero_identificacion' => 'M98765432'],
        ];

        foreach ($clientes as $cliente) {
            Cliente::firstOrCreate(['email' => $cliente['email']], $cliente);
        }
    }
}
