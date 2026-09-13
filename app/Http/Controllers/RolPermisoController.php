<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolPermisoController extends Controller
{
    public function index()
    {
        $roles = Role::whereIn('name', [
            'super-admin',
            'gerente',
            'recepcionista',
            'limpieza',
        ])
        ->orderByRaw("
            CASE name
                WHEN 'super-admin' THEN 1
                WHEN 'gerente' THEN 2
                WHEN 'recepcionista' THEN 3
                WHEN 'limpieza' THEN 4
                ELSE 5
            END
        ")
        ->get();

        $modulos = [
            'Dashboard' => 'dashboard',
            'Reservaciones' => 'reservaciones',
            'Habitaciones' => 'habitaciones',
            'Clientes' => 'clientes',
            'Check-in / Check-out' => 'checkin_checkout',
            'Limpieza' => 'limpieza',
            'Pagos' => 'pagos',
            'Servicios' => 'servicios',
            'Gastos' => 'gastos',
            'Empleados' => 'empleados',
            'Temporadas' => 'temporadas',
            'Reportes' => 'reportes',
            'Usuarios' => 'usuarios',
            'Roles y permisos' => 'roles_permisos',
            'Configuración' => 'configuracion',
        ];

        return view('roles-permisos.index', compact('roles', 'modulos'));
    }

    public function update(Request $request)
    {
        $modulos = [
            'dashboard',
            'reservaciones',
            'habitaciones',
            'clientes',
            'checkin_checkout',
            'limpieza',
            'pagos',
            'servicios',
            'gastos',
            'empleados',
            'temporadas',
            'reportes',
            'usuarios',
            'roles_permisos',
            'configuracion',
        ];

        $rolesPermitidos = [
            'super-admin',
            'gerente',
            'recepcionista',
            'limpieza',
        ];

        $permisos = $request->input('permisos', []);

        foreach ($rolesPermitidos as $nombreRol) {

            $rol = Role::where('name', $nombreRol)->first();

            if (!$rol) {
                continue;
            }

            foreach ($modulos as $modulo) {

                $nombrePermiso = $modulo . '.ver';

                $tienePermiso = isset($permisos[$nombreRol][$modulo]);

                if ($tienePermiso) {

                    $permiso = Permission::where('name', $nombrePermiso)->first();

                    if ($permiso) {
                        $rol->givePermissionTo($permiso);
                    }

                } else {

                    $permiso = Permission::where('name', $nombrePermiso)->first();

                    if ($permiso) {
                        $rol->revokePermissionTo($permiso);
                    }
                }
            }
        }

        return redirect()
            ->route('roles-permisos.index')
            ->with('success', 'Los permisos se actualizaron correctamente.');
    }
}