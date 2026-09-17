<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app')]
class MatrizPermisos extends Component
{
    public ?string $mensajeExito = null;

    public ?string $mensajeError = null;

    public function render(): View
    {
        return view('livewire.matriz-permisos');
    }

    /**
     * Roles cuyos permisos no pueden modificarse desde la matriz.
     *
     * @return list<string>
     */
    protected function rolesInmutables(): array
    {
        return ['super-admin', 'superadmin', 'super admin'];
    }

    public function normalizarNombre(string $nombre): string
    {
        return Str::lower(preg_replace('/\s+/', ' ', trim($nombre)) ?? '');
    }

    public function esRolInmutable(string $nombre): bool
    {
        return in_array($this->normalizarNombre($nombre), $this->rolesInmutables(), true);
    }

    /**
     * Solo el Super Admin puede alternar los permisos de la matriz.
     */
    public function puedeEditar(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public function alternarPermiso(int $roleId, int $permisoId): void
    {
        abort_unless($this->puedeEditar(), 403);

        $rol = Role::findOrFail($roleId);
        $permiso = Permission::findOrFail($permisoId);

        if ($this->esRolInmutable($rol->name)) {
            $this->mensajeError = "El rol \"{$rol->name}\" es del sistema; sus permisos no pueden modificarse.";
            $this->reset('mensajeExito');

            return;
        }

        if ($rol->hasPermissionTo($permiso->name)) {
            $rol->revokePermissionTo($permiso->name);
            $this->mensajeExito = "Permiso \"{$permiso->name}\" retirado del rol \"{$rol->name}\".";
        } else {
            $rol->givePermissionTo($permiso->name);
            $this->mensajeExito = "Permiso \"{$permiso->name}\" asignado al rol \"{$rol->name}\".";
        }

        $this->reset('mensajeError');
        unset($this->roles);
    }

    /**
     * @return list<int>
     */
    protected function idsPermisosDelRol(Role $rol): array
    {
        return $rol->permissions
            ->pluck('id')
            ->map(fn (mixed $id): int => (int) $id)
            ->all();
    }

    public function rolTienePermiso(Role $rol, int $permisoId): bool
    {
        return in_array($permisoId, $this->idsPermisosDelRol($rol), true);
    }

    public function etiquetaModulo(string $modulo): string
    {
        return match ($modulo) {
            'dashboard' => 'Dashboard',
            'checkin_checkout' => 'Check-in / Check-out',
            'roles_permisos' => 'Roles y permisos',
            'configuracion' => 'Configuración',
            default => Str::headline($modulo),
        };
    }

    public function etiquetaAccion(string $accion): string
    {
        return match ($accion) {
            'ver' => 'Ver',
            'crear' => 'Crear',
            'editar' => 'Editar',
            'eliminar' => 'Eliminar',
            default => Str::headline($accion),
        };
    }

    /**
     * Roles registrados en el sistema.
     */
    #[Computed]
    public function roles()
    {
        return Role::with('permissions')
            ->withCount('permissions')
            ->orderByRaw("
                CASE name
                    WHEN 'super-admin' THEN 1
                    WHEN 'gerente' THEN 2
                    WHEN 'recepcionista' THEN 3
                    WHEN 'limpieza' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('name')
            ->get();
    }

    /**
     * Permisos agrupados por módulo.
     */
    #[Computed]
    public function permisosPorModulo()
    {
        return Permission::orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permiso) => Str::before($permiso->name, '.'));
    }
}
