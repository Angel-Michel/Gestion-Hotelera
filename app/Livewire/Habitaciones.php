<?php

namespace App\Livewire;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Habitaciones extends Component
{
    public bool $mostrarModal = false;

    public ?int $habitacionId = null;

    public string $numero_habitacion = '';

    public string $tipo_habitacion_id = '';

    public string $estado = 'Disponible';

    public string $piso = '1';

    public ?string $mensajeExito = null;

    public function render(): View
    {
        return view('livewire.habitaciones', [
            'habitaciones' => Habitacion::with('tipo')->orderBy('numero_habitacion')->get(),
            'tipos' => TipoHabitacion::orderBy('nombre')->get(),
            'estados' => ['Disponible', 'Ocupada', 'Mantenimiento', 'Limpieza'],
        ]);
    }

    public function crear(): void
    {
        $this->reset(['habitacionId', 'numero_habitacion', 'tipo_habitacion_id']);
        $this->estado = 'Disponible';
        $this->piso = '1';
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function editar(int $id): void
    {
        $habitacion = Habitacion::findOrFail($id);

        $this->habitacionId = $habitacion->id;
        $this->numero_habitacion = $habitacion->numero_habitacion;
        $this->tipo_habitacion_id = (string) $habitacion->tipo_habitacion_id;
        $this->estado = $habitacion->estado;
        $this->piso = (string) $habitacion->piso;
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset(['habitacionId', 'numero_habitacion', 'tipo_habitacion_id']);
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate([
            'numero_habitacion' => ['required', 'string', 'max:10', 'unique:habitaciones,numero_habitacion,'.$this->habitacionId],
            'tipo_habitacion_id' => ['required', 'exists:tipos_habitacion,id'],
            'estado' => ['required', 'in:Disponible,Ocupada,Mantenimiento,Limpieza'],
            'piso' => ['required', 'integer', 'min:1'],
        ]);

        $datos = [
            'numero_habitacion' => $this->numero_habitacion,
            'tipo_habitacion_id' => $this->tipo_habitacion_id,
            'estado' => $this->estado,
            'piso' => $this->piso,
        ];

        if ($this->habitacionId) {
            Habitacion::findOrFail($this->habitacionId)->update($datos);
            $this->mensajeExito = 'Habitación actualizada correctamente.';
        } else {
            Habitacion::create($datos);
            $this->mensajeExito = 'Habitación creada correctamente.';
        }

        $this->cerrarModal();
    }

    public function eliminar(int $id): void
    {
        Habitacion::findOrFail($id)->delete();

        $this->mensajeExito = 'Habitación eliminada correctamente.';
    }
}
