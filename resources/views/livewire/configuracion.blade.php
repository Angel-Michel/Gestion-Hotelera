<div>
    <div class="mb-6">
        <flux:heading size="xl">Configuración</flux:heading>
        <flux:subheading>
            Información general del sistema Novastay.
        </flux:subheading>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <flux:card>
            <flux:heading size="lg" class="mb-4">Sistema</flux:heading>

            <dl class="space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Aplicación</dt>
                    <dd class="font-medium">{{ $nombreApp }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Entorno</dt>
                    <dd>
                        <flux:badge color="blue" size="sm">{{ $entorno }}</flux:badge>
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Temporada vigente</dt>
                    <dd class="font-medium">
                        @if ($temporadaVigente)
                            {{ $temporadaVigente->nombre }} (x{{ number_format($temporadaVigente->multiplicador_precio, 2) }})
                        @else
                            Sin temporada vigente
                        @endif
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Sesión iniciada como</dt>
                    <dd class="font-medium">{{ auth()->user()?->name }}</dd>
                </div>
            </dl>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Registros del sistema</flux:heading>

            <dl class="space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Usuarios</dt>
                    <dd><flux:badge color="zinc" size="sm">{{ $totalUsuarios }}</flux:badge></dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Roles</dt>
                    <dd><flux:badge color="zinc" size="sm">{{ $totalRoles }}</flux:badge></dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Permisos</dt>
                    <dd><flux:badge color="zinc" size="sm">{{ $totalPermisos }}</flux:badge></dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Habitaciones</dt>
                    <dd><flux:badge color="zinc" size="sm">{{ $totalHabitaciones }}</flux:badge></dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Clientes</dt>
                    <dd><flux:badge color="zinc" size="sm">{{ $totalClientes }}</flux:badge></dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-zinc-500">Reservaciones</dt>
                    <dd><flux:badge color="zinc" size="sm">{{ $totalReservas }}</flux:badge></dd>
                </div>
            </dl>
        </flux:card>
    </div>

    <flux:card class="mt-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="lg">Cuenta y apariencia</flux:heading>
                <flux:text size="sm">Gestiona tu perfil, contraseña y tema desde los ajustes de cuenta.</flux:text>
            </div>

            <div class="flex shrink-0 flex-wrap gap-2">
                <flux:button variant="outline" :href="route('settings.profile')" wire:navigate>
                    Mi perfil
                </flux:button>
                <flux:button variant="outline" :href="route('settings.appearance')" wire:navigate>
                    Apariencia
                </flux:button>
            </div>
        </div>
    </flux:card>
</div>
