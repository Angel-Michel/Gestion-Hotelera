<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Clientes</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium">
                Gestiona la información de los huéspedes del hotel.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nuevo cliente
        </flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Nombre</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Email</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Teléfono</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Identificación</flux:table.column>
                <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Reservas</flux:table.column>
                <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($clientes as $cliente)
                    <flux:table.row :key="$cliente->id">
                        <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $cliente->nombreCompleto() }}</flux:table.cell>
                        <flux:table.cell>{{ $cliente->email ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $cliente->telefono ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $cliente->tipo_identificacion ? $cliente->tipo_identificacion.' · '.$cliente->numero_identificacion : '—' }}</flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge color="zinc" size="sm" class="!bg-zinc-100 !text-zinc-800 !font-semibold">{{ $cliente->reservas_count }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $cliente->id }})">
                                    <flux:icon.pencil-square class="size-4" />
                                    Editar
                                </flux:button>
                                <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $cliente->id }})" wire:confirm="¿Eliminar este cliente?">
                                    <flux:icon.trash class="size-4" />
                                    Eliminar
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" align="center">
                            <p class="py-8">No hay clientes registrados.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="cliente-form" wire:model="mostrarModal" class="w-full max-w-lg">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">{{ $clienteId ? 'Editar cliente' : 'Nuevo cliente' }}</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium">Completa los datos del cliente.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="nombre" required />
                    <flux:error name="nombre" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input wire:model="apellido" required />
                    <flux:error name="apellido" />
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input type="email" wire:model="email" />
                    <flux:error name="email" />
                </flux:field>

                <flux:field>
                    <flux:label>Teléfono</flux:label>
                    <flux:input wire:model="telefono" />
                    <flux:error name="telefono" />
                </flux:field>

                <flux:field>
                    <flux:label>Tipo de identificación</flux:label>
                    <select wire:model="tipo_identificacion" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Sin especificar…</option>
                        <option value="INE">INE</option>
                        <option value="Pasaporte">Pasaporte</option>
                    </select>
                    <flux:error name="tipo_identificacion" />
                </flux:field>

                <flux:field>
                    <flux:label>Número de identificación</flux:label>
                    <flux:input wire:model="numero_identificacion" />
                    <flux:error name="numero_identificacion" />
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
