@use('Illuminate\Support\Str')

{{-- ======================================================
     MODAL: MATRIZ DE SEGURIDAD GRANULAR
     ====================================================== --}}

<div x-data="{ abierto: @entangle('mostrarModalPermisos') }" @keydown.escape.window="abierto = false">
    <div
        x-cloak
        x-show="abierto"
        aria-modal="true"
        role="dialog"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
        x-transition:enter="transition-all duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-all duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.self="abierto = false"
    >
        <div
            class="flex max-h-[92vh] w-full max-w-5xl flex-col rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 dark:bg-zinc-900 dark:ring-zinc-700 sm:p-8"
            x-transition:enter="transition-all duration-300 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition-all duration-200 ease-in"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        >
            <form wire:submit="guardarPermisosGranulares" class="flex flex-col overflow-hidden">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/15">
                            <flux:icon.shield-check class="size-5 text-amber-600 dark:text-amber-400" />
                        </span>
                        <div>
                            <flux:heading size="lg" class="!text-slate-800 !font-semibold dark:!text-slate-100">Seguridad Granular</flux:heading>
                            <flux:subheading class="!text-slate-600 !font-medium dark:!text-slate-400">
                                Matriz de permisos directos de
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $empleadoNombrePermisos }}</span>.
                            </flux:subheading>
                        </div>
                    </div>

                    <button
                        type="button"
                        aria-label="Cerrar"
                        @click="abierto = false"
                        class="shrink-0 rounded-lg p-1.5 text-slate-400 transition-all duration-300 ease-out hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-90 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                    >
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                <flux:callout color="sky" icon="information-circle" class="mb-4">
                    <p>
                        Estos permisos se asignan directamente al usuario y son independientes
                        de los que otorga su <strong>Rol del Sistema</strong>. Solo el Super Admin
                        puede modificarlos.
                    </p>
                </flux:callout>

                <div class="min-h-0 overflow-auto rounded-xl border border-slate-200 dark:border-zinc-700">
                    <table class="w-full min-w-[720px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/90 dark:border-zinc-700 dark:bg-zinc-800/60">
                                <th scope="col" class="sticky top-0 z-10 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:bg-zinc-800/90 dark:text-zinc-300">
                                    Módulo
                                </th>

                                @foreach ($accionesVisibles as $accion)
                                    <th scope="col" class="sticky top-0 z-10 bg-slate-50 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 dark:bg-zinc-800/90 dark:text-zinc-300">
                                        {{ $accion['etiqueta'] }}
                                    </th>
                                @endforeach

                                <th scope="col" class="sticky top-0 z-10 bg-slate-50 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-amber-600 dark:bg-zinc-800/90 dark:text-amber-400">
                                    TODOS
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($this->permisosPorModulo as $modulo => $permisosDelModulo)
                                <tr class="transition-colors duration-200 hover:bg-slate-50/70">
                                    <td class="px-4 py-3">
                                        <span class="flex items-center gap-3">
                                            <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-amber-500/10">
                                                <flux:icon :name="$this->iconoModulo($modulo)" class="size-4 text-amber-600" />
                                            </span>
                                            <span class="font-semibold text-slate-900 dark:text-zinc-200">
                                                {{ $this->etiquetaModulo($modulo) }}
                                            </span>
                                        </span>
                                    </td>

                                    @foreach ($accionesVisibles as $accion)
                                        @php
                                            $permiso = $permisosDelModulo->first(
                                                fn ($p) => Str::after($p->name, '.') === $accion['clave']
                                            );
                                        @endphp

                                        <td class="px-3 py-3 text-center">
                                            @if ($permiso)
                                                <label class="inline-flex cursor-pointer items-center" title="{{ $accion['etiqueta'] }} en {{ $this->etiquetaModulo($modulo) }}">
                                                    <input
                                                        type="checkbox"
                                                        wire:model.live="permisosUsuario"
                                                        value="{{ $permiso->name }}"
                                                        class="peer sr-only"
                                                    />
                                                    <span class="flex size-5 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent shadow-sm transition-all duration-200 ease-out hover:border-amber-400 active:scale-90 peer-checked:border-amber-500 peer-checked:bg-amber-500 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500 peer-focus-visible:ring-offset-1 dark:border-zinc-600 dark:bg-zinc-800 dark:peer-checked:border-amber-500 dark:peer-checked:bg-amber-500">
                                                        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M16.704 4.81a.75.75 0 0 1 .04 1.06l-8.5 9a.75.75 0 0 1-1.075-.034l-4.5-5a.75.75 0 1 1 1.08-1.04l3.96 4.4 7.96-8.42a.75.75 0 0 1 1.06-.046Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </label>
                                            @else
                                                <span class="inline-block text-slate-300 dark:text-zinc-600" title="Este módulo no posee la acción {{ strtolower($accion['etiqueta']) }}">—</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="px-3 py-3 text-center">
                                        <label class="inline-flex cursor-pointer items-center" title="Seleccionar o deseleccionar todas las acciones de {{ $this->etiquetaModulo($modulo) }}">
                                            <input
                                                type="checkbox"
                                                wire:click="alternarTodosDelModulo('{{ $modulo }}')"
                                                @checked($this->todosSeleccionadosDelModulo($modulo))
                                                class="peer sr-only"
                                            />
                                            <span class="flex size-5 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent shadow-sm transition-all duration-200 ease-out hover:border-amber-500 active:scale-90 peer-checked:border-amber-600 peer-checked:bg-amber-600 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-amber-600 peer-focus-visible:ring-offset-1 dark:border-zinc-600 dark:bg-zinc-800 dark:peer-checked:border-amber-600 dark:peer-checked:bg-amber-600">
                                                <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M16.704 4.81a.75.75 0 0 1 .04 1.06l-8.5 9a.75.75 0 0 1-1.075-.034l-4.5-5a.75.75 0 1 1 1.08-1.04l3.96 4.4 7.96-8.42a.75.75 0 0 1 1.06-.046Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $accionesVisibles->count() + 2 }}" class="px-4 py-10 text-center">
                                        <p class="text-sm text-slate-500">No hay permisos registrados en el sistema.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-zinc-800">
                    <p class="text-xs text-slate-500">
                        <span class="font-semibold text-slate-700">{{ count($permisosUsuario) }}</span>
                        permiso(s) directo(s) seleccionados.
                    </p>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            @click="abierto = false"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-slate-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-amber-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 active:scale-95"
                        >
                            <span wire:loading.remove wire:target="guardarPermisosGranulares">
                                <flux:icon.check class="size-4" />
                            </span>
                            <span wire:loading wire:target="guardarPermisosGranulares">Guardando...</span>
                            <span wire:loading.remove wire:target="guardarPermisosGranulares">Guardar permisos</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>