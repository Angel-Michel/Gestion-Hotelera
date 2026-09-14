<?php

namespace App\Livewire;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
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

    public string $telefono = '';

    public string $puesto = '';

    public string $salario = '';

    public string $horario = '';

    public bool $esta_activo = true;

    public string $id_usuario = '';

    public string $rol = '';

    public ?string $mensajeExito = null;

    public function updatedBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $empleados = Empleado::with('usuario.roles')
            ->when($this->busqueda !== '', function ($query): void {
                $termino = '%'.trim($this->busqueda).'%';
                $query->where(function ($sub) use ($termino): void {
                    $sub->where('nombre', 'like', $termino)
                        ->orWhere('apellidos', 'like', $termino)
                        ->orWhere('puesto', 'like', $termino);
                });
            })
            ->when($this->filtroEstado === 'activos', fn ($query) => $query->where('esta_activo', true))
            ->when($this->filtroEstado === 'inactivos', fn ($query) => $query->where('esta_activo', false))
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.empleados', [
            'empleados' => $empleados,
            'usuarios' => User::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function crear(): void
    {
        $this->reset(['empleadoId', 'nombre', 'apellidos', 'telefono', 'puesto', 'salario', 'horario', 'id_usuario', 'rol']);
        $this->esta_activo = true;
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function editar(int $id): void
    {
        $empleado = Empleado::with('usuario.roles')->where('id_empleado', $id)->firstOrFail();

        $this->empleadoId = $empleado->id_empleado;
        $this->nombre = $empleado->nombre;
        $this->apellidos = $empleado->apellidos;
        $this->telefono = $empleado->telefono ?? '';
        $this->puesto = $empleado->puesto;
        $this->salario = (string) ($empleado->salario ?? '');
        $this->horario = $empleado->horario ?? '';
        $this->esta_activo = (bool) $empleado->esta_activo;
        $this->id_usuario = $empleado->id_usuario ? (string) $empleado->id_usuario : '';
        $this->rol = $empleado->usuario?->roles->first()?->name ?? '';
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset(['empleadoId', 'nombre', 'apellidos', 'telefono', 'puesto', 'salario', 'horario', 'id_usuario', 'rol']);
        $this->resetValidation();
    }

    public function guardar(): void
    {
        abort_unless(auth()->user()?->hasRole('super-admin'), 403);

        $this->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'puesto' => ['required', 'string', 'max:100'],
            'salario' => ['nullable', 'numeric', 'min:0'],
            'horario' => ['nullable', 'string', 'max:255'],
            'id_usuario' => ['nullable', 'exists:users,id'],
            'rol' => ['nullable', 'exists:roles,name'],
        ]);

        $datos = [
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono ?: null,
            'puesto' => $this->puesto,
            'salario' => $this->salario ?: null,
            'horario' => $this->horario ?: null,
            'esta_activo' => $this->esta_activo,
            'id_usuario' => $this->id_usuario ?: null,
        ];

        if ($this->empleadoId) {
            Empleado::where('id_empleado', $this->empleadoId)->firstOrFail()->update($datos);
            $this->mensajeExito = 'Empleado actualizado correctamente.';
        } else {
            $empleado = Empleado::create($datos);
            $this->empleadoId = $empleado->id_empleado;
            $this->mensajeExito = 'Empleado creado correctamente.';
        }

        $this->asignarRolAlUsuario();

        $this->cerrarModal();
    }

    protected function asignarRolAlUsuario(): void
    {
        if ($this->id_usuario === '' || $this->rol === '') {
            return;
        }

        $usuario = User::find($this->id_usuario);

        if ($usuario) {
            $usuario->syncRoles([$this->rol]);
        }
    }

    public function toggleActivo(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('super-admin'), 403);

        $empleado = Empleado::where('id_empleado', $id)->firstOrFail();
        $empleado->update(['esta_activo' => ! $empleado->esta_activo]);

        $this->mensajeExito = $empleado->esta_activo
            ? 'Empleado activado correctamente.'
            : 'Empleado desactivado correctamente.';
    }

    public function eliminar(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('super-admin'), 403);

        Empleado::where('id_empleado', $id)->firstOrFail()->delete();

        $this->mensajeExito = 'Empleado eliminado correctamente.';
    }
}
