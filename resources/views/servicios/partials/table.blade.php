{{-- ======================================================
     TABLA DE SERVICIOS
     ====================================================== --}}

<flux:card>
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Nombre</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Precio</flux:table.column>
            <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Veces contratado</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($servicios as $servicio)
                <flux:table.row :key="$servicio->id">
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $servicio->nombre }}</flux:table.cell>
                    <flux:table.cell>${{ number_format($servicio->precio, 2) }}</flux:table.cell>
                    <flux:table.cell align="center">
                        <flux:badge color="zinc" size="sm" class="!bg-zinc-100 !text-zinc-800 !font-semibold">{{ $servicio->reservas_servicio_count }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell align="end">
                        <div class="flex items-center justify-end gap-2">
                            <flux:button type="button" size="sm" variant="outline" color="blue" icon="pencil-square" tooltip="Editar servicio" aria-label="Editar servicio" wire:click="editar({{ $servicio->id }})" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                            <flux:button type="button" size="sm" variant="outline" color="red" icon="trash" tooltip="Eliminar servicio" aria-label="Eliminar servicio" wire:click="eliminar({{ $servicio->id }})" wire:confirm="¿Eliminar este servicio?" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" align="center">
                        <p class="py-8">No hay servicios registrados.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>