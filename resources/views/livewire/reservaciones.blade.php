<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Reservaciones</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium">
                Gestiona las reservaciones del hotel y asigna habitaciones.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nueva reservación
        </flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Cliente</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-in</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Check-out</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Habitaciones</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto total</flux:table.column>
                <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($reservas as $reserva)
                    <flux:table.row :key="$reserva->id">
                        <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $reserva->cliente?->nombreCompleto() ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_in->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $reserva->check_out->format('d/m/Y') }}</flux:table.cell>
                        <flux:table.cell>
                            {{ $reserva->habitaciones->pluck('numero_habitacion')->join(', ') ?: '—' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <x-estado-badge :estado="$reserva->estado" />
                        </flux:table.cell>
                        <flux:table.cell>${{ number_format($reserva->monto_total, 2) }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button type="button" size="sm" variant="outline" color="blue" icon="pencil-square" tooltip="Editar reservación" aria-label="Editar reservación" wire:click="editar({{ $reserva->id }})" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                                <flux:button type="button" size="sm" variant="outline" color="red" icon="trash" tooltip="Eliminar reservación" aria-label="Eliminar reservación" wire:click="eliminar({{ $reserva->id }})" wire:confirm="¿Eliminar esta reservación?" class="transition-all duration-200 hover:scale-105 active:scale-95" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7" align="center">
                            <p class="py-8">No hay reservaciones registradas.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="reservacion-form" wire:model="mostrarModal" class="w-full max-w-2xl">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">{{ $reservaId ? 'Editar reservación' : 'Nueva reservación' }}</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium">Completa los datos de la reservación.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Cliente</flux:label>
                    <select wire:model="cliente_id" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Selecciona un cliente…</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombreCompleto() }}</option>
                        @endforeach
                    </select>
                    <flux:error name="cliente_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Check-in</flux:label>
                    <flux:input type="date" wire:model="check_in" required />
                    <flux:error name="check_in" />
                </flux:field>

                <flux:field>
                    <flux:label>Check-out</flux:label>
                    <flux:input type="date" wire:model="check_out" required />
                    <flux:error name="check_out" />
                </flux:field>

                <flux:field>
                    <flux:label>Estado</flux:label>
                    <select wire:model="estado" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        @foreach ($estados as $estado)
                            <option value="{{ $estado }}">{{ $estado }}</option>
                        @endforeach
                    </select>
                    <flux:error name="estado" />
                </flux:field>

                <flux:field>
                    <flux:label>Monto total</flux:label>
                    <flux:input type="number" step="0.01" min="0" wire:model="monto_total" required />
                    <flux:error name="monto_total" />
                </flux:field>

                <div class="sm:col-span-2">
                    <p class="mb-2 text-sm font-semibold text-slate-800">Habitaciones asignadas</p>
                    <div class="grid max-h-48 gap-1.5 overflow-y-auto pr-1 sm:grid-cols-3">
                        @foreach ($habitaciones as $habitacion)
                            <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                <input type="checkbox" value="{{ $habitacion->id }}" wire:model="habitacion_ids" class="size-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                                Hab. {{ $habitacion->numero_habitacion }} ({{ $habitacion->estado }})
                            </label>
                        @endforeach
                    </div>
                </div>
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
