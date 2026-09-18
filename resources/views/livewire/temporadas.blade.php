<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Temporadas</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium">
                Define las temporadas y sus multiplicadores de precio.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nueva temporada
        </flux:button>
    </div>

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
                                <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $temporada->id }})">
                                    <flux:icon.pencil-square class="size-4" />
                                    Editar
                                </flux:button>
                                <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $temporada->id }})" wire:confirm="¿Eliminar esta temporada?">
                                    <flux:icon.trash class="size-4" />
                                    Eliminar
                                </flux:button>
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

    <flux:modal name="temporada-form" wire:model="mostrarModal" class="w-full max-w-lg">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">{{ $temporadaId ? 'Editar temporada' : 'Nueva temporada' }}</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium">Completa los datos de la temporada.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ejemplo: Temporada Alta" required />
                    <flux:error name="nombre" />
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de inicio</flux:label>
                    <flux:input type="date" wire:model="fecha_inicio" required />
                    <flux:error name="fecha_inicio" />
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de fin</flux:label>
                    <flux:input type="date" wire:model="fecha_fin" required />
                    <flux:error name="fecha_fin" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Multiplicador de precio</flux:label>
                    <flux:input type="number" step="0.01" min="0.01" wire:model="multiplicador_precio" required />
                    <flux:error name="multiplicador_precio" />
                </flux:field>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="cerrarModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">
                    <flux:icon.check class="size-4" />
                    Guardar
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
