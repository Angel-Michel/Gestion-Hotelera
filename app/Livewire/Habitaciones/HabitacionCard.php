<?php

namespace App\Livewire\Habitaciones;

use App\Models\Habitacion;
use Livewire\Component;

/**
 * Tarjeta de una habitación dentro del inventario. Recibe únicamente la
 * habitación y la variante de maquetación, y delega las acciones al contenedor
 * para que el estado global siga teniendo un único dueño.
 */
class HabitacionCard extends Component
{
    public Habitacion $habitacion;

    public string $variante = Index::VISTA_CUADRICULA;

    public function mount(): void
    {
        $this->habitacion->loadMissing('tipoHabitacion');
    }

    public function etiquetaEstado(string $estado): string
    {
        return match ($estado) {
            'Limpieza' => 'Requiere Limpieza',
            'Mantenimiento' => 'Fuera de Servicio',
            default => $estado,
        };
    }

    public function etiquetaLimpieza(string $estado): string
    {
        return match ($estado) {
            'Disponible' => 'Limpia',
            'Ocupada' => 'En uso',
            'Limpieza' => 'Requiere Limpieza',
            default => 'Fuera de servicio',
        };
    }

    public function clasesPuntoEstado(string $estado): string
    {
        return match ($estado) {
            'Disponible' => 'bg-emerald-500',
            'Ocupada' => 'bg-red-500',
            'Limpieza' => 'bg-amber-500',
            'Mantenimiento' => 'bg-zinc-500',
            default => 'bg-slate-400',
        };
    }

    public function clasesTextoEstado(string $estado): string
    {
        return match ($estado) {
            'Disponible' => 'text-emerald-700 dark:text-emerald-300',
            'Ocupada' => 'text-red-700 dark:text-red-300',
            'Limpieza' => 'text-amber-700 dark:text-amber-300',
            default => 'text-slate-600 dark:text-slate-300',
        };
    }
}
