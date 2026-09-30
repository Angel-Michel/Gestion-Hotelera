<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\ReservaServicio;
use App\Models\Servicio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Servicios extends Component
{
    use WithPagination;

    /**
     * Catálogo cerrado de servicios que el hotel ofrece, para estandarizar la
     * captura y evitar nombres escritos a mano con errores tipográficos.
     *
     * @var array<int, string>
     */
    public const NOMBRES_DISPONIBLES = [
        'Servicio a la habitación',
        'Desayuno buffet',
        'Lavandería',
        'Spa y masajes',
        'Traslado al aeropuerto',
    ];

    /**
     * Número de filas por página en la tabla de servicios.
     */
    public const POR_PAGINA = 10;

    public bool $mostrarModal = false;

    public ?int $servicioId = null;

    public string $nombre = '';

    public string $descripcion = '';

    public string $precio = '';

    /**
     * Categoría elegida en el desplegable del modal. Es independiente de
     * $categoriaFiltro, que pertenece al filtro de la tabla: abrir el modal de
     * edición no debe alterar los resultados que el usuario está viendo.
     */
    public ?int $categoria_id = null;

    /**
     * Texto del buscador. Cada pulsación vuelve a la primera página para que el
     * usuario no aterrice en una página vacía.
     */
    #[Url(as: 'search', except: '')]
    public string $search = '';

    /**
     * Categoría seleccionada en el filtro. Cadena vacía significa "todas".
     */
    #[Url(as: 'categoria', except: '')]
    public string $categoriaFiltro = '';

    /**
     * Nombre que ya estaba persistido y no pertenece al catálogo. Al estandarizar
     * el campo en un <select> esos servicios quedarían sin opción visible, así que
     * se conservan como opción editable mientras se edita ese registro.
     */
    #[Locked]
    public ?string $nombreFueraDeCatalogo = null;

    /**
     * Nombres aceptados por el formulario: el catálogo cerrado más, en edición,
     * el nombre previo del servicio cuando quedó fuera de él.
     *
     * @return array<int, string>
     */
    public function nombresDisponibles(): array
    {
        if ($this->nombreFueraDeCatalogo === null) {
            return self::NOMBRES_DISPONIBLES;
        }

        return [...self::NOMBRES_DISPONIBLES, $this->nombreFueraDeCatalogo];
    }

    public function render(): View
    {
        return view('servicios.index', [
            'servicios' => $this->consultaPaginada(),
            'categorias' => Categoria::query()->activas()->ordenadasPorNombre()->get(),
            'categoriasModal' => Categoria::query()->ordenadasPorNombre()->get(),
            'kpis' => $this->kpis(),
            'nombresServicio' => $this->nombresDisponibles(),
        ]);
    }

    public function actualizarSearch(): void
    {
        $this->resetPage();
    }

    public function actualizarCategoria(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->reset(['search', 'categoriaFiltro']);
        $this->resetPage();
    }

    public function crear(): void
    {
        $this->reset([
            'servicioId',
            'nombre',
            'descripcion',
            'categoria_id',
            'precio',
        ]);
        $this->nombreFueraDeCatalogo = null;
        $this->resetValidation();
        $this->mostrarModal = true;
    }

    public function editar(int $id): void
    {
        $servicio = Servicio::findOrFail($id);

        $this->servicioId = $servicio->id;
        $this->nombre = $servicio->nombre;
        $this->nombreFueraDeCatalogo = in_array($servicio->nombre, self::NOMBRES_DISPONIBLES, true)
            ? null
            : $servicio->nombre;
        $this->descripcion = $servicio->descripcion ?? '';
        $this->categoria_id = $servicio->categoria_id;
        $this->precio = (string) $servicio->precio;
        $this->resetValidation();
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset([
            'servicioId',
            'nombre',
            'descripcion',
            'categoria_id',
            'precio',
        ]);
        $this->nombreFueraDeCatalogo = null;
        $this->resetValidation();
    }

    /**
     * Activa o desactiva un servicio sin eliminarlo, para retirarlo del catálogo
     * conservando su historial de contrataciones.
     */
    public function alternarActivo(int $id): void
    {
        $servicio = Servicio::findOrFail($id);

        $servicio->update(['activo' => ! $servicio->activo]);

        $this->notificar(
            $servicio->activo
                ? 'Servicio activado correctamente.'
                : 'Servicio desactivado correctamente.'
        );
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre' => ['required', 'string', Rule::in($this->nombresDisponibles())],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        $datos = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion !== '' ? $this->descripcion : null,
            'categoria_id' => $this->categoria_id,
            'precio' => $this->precio,
        ];

        if ($this->servicioId) {
            Servicio::findOrFail($this->servicioId)->update($datos);
            $mensaje = 'Servicio actualizado correctamente.';
        } else {
            Servicio::create($datos);
            $mensaje = 'Servicio creado correctamente.';
        }

        $this->cerrarModal();
        $this->notificar($mensaje);
    }

    public function eliminar(int $id): void
    {
        Servicio::findOrFail($id)->delete();

        $this->notificar('Servicio eliminado correctamente.');
    }

    /**
     * Emite la confirmación hacia la alerta de Alpine. El evento se escucha en la
     * ventana, así que el toast se pinta en cuanto Livewire recibe la respuesta,
     * sin re-renderizar el bloque de texto.
     */
    private function notificar(string $mensaje): void
    {
        $this->dispatch('notificacion', mensaje: $mensaje);
    }

    /**
     * Consulta filtrada por el buscador y la categoría, paginada para no cargar
     * el catálogo completo en memoria.
     *
     * @return LengthAwarePaginator<int, Servicio>
     */
    private function consultaPaginada(): LengthAwarePaginator
    {
        return $this->consultaFiltrada()
            ->with('categoria')
            ->withCount('reservasServicio')
            ->orderBy('nombre')
            ->paginate(self::POR_PAGINA);
    }

    /**
     * @return Builder<Servicio>
     */
    private function consultaFiltrada(): Builder
    {
        $buscar = trim($this->search);

        return Servicio::query()
            ->when($buscar !== '', function (Builder $query) use ($buscar): void {
                $query->where(function (Builder $interno) use ($buscar): void {
                    $interno->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->when(
                $this->categoriaFiltro !== '',
                fn (Builder $query) => $query->where('categoria_id', $this->categoriaFiltro)
            );
    }

    /**
     * Indicadores de la cabecera. Describen el hotel completo y no la selección
     * actual, para que la fila de métricas no salte mientras el usuario filtra.
     *
     * @return array{totalServicios: int, categoriasActivas: int, cargosRegistrados: int, consumosFacturados: float}
     */
    private function kpis(): array
    {
        return [
            'totalServicios' => Servicio::query()->count(),
            'categoriasActivas' => Categoria::query()->activas()->count(),
            'cargosRegistrados' => ReservaServicio::query()->count(),
            'consumosFacturados' => (float) ReservaServicio::query()
                ->whereHas('reserva', fn (Builder $query) => $query->where('estado', 'Finalizada'))
                ->sum('subtotal'),
        ];
    }
}
