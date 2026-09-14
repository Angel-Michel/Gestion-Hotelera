@props(['estado'])

@php
    $color = match ($estado) {
        'Disponible', 'Confirmada', 'Completado', 'Activo' => 'emerald',
        'Ocupada', 'Pendiente', 'En Proceso' => 'amber',
        'Limpieza', 'Mantenimiento' => 'sky',
        'Finalizada', 'Inactivo' => 'zinc',
        'Cancelada' => 'rose',
        default => 'zinc',
    };
@endphp

<flux:badge :color="$color" size="sm">{{ $estado }}</flux:badge>
