<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Pagos</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium">
                Registra y controla los pagos de las reservaciones. Total cobrado: ${{ number_format($total, 2) }}
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nuevo pago
        </flux:button>
    </div>

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

    <flux:modal name="pago-form" wire:model="mostrarModal" class="w-full max-w-lg">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">{{ $pagoId ? 'Editar pago' : 'Nuevo pago' }}</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium">Completa los datos del pago.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Reservación</flux:label>
                    <select wire:model="reserva_id" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Selecciona…</option>
                        @foreach ($reservas as $reserva)
                            <option value="{{ $reserva->id }}">#{{ $reserva->id }} · {{ $reserva->cliente?->nombreCompleto() }} (${{ number_format($reserva->monto_total, 2) }})</option>
                        @endforeach
                    </select>
                    <flux:error name="reserva_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Monto</flux:label>
                    <flux:input type="number" step="0.01" min="0.01" wire:model="monto" required />
                    <flux:error name="monto" />
                </flux:field>

                <flux:field>
                    <flux:label>Método de pago</flux:label>
                    <select wire:model="metodo_pago" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        @foreach ($metodos as $metodo)
                            <option value="{{ $metodo }}">{{ $metodo }}</option>
                        @endforeach
                    </select>
                    <flux:error name="metodo_pago" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Fecha de pago</flux:label>
                    <flux:input type="datetime-local" wire:model="fecha_pago" />
                    <flux:error name="fecha_pago" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Notas</flux:label>
                    <textarea wire:model="notas" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"></textarea>
                    <flux:error name="notas" />
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
