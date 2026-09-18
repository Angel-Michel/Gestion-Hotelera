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
class GestionRoles extends Component
{
    public bool $mostrarModalCrear = false;

    public bool $mostrarModalEditar = false;

    public bool $mostrarModalEliminar = false;

    public ?int $rolIdEditar = null;

    public ?int $rolAEliminar = null;

    public ?string $nombreRolAEliminar = null;

    public bool $seleccionarTodos = false;

    public string $nombre = '';

    /** @var list<string> */
    public array $permisosSeleccionados = [];

    public ?string $mensajeExito = null;

    public ?string $mensajeError = null;

    public function render(): View
    {
        return view('livewire.gestion-roles');
    }

    /**
     * Roles del sistema que no pueden eliminarse ni renombrarse.
     *
     * @return list<string>
     */
    protected function rolesProtegidos(): array
    {
        return ['super-admin', 'superadmin', 'super admin', 'gerente', 'recepcionista', 'limpieza'];
    }

    public function normalizarNombre(string $nombre): string
    {
        return Str::lower(preg_replace('/\s+/', ' ', trim($nombre)) ?? '');
    }

    public function esRolProtegido(string $nombre): bool
    {
        return in_array($this->normalizarNombre($nombre), $this->rolesProtegidos(), true);
    }

    /**
     * Solo el Super Admin puede crear, editar o eliminar roles.
     */
    public function esSuperAdmin(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public function abrirModalCrear(): void
    {
        $this->reset('nombre', 'permisosSeleccionados', 'seleccionarTodos');
        $this->resetValidation();
        $this->mostrarModalCrear = true;
    }

    public function cerrarModalCrear(): void
    {
        $this->mostrarModalCrear = false;
        $this->reset('nombre', 'permisosSeleccionados', 'seleccionarTodos');
        $this->resetValidation();
    }

    public function guardarRol(): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        $nombreRol = Str::lower(trim(preg_replace('/\s+/', ' ', $this->nombre) ?? ''));

        if (Role::whereRaw('LOWER(name) = ?', [$nombreRol])->exists()) {
            $this->addError('nombre', 'Ya existe un rol con ese nombre.');

            return;
        }

        $rol = Role::create(['name' => $nombreRol]);

        if (! empty($this->permisosSeleccionados)) {
            $nombresPermisos = Permission::whereIn('id', $this->permisosSeleccionados)
                ->pluck('name')
                ->all();

            $rol->syncPermissions($nombresPermisos);
        }

        unset($this->roles);

        $nombreCreado = $rol->name;

        $this->cerrarModalCrear();
        $this->mensajeExito = "Rol \"{$nombreCreado}\" creado correctamente.";
    }

    public function abrirModalEditar(int $roleId): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $rol = Role::with('permissions')->findOrFail($roleId);

        if ($this->esRolProtegido($rol->name)) {
            $this->mensajeError = "El rol \"{$rol->name}\" es del sistema y no puede modificarse.";
            $this->reset('mensajeExito');

            return;
        }

        $this->rolIdEditar = $rol->id;
        $this->nombre = $rol->name;
        $this->permisosSeleccionados = $rol->permissions->pluck('id')->map(fn (int $id): string => (string) $id)->all();
        $this->seleccionarTodos = count($this->permisosSeleccionados) === count($this->todosLosPermisosIds());
        $this->resetValidation();
        $this->mostrarModalEditar = true;
        $this->reset('mensajeError');
    }

    public function cerrarModalEditar(): void
    {
        $this->mostrarModalEditar = false;
        $this->reset('rolIdEditar', 'nombre', 'permisosSeleccionados', 'seleccionarTodos');
        $this->resetValidation();
    }

    public function actualizarRol(): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $rol = Role::findOrFail($this->rolIdEditar);

        if ($this->esRolProtegido($rol->name)) {
            $this->mensajeError = "El rol \"{$rol->name}\" es del sistema y no puede modificarse.";
            $this->reset('mensajeExito');
            $this->cerrarModalEditar();

            return;
        }

        $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        $nombreRol = Str::lower(trim(preg_replace('/\s+/', ' ', $this->nombre) ?? ''));

        if (Role::where('id', '!=', $rol->id)->whereRaw('LOWER(name) = ?', [$nombreRol])->exists()) {
            $this->addError('nombre', 'Ya existe un rol con ese nombre.');

            return;
        }

        $rol->update(['name' => $nombreRol]);

        $nombresPermisos = Permission::whereIn('id', $this->permisosSeleccionados)
            ->pluck('name')
            ->all();

        $rol->syncPermissions($nombresPermisos);

        unset($this->roles);

        $nombreActualizado = $rol->name;

        $this->cerrarModalEditar();
        $this->mensajeExito = "Rol \"{$nombreActualizado}\" actualizado correctamente.";
    }

    public function seleccionarRolAEliminar(int $roleId): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $rol = Role::findOrFail($roleId);

        $this->rolAEliminar = $rol->id;
        $this->nombreRolAEliminar = $rol->name;
        $this->mostrarModalEliminar = true;
        $this->reset('mensajeError');
    }

    public function cerrarModalEliminar(): void
    {
        $this->mostrarModalEliminar = false;
        $this->reset('rolAEliminar', 'nombreRolAEliminar');
    }

    public function eliminarRol(?int $roleId = null): void
    {
        $roleId ??= $this->rolAEliminar;

        abort_unless($roleId, 403);
        abort_unless($this->esSuperAdmin(), 403);

        $rol = Role::findOrFail($roleId);

        if ($this->esRolProtegido($rol->name)) {
            $this->mensajeError = "El rol \"{$rol->name}\" es del sistema y no puede eliminarse.";
            $this->reset('mensajeExito');
            $this->cerrarModalEliminar();

            return;
        }

        $rol->delete();

        unset($this->roles);

        $this->reset('mensajeError');
        $this->mensajeExito = "Rol \"{$rol->name}\" eliminado correctamente.";
        $this->cerrarModalEliminar();
    }

    public function updatedSeleccionarTodos(): void
    {
        $this->permisosSeleccionados = $this->seleccionarTodos ? $this->todosLosPermisosIds() : [];
    }

    public function updatedPermisosSeleccionados(): void
    {
        $seleccionados = array_intersect($this->permisosSeleccionados, $this->todosLosPermisosIds());

        $this->seleccionarTodos = count($seleccionados) === count($this->todosLosPermisosIds());
    }

    /**
     * @return list<string>
     */
    protected function todosLosPermisosIds(): array
    {
        return $this->permisosPorModulo
            ->flatten()
            ->pluck('id')
            ->map(fn (int $id): string => (string) $id)
            ->all();
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
            ->withCount(['permissions', 'users'])
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
