<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\ReservaHabitacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservaController extends Controller
{
    public function iniciar(Request $request): RedirectResponse
    {
        $validado = $request->validate([
            'habitacion_id' => ['required', 'exists:habitaciones,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1'],
            'accion' => ['nullable', 'in:login,registro'],
        ]);

        session()->put('reserva.pendiente', $validado);

        if (! auth()->check()) {
            $accion = $validado['accion'] ?? 'login';

            return redirect($accion === 'registro'
                ? route('register')
                : route('login'))
                ->with('aviso', 'Inicia sesión o crea una cuenta para completar tu reservación.');
        }

        return redirect()->route('reserva.confirmar');
    }

    public function confirmar(): View
    {
        $datos = session('reserva.pendiente');

        abort_unless($datos, 404);

        $habitacion = Habitacion::with('tipo')->findOrFail($datos['habitacion_id']);
        $checkIn = Carbon::parse($datos['check_in']);
        $checkOut = Carbon::parse($datos['check_out']);
        $noches = $checkIn->diffInDays($checkOut);
        $precioPorNoche = (float) $habitacion->tipo->precio_base;
        $total = round($precioPorNoche * $noches, 2);

        return view('reserva.confirmar', compact(
            'datos',
            'habitacion',
            'checkIn',
            'checkOut',
            'noches',
            'precioPorNoche',
            'total'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1'],
            'habitacion_id' => ['required', 'exists:habitaciones,id'],
        ]);

        $habitacion = Habitacion::with('tipo')->findOrFail($datos['habitacion_id']);
        $checkIn = Carbon::parse($datos['check_in']);
        $checkOut = Carbon::parse($datos['check_out']);
        $noches = $checkIn->diffInDays($checkOut);
        $precioPorNoche = (float) $habitacion->tipo->precio_base;
        $montoTotal = round($precioPorNoche * $noches, 2);

        abort_unless($this->habitacionDisponible($habitacion->id, $checkIn, $checkOut), 409);

        $usuario = auth()->user();
        $cliente = Cliente::firstOrCreate(
            ['email' => $usuario->email],
            [
                'user_id' => $usuario->id,
                'nombre' => $usuario->name,
                'apellido' => '',
            ]
        );

        if ($cliente->user_id !== $usuario->id) {
            $cliente->user_id = $usuario->id;
            $cliente->save();
        }

        $reserva = DB::transaction(function () use ($cliente, $usuario, $datos, $montoTotal, $habitacion, $precioPorNoche) {
            $reserva = Reserva::create([
                'cliente_id' => $cliente->id,
                'user_id' => $usuario->id,
                'check_in' => $datos['check_in'],
                'check_out' => $datos['check_out'],
                'estado' => 'Confirmada',
                'monto_total' => $montoTotal,
            ]);

            ReservaHabitacion::create([
                'reserva_id' => $reserva->id,
                'habitacion_id' => $habitacion->id,
                'precio_por_noche' => $precioPorNoche,
            ]);

            return $reserva;
        });

        session()->forget('reserva.pendiente');

        return redirect()->route('home')->with(
            'success',
            'Tu reservación ha sido confirmada. ¡Nos vemos pronto en NovaStay!'
        );
    }

    private function habitacionDisponible(int $habitacionId, Carbon $checkIn, Carbon $checkOut): bool
    {
        $reservadas = ReservaHabitacion::where('habitacion_id', $habitacionId)
            ->whereHas('reserva', function ($query) use ($checkIn, $checkOut) {
                $query->where('estado', '!=', 'Cancelada')
                    ->where('check_in', '<', $checkOut->toDateString())
                    ->where('check_out', '>', $checkIn->toDateString());
            })
            ->exists();

        $habitacion = Habitacion::find($habitacionId);

        return ! $reservadas && $habitacion?->estado === 'Disponible';
    }
}
