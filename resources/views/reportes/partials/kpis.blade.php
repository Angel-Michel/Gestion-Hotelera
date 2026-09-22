<div class="mb-6">
    <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Reportes</flux:heading>
    <flux:subheading class="!text-slate-600 !font-medium">
        Indicadores y resúmenes de la operación del hotel.
    </flux:subheading>
</div>

<div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <flux:card>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">Ocupación</flux:text>
        <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">{{ $ocupacion }}%</p>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">{{ $habitacionesOcupadas }} de {{ $totalHabitaciones }} habitaciones</flux:text>
    </flux:card>

    <flux:card>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">Ingresos totales</flux:text>
        <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">${{ number_format($ingresosTotales, 2) }}</p>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">Suma de pagos registrados</flux:text>
    </flux:card>

    <flux:card>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">Gastos totales</flux:text>
        <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">${{ number_format($gastosTotales, 2) }}</p>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">Suma de gastos registrados</flux:text>
    </flux:card>

    <flux:card>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">Balance</flux:text>
        <p class="mt-1 text-3xl font-semibold text-amber-600 dark:text-amber-400">${{ number_format($balance, 2) }}</p>
        <flux:text size="sm" class="!text-slate-600 !font-semibold">Ingresos menos gastos</flux:text>
    </flux:card>
</div>
