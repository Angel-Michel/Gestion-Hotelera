{{-- ======================================================
     MODAL: ELIMINAR ROL
     ====================================================== --}}

<flux:modal name="eliminar-rol" wire:model="mostrarModalEliminar" class="w-full max-w-md">
    <div class="mb-6">
        <flux:heading size="lg" class="!text-red-600 dark:!text-red-400">
            Eliminar rol
        </flux:heading>
        <flux:subheading class="!text-slate-600 !font-medium">
            Esta acción no se puede deshacer.
        </flux:subheading>
    </div>

    <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
        ¿Estás seguro de que deseas eliminar el rol
        <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $nombreRolAEliminar }}</span>?
        Se retirará el acceso a los usuarios asignados a este rol.
    </p>

    <div class="mt-6 flex items-center justify-end gap-3">
        <flux:button type="button" variant="ghost" wire:click="cerrarModalEliminar">
            Cancelar
        </flux:button>

        <flux:button type="button" variant="danger" wire:click="eliminarRol">
            <flux:icon.trash class="size-4" />
            Sí, eliminar
        </flux:button>
    </div>
</flux:modal>