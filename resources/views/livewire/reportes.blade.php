<div>
    <div class="mb-6">
        <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Reportes</flux:heading>
        <flux:subheading class="!text-slate-600 !font-medium">
            Indicadores y resúmenes de la operación del hotel.
        </flux:subheading>
    </div>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <flux:card>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">Ocupación</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">{{ $ocupacion }}%</p>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">{{ $habitacionesOcupadas }} de {{ $totalHabitaciones }} habitaciones</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">Ingresos totales</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">${{ number_format($ingresosTotales, 2) }}</p>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">Suma de pagos registrados</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">Gastos totales</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">${{ number_format($gastosTotales, 2) }}</p>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">Suma de gastos registrados</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">Balance</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">${{ number_format($balance, 2) }}</p>
            <flux:text size="sm" class="!text-slate-600 !font-semibold">Ingresos menos gastos</flux:text>
        </flux:card>
    </div>

    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <flux:card>
            <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Reservas por estado</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
                    <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Total</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($reservasPorEstado as $estado => $total)
                        <flux:table.row>
                            <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $estado }}</flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold">{{ $total }}</flux:badge>
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
            <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Pagos por método</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Método</flux:table.column>
                    <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Pagos</flux:table.column>
                    <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($pagosPorMetodo as $pago)
                        <flux:table.row>
                            <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $pago->metodo_pago }}</flux:table.cell>
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
            <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Gastos por categoría</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Categoría</flux:table.column>
                    <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($gastosPorCategoria as $gasto)
                        <flux:table.row>
                            <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $gasto->categoria }}</flux:table.cell>
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
        <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Últimas reservaciones</flux:heading>

        <flux:table>
            <flux:table.columns>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Cliente</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-in</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-out</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Habitaciones</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
                <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto total</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($ultimasReservas as $reserva)
                    <flux:table.row :key="$reserva->id">
                        <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $reserva->cliente?->nombreCompleto() ?? '—' }}</flux:table.cell>
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
