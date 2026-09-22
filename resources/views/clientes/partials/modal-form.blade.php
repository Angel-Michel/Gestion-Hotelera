{{-- ======================================================
     MODAL: CREAR / EDITAR CLIENTE
     ====================================================== --}}

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