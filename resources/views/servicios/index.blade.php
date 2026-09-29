{{-- ======================================================
     PANTALLA DEL MÓDULO DE SERVICIOS

     El contenedor no lleva booleanos de visibilidad: los dos <dialog>
     los abre Flux desde el navegador con `modal-show`, de modo que
     ningún morph del contenedor puede cerrarlos por accidente.
     ====================================================== --}}

<div>
    @if ($mensajeExito)
        <div class="mb-4 animate-fade-in">
            <flux:callout variant="success" icon="check-circle">
                <p>{{ $mensajeExito }}</p>
            </flux:callout>
        </div>
    @endif

    @if ($mensajeError)
        <div class="mb-4 animate-fade-in">
            <flux:callout variant="danger" icon="exclamation-triangle">
                <p>{{ $mensajeError }}</p>
            </flux:callout>
        </div>
    @endif

    @include('servicios.partials.header')

    @if ($this->hayFiltrosActivos)
        @php
            $visibles = $this->servicios->total();
            $totales = $this->totalServicios;
        @endphp

        <div class="mt-4 animate-fade-in">
            <flux:callout variant="secondary" icon="information-circle">
                <p>Mostrando {{ $visibles }} {{ $visibles === 1 ? 'servicio' : 'servicios' }} de {{ $totales }}.</p>
            </flux:callout>
        </div>
    @endif

    <div class="mt-8 animate-fade-in-up">
        @include('servicios.partials.table')
    </div>

    @can('servicios.cargos')
        <div class="mt-8 animate-fade-in-up [animation-delay:120ms]">
            @include('servicios.partials.cargos-table')
        </div>
    @endcan

    {{-- `wire:ignore` (no `.self`) es obligatorio en la raíz del modal. Flux solo
         protege el <dialog> con `wire:ignore.self`, dejando a su envoltorio
         <ui-modal> expuesto: si el contenedor re-renderiza, el morph recrea el
         <dialog> y pierde el atributo `open` que puso showModal(), lo que
         provoca el parpadeo. Ignorando todo el subárbol el estado nativo
         sobrevive a cualquier re-renderizado del contenedor. Los formularios son
         subcomponentes con clave estable, así que siguen actualizándose con sus
         propias peticiones. --}}
    <div wire:ignore>
        {{-- `variant="bare"` deja el <dialog> transparente: el cristal de fondo,
             la tarjeta blanca y las animaciones se construyen en los parciales. --}}
        <flux:modal
            name="servicio-form"
            variant="bare"
            class="novastay-servicio-modal w-full max-w-2xl"
        >
            {{-- `__lazyLoad` difiere la consulta del formulario hasta que el
                 modal se abre. --}}
            <livewire:servicios.form-modal wire:key="formulario-servicio" />
        </flux:modal>
    </div>

    @can('servicios.cargos')
        <div wire:ignore>
            <flux:modal
                name="servicio-cargo"
                variant="bare"
                class="novastay-servicio-modal w-full max-w-3xl"
            >
                <livewire:servicios.cargos wire:key="formulario-cargo" />
            </flux:modal>
        </div>
    @endcan
</div>
