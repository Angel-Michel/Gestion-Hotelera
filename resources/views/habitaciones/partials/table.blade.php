{{-- ======================================================
     TABLA DE HABITACIONES
     ====================================================== --}}

<flux:card>
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Número</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Tipo</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Precio base</flux:table.column>
            <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Piso</flux:table.column>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($habitaciones as $habitacion)
                <flux:table.row :key="$habitacion->id">
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">Hab. {{ $habitacion->numero_habitacion }}</flux:table.cell>
                    <flux:table.cell>{{ $habitacion->tipo?->nombre ?? '—' }}</flux:table.cell>
                    <flux:table.cell>${{ number_format($habitacion->tipo?->precio_base ?? 0, 2) }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $habitacion->piso }}</flux:table.cell>
                    <flux:table.cell>
                        <x-estado-badge :estado="$habitacion->estado" />
                    </flux:table.cell>
                    <flux:table.cell align="end">
                        <div class="flex items-center justify-end gap-2">
                            <flux:button type="button" size="sm" variant="outline" color="blue" icon="pencil-square" tooltip="Editar habitación" aria-label="Editar habitación" wire:click="editar({{ $habitacion->id }})" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                            <flux:button type="button" size="sm" variant="outline" color="red" icon="trash" tooltip="Eliminar habitación" aria-label="Eliminar habitación" wire:click="eliminar({{ $habitacion->id }})" wire:confirm="¿Eliminar esta habitación?" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" align="center">
                        <p class="py-8">No hay habitaciones registradas.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>