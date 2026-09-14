<div>
    <div class="mb-6">
        <flux:heading size="xl">Reportes</flux:heading>
        <flux:subheading>
            Indicadores y resúmenes de la operación del hotel.
        </flux:subheading>
    </div>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <flux:card>
            <flux:text size="sm">Ocupación</flux:text>
            <p class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">{{ $ocupacion }}%</p>
            <flux:text size="sm">{{ $habitacionesOcupadas }} de {{ $totalHabitaciones }} habitaciones</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm">Ingresos totales</flux:text>
            <p class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">${{ number_format($ingresosTotales, 2) }}</p>
            <flux:text size="sm">Suma de pagos registrados</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm">Gastos totales</flux:text>
            <p class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">${{ number_format($gastosTotales, 2) }}</p>
            <flux:text size="sm">Suma de gastos registrados</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm">Balance</flux:text>
            <p class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">${{ number_format($balance, 2) }}</p>
            <flux:text size="sm">Ingresos menos gastos</flux:text>
        </flux:card>
    </div>

    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <flux:card>
            <flux:heading size="lg" class="mb-4">Reservas por estado</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Estado</flux:table.column>
                    <flux:table.column align="center">Total</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($reservasPorEstado as $estado => $total)
                        <flux:table.row>
                            <flux:table.cell variant="strong">{{ $estado }}</flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:badge color="blue" size="sm">{{ $total }}</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="2" align="center">
                                <p class="py-6">Sin datos.</p>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Pagos por método</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Método</flux:table.column>
                    <flux:table.column align="center">Pagos</flux:table.column>
                    <flux:table.column align="end">Monto</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($pagosPorMetodo as $pago)
                        <flux:table.row>
                            <flux:table.cell variant="strong">{{ $pago->metodo_pago }}</flux:table.cell>
                            <flux:table.cell align="center">{{ $pago->total }}</flux:table.cell>
                            <flux:table.cell align="end">${{ number_format($pago->monto, 2) }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" align="center">
                                <p class="py-6">Sin datos.</p>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Gastos por categoría</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Categoría</flux:table.column>
                    <flux:table.column align="end">Monto</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($gastosPorCategoria as $gasto)
                        <flux:table.row>
                            <flux:table.cell variant="strong">{{ $gasto->categoria }}</flux:table.cell>
                            <flux:table.cell align="end">${{ number_format($gasto->monto, 2) }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="2" align="center">
                                <p class="py-6">Sin datos.</p>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>

    <flux:card>
        <flux:heading size="lg" class="mb-4">Últimas reservaciones</flux:heading>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Cliente</flux:table.column>
                <flux:table.column>Check-in</flux:table.column>
                <flux:table.column>Check-out</flux:table.column>
                <flux:table.column>Habitaciones</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
                <flux:table.column align="end">Monto total</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($ultimasReservas as $reserva)
                    <flux:table.row :key="$reserva->id">
                        <flux:table.cell variant="strong">{{ $reserva->cliente?->nombreCompleto() ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_in->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_out->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->habitaciones->pluck('numero_habitacion')->join(', ') ?: '—' }}</flux:table.cell>
                        <flux:table.cell>
                            <x-estado-badge :estado="$reserva->estado" />
                        </flux:table.cell>
                        <flux:table.cell align="end">${{ number_format($reserva->monto_total, 2) }}</flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" align="center">
                            <p class="py-8">No hay reservaciones registradas.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
