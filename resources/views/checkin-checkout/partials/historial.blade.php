{{-- ======================================================
     HISTORIAL RECIENTE
     ====================================================== --}}

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