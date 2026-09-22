{{-- ======================================================
     ENCABEZADO
     ====================================================== --}}

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Servicios</flux:heading>
        <flux:subheading class="!text-slate-600 !font-medium">
            Gestiona el catálogo de servicios adicionales del hotel.
        </flux:subheading>
    </div>

    <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
        <flux:icon.plus class="size-4" />
        Nuevo servicio
    </flux:button>
</div>