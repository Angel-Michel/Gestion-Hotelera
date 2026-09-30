{{-- ======================================================
     BARRA DE BÚSQUEDA Y FILTROS
     ====================================================== --}}

<div class="flex gap-4 mb-6">
    <div class="w-full">
        <input
            type="text"
            wire:model.live="search"
            wire:change="actualizarSearch"
            placeholder="Buscar por nombre o descripción..."
            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm transition duration-150 placeholder:text-slate-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/10"
        />
    </div>

    <div>
        <select
            wire:model.live="categoriaFiltro"
            wire:change="actualizarCategoria"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm transition duration-150 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/10"
        >
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}">
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </div>
</div>
