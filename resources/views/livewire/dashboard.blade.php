<div>
    <div class="mb-6">
        <flux:heading size="xl">Dashboard</flux:heading>
        <flux:subheading>
            Resumen general de la operación del hotel Novastay.
        </flux:subheading>
    </div>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <flux:card>
            <flux:text size="sm">Ocupación actual</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">
                {{ $ocupacion }}%
            </p>
            <flux:text size="sm">{{ $habitacionesOcupadas }} de {{ $totalHabitaciones }} habitaciones ocupadas</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm">Reservas activas</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">
                {{ $reservasActivas }}
            </p>
            <flux:text size="sm">Pendientes y confirmadas</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm">Ingresos del mes</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">
                ${{ number_format($ingresosMes, 2) }}
            </p>
            <flux:text size="sm">{{ now()->translatedFormat('F Y') }}</flux:text>
        </flux:card>

        <flux:card>
            <flux:text size="sm">Tareas de limpieza</flux:text>
            <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">
                {{ $tareasPendientes->count() }}
            </p>
            <flux:text size="sm">Pendientes o en proceso</flux:text>
        </flux:card>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <flux:card>
            <flux:heading size="lg" class="mb-4">Próximas llegadas</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Cliente</flux:table.column>
                    <flux:table.column>Check-in</flux:table.column>
                    <flux:table.column>Estado</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($proximasLlegadas as $reserva)
                        <flux:table.row :key="$reserva->id">
                            <flux:table.cell variant="strong">{{ $reserva->cliente?->nombreCompleto() ?? '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $reserva->check_in->format('d/m/Y') }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="amber" size="sm">{{ $reserva->estado }}</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" align="center">
                                <p class="py-6">Sin llegadas próximas.</p>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Limpieza pendiente</flux:heading>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Habitación</flux:table.column>
                    <flux:table.column>Estado</flux:table.column>
                    <flux:table.column>Responsable</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($tareasPendientes as $tarea)
                        <flux:table.row :key="$tarea->id">
                            <flux:table.cell variant="strong">Hab. {{ $tarea->habitacion?->numero_habitacion ?? '—' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="zinc" size="sm">{{ $tarea->estado }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>{{ $tarea->usuario?->name ?? '—' }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" align="center">
                                <p class="py-6">Sin tareas pendientes.</p>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</div>
