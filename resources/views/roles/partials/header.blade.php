{{-- ======================================================
     ENCABEZADO
     ====================================================== --}}

<div class="mb-6 flex animate-fade-in flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-4">
        <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500/15 shadow-sm shadow-amber-500/20">
            <flux:icon.shield-check class="size-6 text-amber-600 dark:text-amber-400" />
        </span>

        <div>
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl dark:!text-white">Catálogo de Puestos</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium dark:!text-slate-300">
                <span class="inline-flex items-center gap-2">
                    <span class="relative flex size-2" aria-hidden="true">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Crea y renombra los puestos del equipo; los niveles de acceso se ajustan en la Matriz de Permisos.
                </span>
            </flux:subheading>
        </div>
    </div>

    @if ($this->esSuperAdmin())
        <flux:button type="button" variant="primary" wire:click="abrirModalCrear" class="group shrink-0 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:scale-105 hover:shadow-lg active:scale-95">
            <span class="flex size-5 items-center justify-center rounded-full bg-white/20 transition-transform duration-200 ease-in-out group-hover:rotate-90">
                <flux:icon.plus class="size-3.5" />
            </span>
            Crear nuevo rol
        </flux:button>
    @endif
</div>