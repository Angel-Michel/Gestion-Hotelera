{{-- ======================================================
     TABLA DE TEMPORADAS
     ====================================================== --}}

<flux:card>
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Nombre</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Fecha inicio</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Fecha fin</flux:table.column>
            <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Multiplicador</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($temporadas as $temporada)
                <flux:table.row :key="$temporada->id">
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $temporada->nombre }}</flux:table.cell>
                    <flux:table.cell>{{ $temporada->fecha_inicio->format('d/m/Y') }}</flux:table.cell>
                    <flux:table.cell>{{ $temporada->fecha_fin->format('d/m/Y') }}</flux:table.cell>
                    <flux:table.cell align="center">
                        <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold">x{{ number_format($temporada->multiplicador_precio, 2) }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell align="end">
                        <div class="flex items-center justify-end gap-2">
                            <flux:button type="button" size="sm" variant="outline" color="blue" icon="pencil-square" tooltip="Editar temporada" aria-label="Editar temporada" wire:click="editar({{ $temporada->id }})" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                            <flux:button type="button" size="sm" variant="outline" color="red" icon="trash" tooltip="Eliminar temporada" aria-label="Eliminar temporada" wire:click="eliminar({{ $temporada->id }})" wire:confirm="¿Eliminar esta temporada?" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" align="center">
                        <p class="py-8">No hay temporadas registradas.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>