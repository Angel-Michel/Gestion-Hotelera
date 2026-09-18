<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\ReservaHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class RoomSearchController extends Controller
{
    public function index(Request $request): View
    {
        $validado = $request->validate([
            'check_in' => ['nullable', 'date', 'after_or_equal:today'],
            'check_out' => ['nullable', 'date', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1'],
        ]);

        $checkIn = $validado['check_in'] ?? now()->toDateString();
        $checkOut = $validado['check_out'] ?? Carbon::parse($checkIn)->addDays(3)->toDateString();
        $guests = $validado['guests'] ?? null;

        $reservadas = ReservaHabitacion::whereHas('reserva', function ($query) use ($checkIn, $checkOut) {
            $query->where('estado', '!=', 'Cancelada')
                ->where('check_in', '<', $checkOut)
                ->where('check_out', '>', $checkIn);
        })->pluck('habitacion_id');

        $habitaciones = Habitacion::with('tipo')
            ->where('estado', 'Disponible')
            ->when($guests, fn ($query) => $query->whereHas(
                'tipo',
                fn ($tipo) => $tipo->where('capacidad', '>=', $guests)
            ))
            ->when($reservadas->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $reservadas))
            ->orderBy('piso')
            ->orderBy('numero_habitacion')
            ->get();

        $noches = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));

        return view('rooms.search-results', compact('checkIn', 'checkOut', 'guests', 'habitaciones', 'noches'));
    }
}
