{{-- ======================================================
     TABLA DE RESERVACIONES
     ====================================================== --}}

<flux:card>
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Cliente</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-in</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-out</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Habitaciones</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto total</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($reservas as $reserva)
                <flux:table.row :key="$reserva->id">
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $reserva->cliente?->nombreCompleto() ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $reserva->check_in->format('d/m/Y') }}</flux:table.cell>
                    <flux:table.cell>{{ $reserva->check_out->format('d/m/Y') }}</flux:table.cell>
                    <flux:table.cell>
                        {{ $reserva->habitaciones->pluck('numero_habitacion')->join(', ') ?: '—' }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <x-estado-badge :estado="$reserva->estado" />
                    </flux:table.cell>
                    <flux:table.cell>${{ number_format($reserva->monto_total, 2) }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <div class="flex items-center justify-end gap-2">
                            <flux:button type="button" size="sm" variant="outline" color="blue" icon="pencil-square" tooltip="Editar reservación" aria-label="Editar reservación" wire:click="editar({{ $reserva->id }})" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                            <flux:button type="button" size="sm" variant="outline" color="red" icon="trash" tooltip="Eliminar reservación" aria-label="Eliminar reservación" wire:click="eliminar({{ $reserva->id }})" wire:confirm="¿Eliminar esta reservación?" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="7" align="center">
                        <p class="py-8">No hay reservaciones registradas.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>