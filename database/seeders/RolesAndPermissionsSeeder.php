<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        //Permisos granulares

        $permissions = [
            // Dashboard
            'dashboard.ver',

            // Reservaciones
            'reservaciones.ver',
            'reservaciones.crear',
            'reservaciones.editar',
            'reservaciones.eliminar',

            // Habitaciones
            'habitaciones.ver',
            'habitaciones.crear',
            'habitaciones.editar',
            'habitaciones.eliminar',

            // Clientes
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',

            // Check-in / Check-out
            'checkin_checkout.ver',
            'checkin_checkout.crear',
            'checkin_checkout.editar',

            // Limpieza
            'limpieza.ver',
            'limpieza.crear',
            'limpieza.editar',
            'limpieza.eliminar',

            // Pagos
            'pagos.ver',
            'pagos.crear',
            'pagos.editar',
            'pagos.eliminar',

            // Servicios
            'servicios.ver',
            'servicios.crear',
            'servicios.editar',
            'servicios.eliminar',

            // Gastos
            'gastos.ver',
            'gastos.crear',
            'gastos.editar',
            'gastos.eliminar',

            // Empleados
            'empleados.ver',
            'empleados.crear',
            'empleados.editar',
            'empleados.eliminar',

            // Temporadas
            'temporadas.ver',
            'temporadas.crear',
            'temporadas.editar',
            'temporadas.eliminar',

            // Reportes
            'reportes.ver',

            // Usuarios
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',

            // Roles y permisos
            'roles_permisos.ver',
            'roles_permisos.crear',
            'roles_permisos.editar',
            'roles_permisos.eliminar',

            // Configuración
            'configuracion.ver',
            'configuracion.editar',
        ];

        /*
        |--------------------------------------------------------------------------
        | Crear permisos
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Crear roles
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $gerente = Role::firstOrCreate([
            'name' => 'gerente',
            'guard_name' => 'web',
        ]);

        $recepcionista = Role::firstOrCreate([
            'name' => 'recepcionista',
            'guard_name' => 'web',
        ]);

        $limpieza = Role::firstOrCreate([
            'name' => 'limpieza',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Permisos del SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions($permissions);

        /*
        |--------------------------------------------------------------------------
        | Permisos del GERENTE
        |--------------------------------------------------------------------------
        */

        $gerente->syncPermissions([
            'dashboard.ver',

            'reservaciones.ver',
            'reservaciones.crear',
            'reservaciones.editar',
            'reservaciones.eliminar',

            'habitaciones.ver',
            'habitaciones.crear',
            'habitaciones.editar',
            'habitaciones.eliminar',

            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',

            'checkin_checkout.ver',
            'checkin_checkout.crear',
            'checkin_checkout.editar',

            'limpieza.ver',
            'limpieza.crear',
            'limpieza.editar',
            'limpieza.eliminar',

            'pagos.ver',
            'pagos.crear',
            'pagos.editar',
            'pagos.eliminar',

            'servicios.ver',
            'servicios.crear',
            'servicios.editar',
            'servicios.eliminar',

            'gastos.ver',
            'gastos.crear',
            'gastos.editar',
            'gastos.eliminar',

            'empleados.ver',
            'empleados.crear',
            'empleados.editar',
            'empleados.eliminar',

            'temporadas.ver',
            'temporadas.crear',
            'temporadas.editar',
            'temporadas.eliminar',

            'reportes.ver',

            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',

            'roles_permisos.ver',
            'roles_permisos.crear',
            'roles_permisos.editar',

            'configuracion.ver',
            'configuracion.editar',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Permisos del RECEPCIONISTA
        |--------------------------------------------------------------------------
        */

        $recepcionista->syncPermissions([
            'dashboard.ver',

            'reservaciones.ver',
            'reservaciones.crear',
            'reservaciones.editar',

            'habitaciones.ver',
            'habitaciones.editar',

            'clientes.ver',
            'clientes.crear',
            'clientes.editar',

            'checkin_checkout.ver',
            'checkin_checkout.crear',
            'checkin_checkout.editar',

            'limpieza.ver',

            'pagos.ver',
            'pagos.crear',
            'pagos.editar',

            'servicios.ver',
            'servicios.crear',
            'servicios.editar',

            'empleados.ver',

            'temporadas.ver',

            'reportes.ver',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Permisos de LIMPIEZA
        |--------------------------------------------------------------------------
        */

        $limpieza->syncPermissions([
            'dashboard.ver',

            'habitaciones.ver',

            'limpieza.ver',
            'limpieza.editar',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Usuario inicial Super Admin
        |--------------------------------------------------------------------------
        */

        $admin = User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@hotel.com',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
        }
    }
}