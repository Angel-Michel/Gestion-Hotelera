{{-- ======================================================
     TABLA DEL CATÁLOGO DE SERVICIOS
     ====================================================== --}}

<div class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-950/5 ring-1 ring-slate-900/5 dark:bg-slate-800 dark:ring-white/10">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100 text-left text-sm dark:divide-zinc-700">
            <thead class="bg-slate-50/80 dark:bg-zinc-900/60">
                <tr>
                    <th scope="col" class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Servicio</th>
                    <th scope="col" class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Categoría</th>
                    <th scope="col" class="px-6 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-400">Precio (MXN)</th>
                    <th scope="col" class="px-6 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-400">Cargos</th>
                    <th scope="col" class="px-6 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-400">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white dark:divide-zinc-700 dark:bg-slate-800">
                @forelse ($this->servicios as $servicio)
                    <tr wire:key="servicio-{{ $servicio->id }}" class="transition-colors duration-200 hover:bg-slate-50/70 dark:hover:bg-zinc-700/40">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $servicio->nombre }}</p>
                            @if ($servicio->descripcion)
                                <p class="mt-0.5 max-w-md text-xs text-slate-500 dark:text-slate-400">{{ $servicio->descripcion }}</p>
                            @else
                                <p class="mt-0.5 text-xs italic text-slate-400 dark:text-zinc-500">Sin descripción</p>
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($servicio->categoria)
                                <span class="inline-flex items-center rounded-xl bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 ring-1 ring-inset ring-amber-200/70 dark:bg-amber-950/50 dark:text-amber-300 dark:ring-amber-500/20">
                                    {{ $servicio->categoria }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 dark:text-zinc-500">—</span>
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-right font-bold tabular-nums text-slate-900 dark:text-white">
                            {{ $servicio->precioEnPesos() }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex min-w-8 items-center justify-center rounded-xl bg-slate-100 px-2.5 py-1 text-xs font-bold tabular-nums text-slate-700 dark:bg-zinc-700 dark:text-slate-200">
                                {{ $servicio->reservas_servicio_count }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Un solo clic: el contenedor enruta al formulario, que
                                     carga el servicio y abre el diálogo en la misma
                                     petición. `wire:loading` avisa que el viaje está en
                                     curso para que no se repita el clic. --}}
                                <button
                                    type="button"
                                    wire:click="editar({{ $servicio->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="editar({{ $servicio->id }})"
                                    wire:loading.class="opacity-60"
                                    aria-label="Editar servicio"
                                    title="Editar servicio"
                                    class="inline-flex size-9 items-center justify-center rounded-xl bg-white text-slate-500 shadow-sm ring-1 ring-inset ring-slate-200 transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-900 hover:text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-95 disabled:pointer-events-none disabled:opacity-60 dark:bg-zinc-900 dark:text-zinc-300 dark:ring-zinc-700"
                                >
                                    <flux:icon.pencil-square class="size-4" />
                                </button>

                                <button
                                    type="button"
                                    wire:click="eliminar({{ $servicio->id }})"
                                    wire:confirm="¿Eliminar este servicio del catálogo?"
                                    aria-label="Eliminar servicio"
                                    title="Eliminar servicio"
                                    class="inline-flex size-9 items-center justify-center rounded-xl bg-white text-slate-500 shadow-sm ring-1 ring-inset ring-slate-200 transition-all duration-200 hover:-translate-y-0.5 hover:bg-red-600 hover:text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-95 dark:bg-zinc-900 dark:text-zinc-300 dark:ring-zinc-700"
                                >
                                    <flux:icon.trash class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <span class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-zinc-700 dark:text-zinc-300">
                                <flux:icon.sparkles class="size-6" />
                            </span>
                            <p class="mt-4 text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $this->hayFiltrosActivos ? 'Ningún servicio coincide' : 'El catálogo está vacío' }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                {{ $this->hayFiltrosActivos
                                    ? 'Prueba con otra búsqueda o quita los filtros aplicados.'
                                    : 'Registra el primer servicio para poder cargarlo al folio de un huésped.' }}
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($this->servicios->hasPages())
        <div class="border-t border-slate-100 px-6 py-4 dark:border-zinc-700">
            {{ $this->servicios->links() }}
        </div>
    @endif
</div>
