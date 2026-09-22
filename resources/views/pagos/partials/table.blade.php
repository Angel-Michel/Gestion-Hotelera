{{-- ======================================================
     TABLA DE PAGOS
     ====================================================== --}}

<flux:card>
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Reservación</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Método</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Fecha de pago</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Notas</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($pagos as $pago)
                <flux:table.row :key="$pago->id">
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">
                        #{{ $pago->reserva_id }} · {{ $pago->reserva?->cliente?->nombreCompleto() ?? '—' }}
                    </flux:table.cell>
                    <flux:table.cell>${{ number_format($pago->monto, 2) }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold">{{ $pago->metodo_pago }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $pago->fecha_pago?->format('d/m/Y H:i') ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $pago->notas ?? '—' }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <div class="flex items-center justify-end gap-2">
                            <flux:button type="button" size="sm" variant="outline" color="blue" icon="pencil-square" tooltip="Editar pago" aria-label="Editar pago" wire:click="editar({{ $pago->id }})" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                            <flux:button type="button" size="sm" variant="outline" color="red" icon="trash" tooltip="Eliminar pago" aria-label="Eliminar pago" wire:click="eliminar({{ $pago->id }})" wire:confirm="¿Eliminar este pago?" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" align="center">
                        <p class="py-8">No hay pagos registrados.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>