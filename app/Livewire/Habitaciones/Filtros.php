<?php

namespace App\Livewire\Habitaciones;

use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Barra de cabecera y controles del inventario: búsqueda en vivo, tarjetas KPI
 * de estado con contador, selector de estados, total de habitaciones y el
 * conmutador entre la vista cuadrícula y la vista lista.
 *
 * No escribe estado propio salvo la búsqueda, que está enlazada en dos
 * direcciones con el contenedor para que los filtros afecten al inventario.
 */
class Filtros extends Component
{
    #[Modelable]
    public string $search = '';

    public string $filtroEstado = Index::FILTRO_TODOS;

    public string $vista = Index::VISTA_CUADRICULA;

    public int $totalHabitaciones = 0;

    /**
     * Tarjetas KPI de estado ya calculadas por el contenedor.
     *
     * @var array<int, array{clave: string, etiqueta: string, conteo: int, icono: string, borde: string, iconoFondo: string, iconoTexto: string, activa: bool}>
     */
    public array $tarjetas = [];

    /**
     * Opciones del desplegable de estados.
     *
     * @var array<int, array{clave: string, etiqueta: string, estados: list<string>, icono: string, borde: string, iconoFondo: string, iconoTexto: string}>
     */
    public array $opcionesEstado = [];
}
