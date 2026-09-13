<?php

namespace App\Livewire;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Empleados extends Component
{
    public bool $mostrarModal = false;

    public ?int $empleadoId = null;

    public string $nombre = '';

    public string $apellidos = '';

    public string $telefono = '';

    public string $puesto = '';

    public string $salario = '';

    public string $horario = '';

    public bool $esta_activo = true;

    public string $id_usuario = '';

    public ?string $mensajeExito = null;

    public function render(): View
    {
        return view('livewire.empleados', [
            'empleados' => Empleado::with('usuario')->orderBy('nombre')->get(),
            'usuarios' => User::orderBy('name')->get(),
        ]);
    }

    public function crear(): void
    {
        $this->reset(['empleadoId', 'nombre', 'apellidos', 'telefono', 'puesto', 'salario', 'horario', 'id_usuario']);
        $this->esta_activo = true;
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function editar(int $id): void
    {
        $empleado = Empleado::findOrFail($id);

        $this->empleadoId = $empleado->id_empleado;
        $this->nombre = $empleado->nombre;
        $this->apellidos = $empleado->apellidos;
        $this->telefono = $empleado->telefono ?? '';
        $this->puesto = $empleado->puesto;
        $this->salario = (string) ($empleado->salario ?? '');
        $this->horario = $empleado->horario ?? '';
        $this->esta_activo = (bool) $empleado->esta_activo;
        $this->id_usuario = $empleado->id_usuario ? (string) $empleado->id_usuario : '';
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset(['empleadoId', 'nombre', 'apellidos', 'telefono', 'puesto', 'salario', 'horario', 'id_usuario']);
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'puesto' => ['required', 'string', 'max:100'],
            'salario' => ['nullable', 'numeric', 'min:0'],
            'horario' => ['nullable', 'string', 'max:255'],
            'id_usuario' => ['nullable', 'exists:users,id'],
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
            Empleado::create($datos);
            $this->mensajeExito = 'Empleado creado correctamente.';
        }

        $this->cerrarModal();
    }

    public function eliminar(int $id): void
    {
        Empleado::where('id_empleado', $id)->firstOrFail()->delete();

        $this->mensajeExito = 'Empleado eliminado correctamente.';
    }
}
