<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class EstadoCuentaController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cliente = Cliente::where('user_id', auth()->id())
            ->with('reservas')
            ->first();

        if (! $cliente) {
            return redirect()->route('home')->with(
                'info',
                'Crea tu primera reservación para ver su estado de cuenta.'
            );
        }

        $reservas = $cliente->reservas()
            ->with([
                'habitacionesAsignadas.habitacion.tipo',
                'serviciosAsignados.servicio',
                'pagos',
            ])
            ->orderByDesc('check_in')
            ->get()
            ->map(function (Reserva $reserva): array {
                return $this->datosEstadoCuenta($reserva) + ['reserva' => $reserva];
            });

        return view('cliente.mis-reservaciones', [
            'cliente' => $cliente,
            'reservas' => $reservas,
        ]);
    }

    public function descargar(Reserva $reserva): Response
    {
        abort_unless($this->perteneceAlUsuario($reserva), 403);

        $datos = $this->datosEstadoCuenta($reserva) + ['reserva' => $reserva];

        $pdf = Pdf::loadView('pdf.estado-cuenta', $datos)
            ->setPaper('a4');

        return $pdf->download("estado-cuenta-{$reserva->id}.pdf");
    }

    /**
     * @return array<string, mixed>
     */
    private function datosEstadoCuenta(Reserva $reserva): array
    {
        $noches = $reserva->check_in->diffInDays($reserva->check_out);
        $asignaciones = $reserva->habitacionesAsignadas()->with('habitacion.tipo')->get();

        $tarifa = (float) $asignaciones->reduce(
            fn (float $carry, $asignacion) => $carry + ((float) $asignacion->precio_por_noche * $noches),
            0.0
        );

        if ($tarifa <= 0) {
            $tarifa = (float) $reserva->monto_total;
        }

        $gastosExtra = $reserva->serviciosAsignados()->with('servicio')->get();

        $subtotalExtras = round(
            (float) $gastosExtra->sum(fn ($gasto) => (float) $gasto->subtotal),
            2
        );

        $totalConsumos = round($tarifa + $subtotalExtras, 2);
        $pagado = round((float) $reserva->pagos()->sum('monto'), 2);
        $pendiente = round(max(0.0, $totalConsumos - $pagado), 2);

        return compact(
            'noches',
            'asignaciones',
            'tarifa',
            'gastosExtra',
            'subtotalExtras',
            'totalConsumos',
            'pagado',
            'pendiente'
        );
    }

    private function perteneceAlUsuario(Reserva $reserva): bool
    {
        return $reserva->user_id === auth()->id()
            || $reserva->cliente?->user_id === auth()->id();
    }
}
