{{-- ======================================================
     ENCABEZADO
     ====================================================== --}}

<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Habitaciones</flux:heading>
        <flux:subheading class="!text-slate-600 !font-medium">
            Gestiona el inventario de habitaciones del hotel.
        </flux:subheading>
    </div>

    <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
        <flux:icon.plus class="size-4" />
        Nueva habitación
    </flux:button>
</div>