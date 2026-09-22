<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    @include('clientes.partials.header')

    @include('clientes.partials.table')

    @include('clientes.partials.modal-form')
</div>