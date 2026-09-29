<?php

namespace App\Livewire\Servicios;

use App\Models\Servicio;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Alta y edición de un servicio del catálogo dentro del diálogo.
 *
 * El componente se monta con la pantalla, nunca de forma diferida: Livewire
 * descarta los eventos dirigidos a un componente `#[Lazy]` que todavía no se ha
 * cargado, así que el primer clic sobre una fila abría el formulario de alta en
 * lugar del servicio. Con el componente siempre presente, abrir y cargar son una
 * sola ejecución de servidor.
 *
 * La misma vista sirve para los dos modos; lo que cambia es la cabecera y el
 * botón, y eso lo decide `render()`.
 */
class FormModal extends Component
{
    public ?int $servicioId = null;

    public string $nombre = '';

    public string $descripcion = '';

    public string $categoria = '';

    public string $precio = '';

    /**
     * El diálogo del catálogo está abierto. La misma petición que carga los
     * datos lo activa, de modo que el formulario jamás se presenta con los
     * valores de otro servicio, y `render()` lo toma como modo en curso.
     */
    public bool $isOpenEditModal = false;

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
        return view($this->isOpenEditModal && $this->servicioId !== null ? 'servicios.edit' : 'servicios.create');
    }

    /**
     * Abre el formulario de alta. Lo dispara el botón "Nuevo servicio" de la
     * pantalla.
     */
    #[On('servicio-crear')]
    public function crear(): void
    {
        $this->servicioId = null;

        $this->cargarFormulario();

        $this->abrirDialogo();
    }

    /**
     * Abre el formulario con los datos del servicio indicado. Cargar y mostrar
     * ocurren en la misma petición, que es lo que exige el botón de la fila: un
     * solo clic y el diálogo aparece con la categoría y el precio del servicio.
     */
    #[On('servicio-editar')]
    public function editar(int $id): void
    {
        $this->servicioId = $id;

        $this->cargarFormulario();

        $this->abrirDialogo();
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

        $this->servicioId = null;

        $this->isOpenEditModal = false;

        $this->cargarFormulario();

        $this->dispatch(
            'servicio-guardada',
            mensaje: $esNuevo
                ? 'Servicio creado correctamente.'
                : 'Servicio actualizado correctamente.'
        );
    }

    /**
     * Marca el diálogo como abierto y pide a Flux que lo muestre.
     *
     * Livewire entrega los eventos despachados después de aplicar el morph del
     * componente, así que el <dialog> se abre cuando el formulario ya muestra
     * los datos recién cargados.
     */
    private function abrirDialogo(): void
    {
        $this->isOpenEditModal = true;

        $this->dispatch('modal-show', name: 'servicio-form');
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
