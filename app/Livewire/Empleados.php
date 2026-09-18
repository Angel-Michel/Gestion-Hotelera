<?php

namespace App\Livewire;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app')]
class Empleados extends Component
{
    use WithPagination;

    public bool $mostrarModal = false;

    public ?int $empleadoId = null;

    public string $busqueda = '';

    public string $filtroEstado = 'todos';

    public string $nombre = '';

    public string $apellidos = '';

    public string $correo_electronico = '';

    public string $contrasena = '';

    public string $rol = '';

    public bool $acceso_sistema = false;

    public string $telefono = '';

    public string $salario = '';

    public string $turno = '';

    public bool $esta_activo = true;

    public ?string $mensajeExito = null;

    public ?string $mensajeError = null;

    public bool $mostrarModalPermisos = false;

    public ?int $empleadoIdPermisos = null;

    public ?string $empleadoNombrePermisos = null;

    /** @var list<string> */
    public array $permisosUsuario = [];

    public const TURNOS = ['Mañana', 'Tarde', 'Noche', 'Rotativo'];

    public function updatedBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado(): void
    {
        $this->resetPage();
    }

    /**
     * Solo el Super Admin puede administrar al personal.
     */
    public function esSuperAdmin(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public function render(): View
    {
        $empleados = Empleado::with('usuario.roles')
            ->when($this->busqueda !== '', function ($query): void {
                $termino = '%'.trim($this->busqueda).'%';
                $query->where(function ($sub) use ($termino): void {
                    $sub->where('nombre', 'like', $termino)
                        ->orWhere('apellidos', 'like', $termino)
                        ->orWhere('puesto', 'like', $termino)
                        ->orWhere('correo_electronico', 'like', $termino);
                });
            })
            ->when($this->filtroEstado === 'activos', fn ($query) => $query->where('esta_activo', true))
            ->when($this->filtroEstado === 'inactivos', fn ($query) => $query->where('esta_activo', false))
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.empleados', [
            'empleados' => $empleados,
            'turnos' => self::TURNOS,
        ]);
    }

    /**
     * Roles del sistema asignables desde el formulario (sin Super Admin).
     */
    #[Computed]
    public function rolesDisponibles()
    {
        return Role::where('name', '!=', 'super-admin')->orderBy('name')->get();
    }

    /**
     * Permisos del sistema agrupados por módulo para la matriz granular.
     */
    #[Computed]
    public function permisosPorModulo()
    {
        return Permission::orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permiso) => Str::before($permiso->name, '.'));
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

    public function crear(): void
    {
        $this->reset([
            'empleadoId',
            'nombre',
            'apellidos',
            'correo_electronico',
            'contrasena',
            'rol',
            'telefono',
            'salario',
            'turno',
        ]);
        $this->acceso_sistema = true;
        $this->esta_activo = true;
        $this->turno = 'Mañana';
        $this->resetValidation();
        $this->reset('mensajeExito', 'mensajeError');
        $this->mostrarModal = true;
    }

    public function editar(int $id): void
    {
        $empleado = Empleado::with('usuario.roles')->where('id_empleado', $id)->firstOrFail();

        $this->empleadoId = $empleado->id_empleado;
        $this->nombre = $empleado->nombre;
        $this->apellidos = $empleado->apellidos;
        $this->correo_electronico = $empleado->correo_electronico ?? $empleado->usuario?->email ?? '';
        $this->telefono = $empleado->telefono ?? '';
        $this->salario = (string) ($empleado->salario ?? '');
        $this->turno = $empleado->turno ?? '';
        $this->esta_activo = (bool) $empleado->esta_activo;
        $this->acceso_sistema = (bool) $empleado->id_usuario;
        $rolActual = $empleado->usuario?->roles->first()?->name ?? '';
        $this->rol = $rolActual === 'super-admin' ? '' : $rolActual;
        $this->contrasena = '';
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset([
            'empleadoId',
            'nombre',
            'apellidos',
            'correo_electronico',
            'contrasena',
            'rol',
            'telefono',
            'salario',
            'turno',
        ]);
        $this->resetValidation();
    }

    public function guardar(): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $this->validate($this->reglasDeValidacion());

        $datos = [
            'nombre' => trim($this->nombre),
            'apellidos' => trim($this->apellidos),
            'correo_electronico' => $this->correo_electronico,
            'telefono' => $this->telefono ?: null,
            'salario' => $this->salario !== '' ? $this->salario : null,
            'turno' => $this->turno ?: null,
            'esta_activo' => $this->esta_activo,
        ];

        if ($this->acceso_sistema) {
            $usuario = $this->sincronizarUsuario();
            $datos['id_usuario'] = $usuario->id;
        } elseif ($this->empleadoId) {
            $this->revocarAccesoUsuario();
            $datos['id_usuario'] = null;
        }

        if ($this->empleadoId) {
            Empleado::where('id_empleado', $this->empleadoId)->firstOrFail()->update($datos);
            $this->mensajeExito = 'Empleado actualizado correctamente.';
        } else {
            Empleado::create($datos);
            $this->mensajeExito = 'Empleado creado correctamente.';
        }

        $this->cerrarModal();
    }

    /**
     * @return array<string, array>
     */
    protected function reglasDeValidacion(): array
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'correo_electronico' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'salario' => ['nullable', 'numeric', 'min:0'],
            'turno' => ['nullable', Rule::in(self::TURNOS)],
            'rol' => ['nullable', Rule::exists('roles', 'name'), Rule::notIn(['super-admin'])],
            'esta_activo' => ['boolean'],
            'acceso_sistema' => ['boolean'],
        ];

        if ($this->requiereNuevaContrasena()) {
            $rules['contrasena'] = ['required', 'string', 'min:8'];
        }

        if ($this->acceso_sistema) {
            $rules['correo_electronico'][] = Rule::unique('users', 'email')
                ->ignore($this->idUsuarioVinculado() ?? 0);
        }

        return $rules;
    }

    /**
     * La contraseña inicial solo es obligatoria cuando se dará de alta un usuario del sistema.
     */
    protected function requiereNuevaContrasena(): bool
    {
        if (! $this->acceso_sistema) {
            return false;
        }

        $id = $this->idUsuarioVinculado();

        return $id === null || User::whereKey($id)->doesntExist();
    }

    /**
     * Valor actual del vínculo con un usuario del sistema para el empleado en edición.
     */
    protected function idUsuarioVinculado(): ?int
    {
        return $this->empleadoId
            ? Empleado::where('id_empleado', $this->empleadoId)->value('id_usuario')
            : null;
    }

    /**
     * Crea el usuario del sistema o actualiza el vinculado, con su contraseña y rol.
     */
    protected function sincronizarUsuario(): User
    {
        $usuario = $this->idUsuarioVinculado() ? User::find($this->idUsuarioVinculado()) : null;

        $nombreCompleto = trim($this->nombre.' '.$this->apellidos);

        if ($usuario) {
            $usuario->name = $nombreCompleto;
            $usuario->email = $this->correo_electronico;
            $usuario->activo = true;

            if ($this->contrasena !== '') {
                $usuario->password = Hash::make($this->contrasena);
            }

            $usuario->save();
        } else {
            $usuario = User::create([
                'name' => $nombreCompleto,
                'email' => $this->correo_electronico,
                'password' => Hash::make($this->contrasena),
                'activo' => true,
            ]);
        }

        $this->asignarRol($usuario);

        return $usuario;
    }

    /**
     * Asigna el rol seleccionado al usuario. Super Admin nunca se asigna desde
     * este formulario; si el usuario ya lo posee y no hay cambio, se conserva.
     */
    protected function asignarRol(User $usuario): void
    {
        if ($this->rol !== '') {
            $usuario->syncRoles([$this->rol]);

            return;
        }

        if (! $usuario->hasRole('super-admin')) {
            $usuario->syncRoles([]);
        }
    }

    /**
     * Sin acceso al sistema: se bloquea el login del usuario vinculado, si existe.
     */
    protected function revocarAccesoUsuario(): void
    {
        $id = $this->idUsuarioVinculado();

        if ($id) {
            User::whereKey($id)->update(['activo' => false]);
        }
    }

    public function toggleActivo(int $id): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $empleado = Empleado::where('id_empleado', $id)->firstOrFail();
        $empleado->update(['esta_activo' => ! $empleado->esta_activo]);

        $this->mensajeExito = $empleado->esta_activo
            ? 'Empleado activado correctamente.'
            : 'Empleado desactivado correctamente.';
    }

    public function eliminar(int $id): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $empleado = Empleado::where('id_empleado', $id)->firstOrFail();

        if ($empleado->id_usuario) {
            User::whereKey($empleado->id_usuario)->update(['activo' => false]);
        }

        $empleado->delete();

        $this->mensajeExito = 'Empleado eliminado correctamente.';
    }

    /**
     * Abre la matriz de seguridad granular del empleado. Solo el Super Admin
     * puede configurar los permisos directos de un usuario.
     */
    public function abrirModalPermisos(int $id): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $empleado = Empleado::with('usuario')->where('id_empleado', $id)->firstOrFail();

        if ($empleado->usuario === null) {
            $this->mensajeError = 'Este empleado no tiene acceso al sistema; asígnale una cuenta para configurar sus permisos.';
            $this->reset('mensajeExito');

            return;
        }

        $this->empleadoIdPermisos = $empleado->id_empleado;
        $this->empleadoNombrePermisos = trim($empleado->nombre.' '.$empleado->apellidos);
        $this->permisosUsuario = $empleado->usuario->getDirectPermissions()
            ->pluck('name')
            ->all();
        $this->resetValidation();
        $this->mostrarModalPermisos = true;
        $this->reset('mensajeError');
    }

    public function cerrarModalPermisos(): void
    {
        $this->mostrarModalPermisos = false;
        $this->reset('empleadoIdPermisos', 'empleadoNombrePermisos', 'permisosUsuario');
        $this->resetValidation();
    }

    /**
     * Sincroniza los permisos directos (Spatie) del usuario vinculado al empleado.
     */
    public function guardarPermisosGranulares(): void
    {
        abort_unless($this->esSuperAdmin(), 403);

        $empleado = Empleado::with('usuario')->where('id_empleado', $this->empleadoIdPermisos)->firstOrFail();

        abort_unless($empleado->usuario !== null, 403);

        $nombresValidos = Permission::pluck('name')->all();

        $nombres = array_values(array_intersect($this->permisosUsuario, $nombresValidos));

        $empleado->usuario->syncPermissions($nombres);

        $this->mensajeExito = 'Permisos del empleado actualizados correctamente.';
        $this->cerrarModalPermisos();
    }
}
