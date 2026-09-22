@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de paginación">
        <div class="flex items-center justify-between gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-400">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 ease-out hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700 active:scale-95">
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 ease-out hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700 active:scale-95">
                    Siguiente
                </a>
            @else
                <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-400">
                    Siguiente
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:items-center sm:justify-between sm:gap-2">
            <div>
                <p class="text-sm text-slate-500">
                    Mostrando
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>
                        a
                        <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    de
                    <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="inline-flex flex-wrap items-center gap-1">
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Página anterior">
                            <span class="inline-flex size-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300">
                                <flux:icon.chevron-left class="size-4" />
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior" class="inline-flex size-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all duration-200 ease-out hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700 active:scale-95">
                            <flux:icon.chevron-left class="size-4" />
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex size-8 items-center justify-center rounded-lg text-sm font-medium text-slate-400">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex size-8 items-center justify-center rounded-lg bg-amber-500 text-sm font-semibold text-white shadow-sm shadow-amber-500/30">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" aria-label="Ir a la página {{ $page }}" class="inline-flex size-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 ease-out hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700 active:scale-95">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente" class="inline-flex size-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all duration-200 ease-out hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700 active:scale-95">
                            <flux:icon.chevron-right class="size-4" />
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Página siguiente">
                            <span class="inline-flex size-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300">
                                <flux:icon.chevron-right class="size-4" />
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif