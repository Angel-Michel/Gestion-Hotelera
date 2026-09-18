<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Gastos</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium">
                Controla los gastos operativos del hotel. Total: ${{ number_format($total, 2) }}
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nuevo gasto
        </flux:button>
    </div>

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
                                <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $gasto->id }})">
                                    <flux:icon.pencil-square class="size-4" />
                                    Editar
                                </flux:button>
                                <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $gasto->id }})" wire:confirm="¿Eliminar este gasto?">
                                    <flux:icon.trash class="size-4" />
                                    Eliminar
                                </flux:button>
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

    <flux:modal name="gasto-form" wire:model="mostrarModal" class="w-full max-w-lg">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">{{ $gastoId ? 'Editar gasto' : 'Nuevo gasto' }}</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium">Completa los datos del gasto.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Concepto</flux:label>
                    <flux:input wire:model="concepto" placeholder="Ejemplo: Compra de blancos" required />
                    <flux:error name="concepto" />
                </flux:field>

                <flux:field>
                    <flux:label>Monto</flux:label>
                    <flux:input type="number" step="0.01" min="0.01" wire:model="monto" required />
                    <flux:error name="monto" />
                </flux:field>

                <flux:field>
                    <flux:label>Fecha del gasto</flux:label>
                    <flux:input type="date" wire:model="fecha_gasto" required />
                    <flux:error name="fecha_gasto" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Categoría</flux:label>
                    <select wire:model="categoria" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Selecciona…</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                    </select>
                    <flux:error name="categoria" />
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
