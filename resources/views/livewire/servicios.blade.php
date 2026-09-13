<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Servicios</flux:heading>
            <flux:subheading>
                Gestiona el catálogo de servicios adicionales del hotel.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nuevo servicio
        </flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nombre</flux:table.column>
                <flux:table.column>Precio</flux:table.column>
                <flux:table.column align="center">Veces contratado</flux:table.column>
                <flux:table.column align="end">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($servicios as $servicio)
                    <flux:table.row :key="$servicio->id">
                        <flux:table.cell variant="strong">{{ $servicio->nombre }}</flux:table.cell>
                        <flux:table.cell>${{ number_format($servicio->precio, 2) }}</flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge color="zinc" size="sm">{{ $servicio->reservas_servicio_count }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $servicio->id }})">
                                    <flux:icon.pencil-square class="size-4" />
                                    Editar
                                </flux:button>
                                <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $servicio->id }})" wire:confirm="¿Eliminar este servicio?">
                                    <flux:icon.trash class="size-4" />
                                    Eliminar
                                </flux:button>
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

    <flux:modal name="servicio-form" wire:model="mostrarModal" class="w-full max-w-md">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg">{{ $servicioId ? 'Editar servicio' : 'Nuevo servicio' }}</flux:heading>
                <flux:subheading>Completa los datos del servicio.</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ejemplo: Desayuno buffet" required />
                    <flux:error name="nombre" />
                </flux:field>

                <flux:field>
                    <flux:label>Precio</flux:label>
                    <flux:input type="number" step="0.01" min="0" wire:model="precio" required />
                    <flux:error name="precio" />
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
