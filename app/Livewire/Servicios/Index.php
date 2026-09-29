<?php

namespace App\Livewire\Servicios;

use App\Models\ReservaServicio;
use App\Models\Servicio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Contenedor del módulo de servicios. Es el único dueño del estado global
 * (búsqueda, filtro de categoría, mensajes) y de los dos diálogos de la
 * pantalla: el alta y edición del catálogo, y el cargo de un consumo al folio
 * de una habitación ocupada. La presentación se delega en parciales y los
 * formularios viven en subcomponentes que se cargan de forma diferida.
 */
#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public const POR_PAGINA = 10;

    public const FILTRO_TODAS = 'todas';

    #[Url(as: 'buscar', except: '')]
    public string $search = '';

    #[Url(as: 'categoria', except: self::FILTRO_TODAS)]
    public string $filtroCategoria = self::FILTRO_TODAS;

    public ?string $mensajeExito = null;

    public ?string $mensajeError = null;

    public function render(): View
    {
        return view('servicios.index');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Aplica el filtro de una tarjeta de categoría o del desplegable de la barra
     * de filtros. Una categoría que no existe devuelve el catálogo completo.
     */
    public function filtrarPorCategoria(string $categoria): void
    {
        $this->filtroCategoria = $this->esCategoriaValida($categoria)
            ? $categoria
            : self::FILTRO_TODAS;

        $this->resetPage();
    }

    /**
     * Descarta un servicio del catálogo. Un servicio con cargos en folios se
     * conserva: los cargos históricos deben seguir señalando a un servicio real.
     */
    public function eliminar(int $id): void
    {
        $servicio = Servicio::findOrFail($id);

        $this->reset('mensajeExito', 'mensajeError');

        if ($servicio->reservasServicio()->exists()) {
            $this->mensajeError = 'No se puede eliminar: el servicio ya tiene cargos registrados en folios.';

            return;
        }

        $servicio->delete();

        $this->mensajeExito = 'Servicio eliminado correctamente.';
    }

    /**
     * Cierra el diálogo del catálogo y muestra el mensaje tras guardar.
     */
    #[On('servicio-guardada')]
    public function servicioGuardada(string $mensaje): void
    {
        $this->dispatch('modal-close', name: 'servicio-form');

        $this->reset('mensajeError');
        $this->mensajeExito = $mensaje;
    }

    /**
     * Cierra el diálogo de cargos y muestra el mensaje tras registrar el
     * consumo, que ya forma parte del total de check-out.
     */
    #[On('cargo-registrado')]
    public function cargoRegistrado(string $mensaje): void
    {
        $this->dispatch('modal-close', name: 'servicio-cargo');

        $this->reset('mensajeError');
        $this->mensajeExito = $mensaje;
    }

    /**
     * Catálogo paginado. La búsqueda cubre el nombre y la descripción para que
     * recepción encuentre un servicio por como lo escribe en el mostrador.
     */
    #[Computed]
    public function servicios(): LengthAwarePaginator
    {
        return Servicio::withCount('reservasServicio')
            ->when($this->search !== '', function ($query): void {
                $termino = '%'.trim($this->search).'%';

                $query->where(function ($sub) use ($termino): void {
                    $sub->where('nombre', 'like', $termino)
                        ->orWhere('descripcion', 'like', $termino);
                });
            })
            ->when($this->filtroCategoria !== self::FILTRO_TODAS, function ($query): void {
                $query->where('categoria', $this->filtroCategoria);
            })
            ->orderBy('nombre')
            ->paginate(self::POR_PAGINA);
    }

    /**
     * Servicios que hay en catálogo, con o sin filtro aplicado.
     */
    #[Computed]
    public function totalServicios(): int
    {
        return Servicio::count();
    }

    /**
     * Cuántos cargos por servicios se han registrado en toda la operación.
     */
    #[Computed]
    public function totalCargos(): int
    {
        return ReservaServicio::count();
    }

    /**
     * Importe acumulado de los consumos extras que se han cargado a los folios
     * de los huéspedes.
     */
    #[Computed]
    public function ingresosPorServicios(): float
    {
        return round((float) ReservaServicio::sum('subtotal'), 2);
    }

    /**
     * Número de categorías que tienen al menos un servicio en el catálogo.
     */
    #[Computed]
    public function totalCategorias(): int
    {
        return Servicio::whereNotNull('categoria')->distinct()->count('categoria');
    }

    /**
     * Últimos cargos registrados, con el folio, el huésped y el empleado
     * responsable ya cargados.
     *
     * @return Collection<int, ReservaServicio>
     */
    #[Computed]
    public function cargosRecientes(): Collection
    {
        return ReservaServicio::with([
            'servicio',
            'empleado',
            'reserva.cliente',
            'reserva.habitacionesAsignadas.habitacion',
        ])
            ->latest()
            ->limit(8)
            ->get();
    }

    /**
     * Conteo por categoría para las tarjetas de filtro. Solo aparecen las
     * categorías con servicios, de modo que no se ofrecen filtros vacíos.
     *
     * @return array<string, int>
     */
    #[Computed]
    public function conteoPorCategoria(): array
    {
        $conteos = Servicio::query()
            ->select('categoria')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('categoria')
            ->groupBy('categoria')
            ->pluck('total', 'categoria');

        $conteo = [];

        foreach (Servicio::CATEGORIAS as $categoria) {
            if ($conteos->has($categoria)) {
                $conteo[$categoria] = (int) $conteos->get($categoria);
            }
        }

        return $conteo;
    }

    /**
     * Hay filtros activos cuando la búsqueda no está vacía o la categoría no es
     * "todas". Determina el mensaje del estado vacío del catálogo.
     */
    #[Computed]
    public function hayFiltrosActivos(): bool
    {
        return $this->search !== '' || $this->filtroCategoria !== self::FILTRO_TODAS;
    }

    /**
     * Etiquetas de las habitaciones de una reservación, para identificar el folio
     * en la lista de cargos.
     *
     * @return list<string>
     */
    public function habitacionesDe(ReservaServicio $cargo): array
    {
        return $cargo->reserva?->habitacionesAsignadas
            ->pluck('habitacion.numero_habitacion')
            ->filter()
            ->values()
            ->all() ?? [];
    }

    private function esCategoriaValida(string $categoria): bool
    {
        return $categoria === self::FILTRO_TODAS
            || in_array($categoria, Servicio::CATEGORIAS, true);
    }
}
