<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Habitaciones</flux:heading>
            <flux:subheading>
                Gestiona el inventario de habitaciones del hotel.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nueva habitación
        </flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Número</flux:table.column>
                <flux:table.column>Tipo</flux:table.column>
                <flux:table.column>Precio base</flux:table.column>
                <flux:table.column align="center">Piso</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
                <flux:table.column align="end">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($habitaciones as $habitacion)
                    <flux:table.row :key="$habitacion->id">
                        <flux:table.cell variant="strong">Hab. {{ $habitacion->numero_habitacion }}</flux:table.cell>
                        <flux:table.cell>{{ $habitacion->tipo?->nombre ?? '—' }}</flux:table.cell>
                        <flux:table.cell>${{ number_format($habitacion->tipo?->precio_base ?? 0, 2) }}</flux:table.cell>
                        <flux:table.cell align="center">{{ $habitacion->piso }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="amber" size="sm">{{ $habitacion->estado }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $habitacion->id }})">
                                    <flux:icon.pencil-square class="size-4" />
                                    Editar
                                </flux:button>
                                <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $habitacion->id }})" wire:confirm="¿Eliminar esta habitación?">
                                    <flux:icon.trash class="size-4" />
                                    Eliminar
                                </flux:button>
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

    <flux:modal name="habitacion-form" wire:model="mostrarModal" class="w-full max-w-lg">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg">{{ $habitacionId ? 'Editar habitación' : 'Nueva habitación' }}</flux:heading>
                <flux:subheading>Completa los datos de la habitación.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Número de habitación</flux:label>
                    <flux:input wire:model="numero_habitacion" placeholder="Ejemplo: 101" required />
                    <flux:error name="numero_habitacion" />
                </flux:field>

                <flux:field>
                    <flux:label>Piso</flux:label>
                    <flux:input type="number" min="1" wire:model="piso" required />
                    <flux:error name="piso" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Tipo de habitación</flux:label>
                    <select wire:model="tipo_habitacion_id" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Selecciona un tipo…</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }} — ${{ number_format($tipo->precio_base, 2) }}</option>
                        @endforeach
                    </select>
                    <flux:error name="tipo_habitacion_id" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Estado</flux:label>
                    <select wire:model="estado" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        @foreach ($estados as $estado)
                            <option value="{{ $estado }}">{{ $estado }}</option>
                        @endforeach
                    </select>
                    <flux:error name="estado" />
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
