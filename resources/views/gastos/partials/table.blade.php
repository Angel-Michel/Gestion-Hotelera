{{-- ======================================================
     TABLA DE GASTOS
     ====================================================== --}}

<flux:card>
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Concepto</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Categoría</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Fecha</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($gastos as $gasto)
                <flux:table.row :key="$gasto->id">
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $gasto->concepto }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold">{{ $gasto->categoria }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>${{ number_format($gasto->monto, 2) }}</flux:table.cell>
                    <flux:table.cell>{{ $gasto->fecha_gasto->format('d/m/Y') }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <div class="flex items-center justify-end gap-2">
                            <flux:button type="button" size="sm" variant="outline" color="blue" icon="pencil-square" tooltip="Editar gasto" aria-label="Editar gasto" wire:click="editar({{ $gasto->id }})" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                            <flux:button type="button" size="sm" variant="outline" color="red" icon="trash" tooltip="Eliminar gasto" aria-label="Eliminar gasto" wire:click="eliminar({{ $gasto->id }})" wire:confirm="¿Eliminar este gasto?" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" align="center">
                        <p class="py-8">No hay gastos registrados.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>