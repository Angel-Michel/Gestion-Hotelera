<?php

namespace App\Livewire;

use App\Models\Servicio;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Servicios extends Component
{
    public bool $mostrarModal = false;

    public ?int $servicioId = null;

    public string $nombre = '';

    public string $precio = '';

    public ?string $mensajeExito = null;

    public function render(): View
    {
        return view('servicios.index', [
            'servicios' => Servicio::withCount('reservasServicio')->orderBy('nombre')->get(),
        ]);
    }

    public function crear(): void
    {
        $this->reset(['servicioId', 'nombre', 'precio']);
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function editar(int $id): void
    {
        $servicio = Servicio::findOrFail($id);

        $this->servicioId = $servicio->id;
        $this->nombre = $servicio->nombre;
        $this->precio = (string) $servicio->precio;
        $this->resetValidation();
        $this->reset('mensajeExito');
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset(['servicioId', 'nombre', 'precio']);
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        if ($this->servicioId) {
            Servicio::findOrFail($this->servicioId)->update([
                'nombre' => $this->nombre,
                'precio' => $this->precio,
            ]);
            $this->mensajeExito = 'Servicio actualizado correctamente.';
        } else {
            Servicio::create([
                'nombre' => $this->nombre,
                'precio' => $this->precio,
            ]);
            $this->mensajeExito = 'Servicio creado correctamente.';
        }

        $this->cerrarModal();
    }

    public function eliminar(int $id): void
    {
        Servicio::findOrFail($id)->delete();

        $this->mensajeExito = 'Servicio eliminado correctamente.';
    }
}
