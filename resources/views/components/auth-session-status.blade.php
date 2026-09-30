@props([
    'status',
])

@if ($status)
    <div
        x-data="{ visible: true }"
        x-init="setTimeout(() => visible = false, 5000)"
        x-show="visible"
        x-transition.duration.300ms
        {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}
    >
        {{ $status }}
    </div>
@endif
