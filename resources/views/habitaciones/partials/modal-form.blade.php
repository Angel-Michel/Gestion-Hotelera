{{-- ======================================================
     MODAL: CREAR / EDITAR HABITACIÓN
     ====================================================== --}}

<flux:modal name="habitacion-form" wire:model="mostrarModal" class="w-full max-w-lg">
    <form wire:submit="guardar">
        <div class="mb-6">
            <flux:heading size="lg" class="!text-slate-800 !font-semibold">{{ $habitacionId ? 'Editar habitación' : 'Nueva habitación' }}</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium">Completa los datos de la habitación.</flux:subheading>
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
                <select wire:model="tipo_habitacion_id" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                    <option value="">Selecciona un tipo…</option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }} — ${{ number_format($tipo->precio_base, 2) }}</option>
                    @endforeach
                </select>
                <flux:error name="tipo_habitacion_id" />
            </flux:field>

            <flux:field class="sm:col-span-2">
                <flux:label>Estado</flux:label>
                <select wire:model="estado" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
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