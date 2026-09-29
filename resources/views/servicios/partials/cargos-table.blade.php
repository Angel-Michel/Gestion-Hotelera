{{-- ======================================================
     CARGOS AL FOLIO: ÚLTIMOS CONSUMOS REGISTRADOS

     Es la cara de lectura del control de cargos: qué se consumió, en qué
     folio, a nombre de quién y qué empleado lo registró.
     ====================================================== --}}

@php
    $puedeCargar = auth()->user()?->can('servicios.cargos') ?? false;
@endphp

<div class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-950/5 ring-1 ring-slate-900/5 dark:bg-slate-800 dark:ring-white/10">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-zinc-700">
        <div>
            <h2 class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">Cargos al folio</h2>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                Consumos extras de las habitaciones ocupadas. Se suman al total de check-out.
            </p>
        </div>

        @if ($puedeCargar)
            <button
                type="button"
                x-data
                x-on:click="$dispatch('modal-show', { name: 'servicio-cargo' })"
                class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-amber-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2 active:scale-95 dark:bg-amber-600 dark:hover:bg-amber-700"
            >
                <flux:icon.plus class="size-4" />
                Registrar consumo
            </button>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100 text-left text-sm dark:divide-zinc-700">
            <thead class="bg-slate-50/80 dark:bg-zinc-900/60">
                <tr>
                    <th scope="col" class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Folio</th>
                    <th scope="col" class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Huésped</th>
                    <th scope="col" class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Consumo</th>
                    <th scope="col" class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Registró</th>
                    <th scope="col" class="px-6 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-400">Subtotal (MXN)</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 dark:divide-zinc-700">
                @forelse ($this->cargosRecientes as $cargo)
                    @php
                        $habitaciones = $this->habitacionesDe($cargo);
                    @endphp

                    <tr wire:key="cargo-{{ $cargo->id }}" class="transition-colors duration-200 hover:bg-slate-50/70 dark:hover:bg-zinc-700/40">
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-bold tabular-nums text-slate-900 dark:text-white">#{{ $cargo->reserva_id }}</span>
                            @if ($habitaciones !== [])
                                <span class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">
                                    {{ collect($habitaciones)->map(fn (string $n): string => '#'.$n)->join(', ') }}
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ $cargo->reserva?->cliente?->nombreCompleto() ?? 'Huésped eliminado' }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $cargo->servicio?->nombre ?? 'Servicio eliminado' }}</p>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                {{ $cargo->cantidad }} × {{ $cargo->servicio?->precioEnPesos() ?? '$'.number_format($cargo->precioUnitario(), 2) }}
                            </p>
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600 dark:text-slate-400">
                            {{ $cargo->empleado?->nombre ?? 'Sin asignar' }}
                            @if ($cargo->empleado?->apellidos)
                                {{ $cargo->empleado->apellidos }}
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-right font-bold tabular-nums text-slate-900 dark:text-white">
                            ${{ number_format((float) $cargo->subtotal, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <span class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-zinc-700 dark:text-zinc-300">
                                <flux:icon.receipt-percent class="size-6" />
                            </span>
                            <p class="mt-4 text-sm font-semibold text-slate-800 dark:text-slate-200">Todavía no hay cargos</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Cuando recepción registre un consumo aparecerá aquí con su folio y responsable.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
