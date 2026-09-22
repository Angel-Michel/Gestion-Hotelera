<div>
    <div class="mb-4">
        <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Matriz de Permisos</flux:heading>
        <flux:subheading class="!text-slate-600 !font-medium">
            Vista granular de permisos: cada fila es un permiso y cada columna un rol.
        </flux:subheading>
    </div>

    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    @if ($mensajeError)
        <flux:callout variant="danger" icon="x-circle" class="mb-4">
            <p>{{ $mensajeError }}</p>
        </flux:callout>
    @endif

    @if (! $this->puedeEditar())
        <flux:callout variant="warning" icon="lock-closed" class="mb-4">
            <p>
                Solo el rol <strong>Super Admin</strong> puede modificar los permisos.
                Los controles se muestran deshabilitados para el resto de roles.
            </p>
        </flux:callout>
    @endif

    @include('matriz-permisos.partials.tabla')
</div>
