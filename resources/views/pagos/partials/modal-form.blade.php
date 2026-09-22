{{-- ======================================================
     MODAL: CREAR / EDITAR PAGO
     ====================================================== --}}

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