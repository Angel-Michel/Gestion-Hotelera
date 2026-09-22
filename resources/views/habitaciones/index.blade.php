<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    @include('habitaciones.partials.header')

    @include('habitaciones.partials.table')

    @include('habitaciones.partials.modal-form')
</div>