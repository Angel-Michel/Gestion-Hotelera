<?php

namespace App\Livewire\Servicios;

use App\Models\Servicio;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Alta y edición de un servicio del catálogo dentro del diálogo. Se carga de
 * forma diferida y no lleva booleanos de visibilidad: el <dialog> de Flux lo abre
 * el navegador, así que el componente solo recibe el modo de la operación por el
 * evento `abrir-formulario` y avisa con `servicio-guardada` al terminar.
 *
 * La misma vista sirve para los dos modos; lo que cambia es la cabecera y el
 * botón, y eso lo decide `render()`.
 */
#[Lazy]
class FormModal extends Component
{
    public ?int $servicioId = null;

    public string $nombre = '';

    public string $descripcion = '';

    public string $categoria = '';

    public string $precio = '';

    public function mount(): void
    {
        $this->cargarFormulario();
    }

    /**
     * La vista de alta y la de edición comparten el formulario; solo difieren en
     * el rótulo del modo en curso.
     */
    public function render()
    {
        return view($this->servicioId === null ? 'servicios.create' : 'servicios.edit');
    }

    /**
     * Prepara el formulario para creación o edición.
     *
     * Lo dispara el botón de la interfaz con un evento de Livewire. Solo este
     * componente reacciona, así que el contenedor no se re-renderiza al abrir y
     * el <dialog> conserva su estado nativo de `open`.
     */
    #[On('abrir-formulario')]
    public function preparar(?int $id): void
    {
        $this->servicioId = $id;

        $this->cargarFormulario();
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('servicios', 'nombre')->ignore($this->servicioId)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'categoria' => ['required', Rule::in(Servicio::CATEGORIAS)],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
        ]);

        $servicio = $this->servicioId === null
            ? new Servicio
            : Servicio::findOrFail($this->servicioId);

        $esNuevo = ! $servicio->exists;

        $servicio->fill([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion !== '' ? $this->descripcion : null,
            'categoria' => $this->categoria,
            'precio' => $this->precio,
        ])->save();

        $this->cargarFormulario();

        $this->dispatch(
            'servicio-guardada',
            mensaje: $esNuevo
                ? 'Servicio creado correctamente.'
                : 'Servicio actualizado correctamente.'
        );
    }

    private function cargarFormulario(): void
    {
        $servicio = $this->servicioId === null
            ? null
            : Servicio::findOrFail($this->servicioId);

        $this->resetValidation();

        if ($servicio === null) {
            $this->servicioId = null;
            $this->nombre = '';
            $this->descripcion = '';
            $this->categoria = Servicio::CATEGORIA_POR_DEFECTO;
            $this->precio = '';

            return;
        }

        $this->nombre = $servicio->nombre;
        $this->descripcion = $servicio->descripcion ?? '';
        $this->categoria = $servicio->categoria ?? Servicio::CATEGORIA_POR_DEFECTO;
        $this->precio = number_format((float) $servicio->precio, 2, '.', '');
    }
}
