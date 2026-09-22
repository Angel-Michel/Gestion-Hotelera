{{-- ======================================================
     ENCABEZADO
     ====================================================== --}}

<div class="mb-8 flex animate-fade-in flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-4">
        <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500/15 shadow-sm shadow-amber-500/20">
            <flux:icon.identification class="size-6 text-amber-600 dark:text-amber-400" />
        </span>

        <div>
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl dark:!text-white">Empleados</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium dark:!text-slate-300">
                <span class="inline-flex items-center gap-2">
                    <span class="relative flex size-2" aria-hidden="true">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Gestiona el personal del hotel, sus datos y su acceso al sistema.
                </span>
            </flux:subheading>
        </div>
    </div>

    @if ($this->esSuperAdmin())
        <button
            type="button"
            wire:click="crear"
            class="group inline-flex shrink-0 items-center justify-center gap-2.5 rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-200 ease-out hover:scale-105 hover:bg-amber-600 hover:shadow-xl hover:shadow-amber-500/40 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
        >
            <span class="flex size-6 items-center justify-center rounded-full bg-white/20 transition-transform duration-200 ease-out group-hover:rotate-90">
                <flux:icon.plus class="size-4" />
            </span>
            Crear Nuevo Empleado
        </button>
    @endif
</div>