<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6">
        <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Check-in / Check-out</flux:heading>
        <flux:subheading class="!text-slate-600 !font-medium">
            Registra las entradas y salidas de los huéspedes.
        </flux:subheading>
    </div>

    <flux:card class="mb-6">
        <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Reservas por atender</flux:heading>

        <flux:table>
            <flux:table.columns>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Cliente</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-in</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-out</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Habitaciones</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
                <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($porAtender as $reserva)
                    <flux:table.row :key="$reserva->id">
                        <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $reserva->cliente?->nombreCompleto() ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_in->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_out->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->habitaciones->pluck('numero_habitacion')->join(', ') ?: '—' }}</flux:table.cell>
                        <flux:table.cell>
                            <x-estado-badge :estado="$reserva->estado" />
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                @if ($reserva->estado === 'Pendiente')
                                    <flux:button type="button" size="sm" variant="primary" wire:click="checkIn({{ $reserva->id }})">
                                        Check-in
                                    </flux:button>
                                @endif

                                @if ($reserva->estado === 'Confirmada')
                                    <flux:button type="button" size="sm" variant="primary" wire:click="checkOut({{ $reserva->id }})">
                                        Check-out
                                    </flux:button>
                                @endif

                                <flux:button type="button" size="sm" variant="danger" wire:click="cancelar({{ $reserva->id }})" wire:confirm="¿Cancelar esta reservación?">
                                    Cancelar
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" align="center">
                            <p class="py-8">No hay reservas por atender.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:card>
        <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Historial reciente</flux:heading>

        <flux:table>
            <flux:table.columns>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Cliente</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-in</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-out</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($historial as $reserva)
                    <flux:table.row :key="$reserva->id">
                        <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $reserva->cliente?->nombreCompleto() ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_in->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_out->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>
                            <x-estado-badge :estado="$reserva->estado" />
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" align="center">
                            <p class="py-8">Sin movimientos recientes.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
