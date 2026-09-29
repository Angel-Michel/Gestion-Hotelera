{{-- ======================================================
     ENCABEZADO, TARJETAS KPI Y FILTROS DEL CATÁLOGO
     ====================================================== --}}

@php
    $todas = \App\Livewire\Servicios\Index::FILTRO_TODAS;
    $conteo = $this->conteoPorCategoria;

    /*
     | El desplegable solo ofrece categorías que tienen servicios: filtrar por
     | una categoría vacía solo llevaría a un catálogo sin resultados.
     */
    $categoriasOpciones = [$todas => 'Todas las categorías'] + collect(\App\Models\Servicio::CATEGORIAS)
        ->filter(fn (string $categoria): bool => array_key_exists($categoria, $conteo))
        ->mapWithKeys(fn (string $categoria): array => [$categoria => $categoria.' · '.$conteo[$categoria]])
        ->all();

    $tarjetas = [
        ['etiqueta' => 'En catálogo', 'valor' => $this->totalServicios, 'sufijo' => 'servicios', 'icono' => 'tag', 'iconoFondo' => 'bg-slate-100', 'iconoTexto' => 'text-slate-600'],
        ['etiqueta' => 'Categorías', 'valor' => $this->totalCategorias, 'sufijo' => 'activas', 'icono' => 'squares-2x2', 'iconoFondo' => 'bg-amber-50', 'iconoTexto' => 'text-amber-500'],
        ['etiqueta' => 'Cargos registrados', 'valor' => $this->totalCargos, 'sufijo' => 'en folios', 'icono' => 'receipt-percent', 'iconoFondo' => 'bg-amber-50', 'iconoTexto' => 'text-amber-500'],
        ['etiqueta' => 'Consumos facturados', 'valor' => '$'.number_format($this->ingresosPorServicios, 2), 'sufijo' => 'MXN', 'icono' => 'banknotes', 'iconoFondo' => 'bg-slate-100', 'iconoTexto' => 'text-slate-600'],
    ];
@endphp

<div class="animate-fade-in">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Servicios</h1>
            <p class="mt-1 text-sm font-medium text-slate-500 dark:text-slate-400">
                {{ $this->totalServicios }} {{ $this->totalServicios === 1 ? 'servicio en catálogo' : 'servicios en catálogo' }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Sin `wire:click`: el <dialog> se abre con `modal-show` en el
                 navegador y `abrir-formulario` carga los datos. Al no pasar por
                 el contenedor, ningún morph puede cerrar el modal recién abierto. --}}
            @can('servicios.cargos')
                <button
                    type="button"
                    x-data
                    x-on:click="$dispatch('modal-show', { name: 'servicio-cargo' })"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-slate-50 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-95 dark:border-zinc-700 dark:bg-slate-800 dark:text-slate-200"
                >
                    <flux:icon.receipt-percent class="size-4 text-amber-600" />
                    Cargar consumo
                </button>
            @endcan

            <button
                type="button"
                x-data
                x-on:click="$dispatch('modal-show', { name: 'servicio-form' }); $dispatch('abrir-formulario', { id: null })"
                class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-amber-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2 active:scale-95 dark:bg-amber-600 dark:hover:bg-amber-700"
            >
                <flux:icon.plus class="size-4" />
                Nuevo servicio
            </button>
        </div>
    </div>

    {{-- KPIs de la operación --}}
    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($tarjetas as $kpi)
            <div class="rounded-2xl border-t-4 border-t-slate-900 bg-white p-4 shadow-sm transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-md dark:border-t-slate-100 dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $kpi['etiqueta'] }}</span>
                        <span class="mt-2 block truncate text-3xl font-black tabular-nums text-slate-800 dark:text-slate-100">{{ $kpi['valor'] }}</span>
                        <span class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">{{ $kpi['sufijo'] }}</span>
                    </div>
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full {{ $kpi['iconoFondo'] }} {{ $kpi['iconoTexto'] }}">
                        <flux:icon :name="$kpi['icono']" class="size-4" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Búsqueda y filtro por categoría --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-[1fr_18rem]">
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-zinc-500">
                <flux:icon.magnifying-glass class="size-5" />
            </span>
            <input
                type="text"
                wire:model.live.debounce.400ms="search"
                placeholder="Buscar por nombre o descripción..."
                aria-label="Buscar servicios"
                class="block w-full rounded-2xl border-0 bg-white py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 transition-all duration-300 ease-out placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-slate-800 dark:text-white dark:ring-zinc-700 dark:placeholder:text-zinc-500"
            />
        </div>

        <x-dropdown
            wire:change="filtrarPorCategoria($event.target.value)"
            aria-label="Filtrar por categoría"
            variant="soft"
            :selected="$filtroCategoria"
            :options="$categoriasOpciones"
        >
            <x-slot:leadingIcon>
                <flux:icon.funnel class="size-4" />
            </x-slot:leadingIcon>
        </x-dropdown>
    </div>

    {{-- Píldoras de categoría: filtran el catálogo con un clic --}}
    @if (count($conteo) > 1)
        <div class="mt-4 flex flex-wrap items-center gap-2">
            <button
                type="button"
                wire:click="filtrarPorCategoria('{{ $todas }}')"
                aria-pressed="{{ $filtroCategoria === $todas ? 'true' : 'false' }}"
                @class([
                    'rounded-xl px-3.5 py-2 text-xs font-semibold transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-500',
                    'bg-slate-900 text-white shadow-sm dark:bg-white dark:text-slate-900' => $filtroCategoria === $todas,
                    'bg-white text-slate-600 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 dark:bg-slate-800 dark:text-slate-300 dark:ring-zinc-700' => $filtroCategoria !== $todas,
                ])
            >
                Todas
                <span class="ml-1.5 tabular-nums opacity-70">{{ $this->totalServicios }}</span>
            </button>

            @foreach ($conteo as $categoria => $categoriaConteo)
                <button
                    type="button"
                    wire:click="filtrarPorCategoria('{{ $categoria }}')"
                    aria-pressed="{{ $filtroCategoria === $categoria ? 'true' : 'false' }}"
                    @class([
                        'rounded-xl px-3.5 py-2 text-xs font-semibold transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-500',
                        'bg-amber-600 text-white shadow-sm shadow-amber-600/20' => $filtroCategoria === $categoria,
                        'bg-white text-slate-600 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 dark:bg-slate-800 dark:text-slate-300 dark:ring-zinc-700' => $filtroCategoria !== $categoria,
                    ])
                >
                    {{ $categoria }}
                    <span class="ml-1.5 tabular-nums opacity-70">{{ $categoriaConteo }}</span>
                </button>
            @endforeach
        </div>
    @endif
</div>
