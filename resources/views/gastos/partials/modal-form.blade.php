{{-- ======================================================
     MODAL: CREAR / EDITAR GASTO
     ====================================================== --}}

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