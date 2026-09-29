{{-- ======================================================
     FORMULARIO COMPARTIDO: ALTA / EDICIÓN DE SERVICIO

     Lo incluyen `servicios.create` y `servicios.edit`; lo único que
     cambia entre ambos modos es el rótulo de $modo.
     ====================================================== --}}

@php
    /*
     | Base compartida de los campos: sin bordes duros, fondo sutil que reacciona
     | al hover y anillo de enfoque en el color de la marca. El icono usa `peer`
     | para teñirse cuando el campo toma el foco.
     */
    $campoBase = 'w-full rounded-xl border-0 border-transparent bg-slate-50 py-2.5 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 transition-all duration-200 ease-out placeholder:text-slate-400 hover:bg-slate-100 hover:ring-slate-300 focus:border-transparent focus:bg-white focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-800 dark:hover:ring-zinc-600 dark:focus:bg-zinc-900 dark:focus:ring-amber-500';

    /*
     | Relleno izquierdo de los campos con icono: el icono mide 1.25rem y está
     | anclado a `left-3.5` (0.875rem), así que ocupa hasta 2.125rem. `pl-11`
     | (2.75rem) reserva ese hueco y deja el texto limpio, sin encima del icono.
     */
    $campo = $campoBase.' pl-11 pr-3.5';

    $icono = 'pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-slate-400 transition-colors duration-200 peer-focus:text-amber-500 dark:text-slate-500 dark:peer-focus:text-amber-400';

    $etiqueta = 'mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 transition-colors duration-200 dark:text-slate-400';

    /* La llevan nombre, categoría y precio: son los datos sin los que el servicio no existe. */
    $obligatorio = '<span class="text-red-500 font-extrabold text-sm ml-0.5">*</span>';

    /*
     | `variant="soft"` hace que el desplegable replique el campo del sistema y el
     | componente calcula su propio `pl-10`, ya que el icono vive dentro del
     | botón en lugar de ser un hermano del control.
     */
    $categoriasOpciones = array_combine(\App\Models\Servicio::CATEGORIAS, \App\Models\Servicio::CATEGORIAS);
@endphp

<form wire:submit="guardar" class="w-full">
    {{-- ---------------------------------------------------
         TARJETA: escala 95 -> 100 con rebote sutil
         --------------------------------------------------- --}}
    <div class="w-full animate-modal-card-in overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/5 dark:bg-zinc-900 dark:ring-white/10">
        {{-- Encabezado --}}
        <div class="flex items-start gap-4 border-b border-slate-100 bg-gradient-to-r from-amber-50/80 to-white px-6 py-5 dark:border-zinc-800 dark:from-amber-950/40 dark:to-zinc-900">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-amber-600 text-white shadow-lg shadow-amber-600/25">
                <flux:icon.sparkles class="size-5" />
            </span>

            <div class="min-w-0 flex-1">
                <h2 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">
                    {{ $modo === 'editar' ? 'Editar servicio' : 'Nuevo servicio' }}
                </h2>
                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                    Completa los datos del catálogo. Los campos marcados con <span class="text-amber-600">*</span> son obligatorios.
                </p>
            </div>

            {{-- El cierre lo resuelve Flux en el navegador; no requiere viaje al
                 servidor. El formulario se prepara de nuevo al reabrirse. --}}
            <button
                type="button"
                x-data
                x-on:click="$dispatch('modal-close', { name: 'servicio-form' })"
                aria-label="Cerrar"
                class="-me-2 -mt-1 shrink-0 rounded-lg p-2 text-slate-400 transition-all duration-200 hover:rotate-90 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500 dark:hover:bg-zinc-800 dark:hover:text-white"
            >
                <flux:icon.x-mark class="size-5" />
            </button>
        </div>

        {{-- Campos. `wire:loading` atenúa el formulario mientras llega la
             preparación o el guardado, para que nunca se edite a ciegas. --}}
        <div
            wire:loading.class="opacity-60"
            wire:target="preparar,guardar"
            class="max-h-[65vh] space-y-5 overflow-y-auto px-6 py-6 transition-opacity duration-200"
        >
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nombre" class="{{ $etiqueta }}">Nombre {!! $obligatorio !!}</label>
                    <div class="relative">
                        <input
                            id="nombre"
                            type="text"
                            wire:model="nombre"
                            placeholder="Minibar premium"
                            required
                            class="{{ $campo }} peer"
                        />
                        <flux:icon.tag class="{{ $icono }}" />
                    </div>
                    <flux:error name="nombre" class="mt-1.5" />
                </div>

                <div>
                    <label for="categoria" class="{{ $etiqueta }}">Categoría {!! $obligatorio !!}</label>
                    <x-dropdown
                        id="categoria"
                        wire:model="categoria"
                        variant="soft"
                        required
                        :selected="$categoria"
                        :options="$categoriasOpciones"
                        class="w-full"
                    >
                        <x-slot:leadingIcon>
                            <flux:icon.squares-2x2 class="size-5" />
                        </x-slot:leadingIcon>
                    </x-dropdown>
                    <flux:error name="categoria" class="mt-1.5" />
                </div>

                <div>
                    <label for="precio" class="{{ $etiqueta }}">Precio (MXN) {!! $obligatorio !!}</label>
                    <div class="relative">
                        <input
                            id="precio"
                            type="number"
                            step="0.01"
                            min="0.01"
                            wire:model="precio"
                            placeholder="0.00"
                            required
                            class="{{ $campo }} peer"
                        />
                        <flux:icon.banknotes class="{{ $icono }}" />
                    </div>
                    <flux:error name="precio" class="mt-1.5" />
                </div>

                <div class="sm:col-span-2">
                    <label for="descripcion" class="{{ $etiqueta }}">Descripción</label>
                    <div class="relative">
                        <textarea
                            id="descripcion"
                            wire:model="descripcion"
                            rows="3"
                            placeholder="Botella de vino, refrescos y snacks artesanales en la habitación..."
                            class="{{ $campo }} peer resize-none"
                        ></textarea>
                        <flux:icon.document-text class="{{ $icono }} top-3.5 -translate-y-0" />
                    </div>
                    <flux:error name="descripcion" class="mt-1.5" />
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-900/60">
            <button
                type="button"
                x-data
                x-on:click="$dispatch('modal-close', { name: 'servicio-form' })"
                class="inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 transition-all duration-200 hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-95 dark:text-slate-300 dark:hover:bg-zinc-800 dark:hover:text-white"
            >
                Cancelar
            </button>

            {{-- `wire:loading.attr="disabled"` bloquea el doble clic mientras
                 el servidor procesa el guardado. --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="guardar"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-amber-600/25 transition-all duration-200 ease-out hover:-translate-y-0.5 hover:bg-amber-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2 active:translate-y-0 active:scale-95 disabled:pointer-events-none disabled:opacity-70 disabled:shadow-none disabled:hover:translate-y-0 disabled:hover:bg-amber-600 disabled:hover:shadow-md"
            >
                <span wire:loading.remove wire:target="guardar">
                    <flux:icon.check class="size-4" />
                </span>
                <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                    <flux:icon.arrow-path class="size-4 animate-spin" />
                    Guardando...
                </span>
                <span wire:loading.remove wire:target="guardar">
                    {{ $modo === 'editar' ? 'Guardar cambios' : 'Agregar servicio' }}
                </span>
            </button>
        </div>
    </div>
</form>
