<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\Temporada;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app')]
class Configuracion extends Component
{
    public function render(): View
    {
        $hoy = now()->toDateString();

        $temporadaVigente = Temporada::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->orderBy('fecha_inicio')
            ->first();

        return view('configuracion.index', [
            'nombreApp' => config('app.name'),
            'entorno' => config('app.env'),
            'temporadaVigente' => $temporadaVigente,
            'totalUsuarios' => User::count(),
            'totalRoles' => Role::count(),
            'totalPermisos' => Permission::count(),
            'totalHabitaciones' => Habitacion::count(),
            'totalClientes' => Cliente::count(),
            'totalReservas' => Reserva::count(),
        ]);
    }
}
