@use('Illuminate\Support\Str')

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
            <flux:callout variant="danger" icon="x-circle">
                <p>{{ $mensajeError }}</p>
            </flux:callout>
        </div>
    @endif

    {{-- ======================================================
         ENCABEZADO
         ====================================================== --}}

    <div class="mb-8 flex animate-fade-in flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500/15 shadow-sm shadow-amber-500/20">
                <flux:icon.identification class="size-6 text-amber-600 dark:text-amber-400" />
            </span>

            <div>
                <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl dark:!text-white">Empleados</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium dark:!text-slate-300">
                    <span class="inline-flex items-center gap-2">
                        <span class="relative flex size-2" aria-hidden="true">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                        </span>
                        Gestiona el personal del hotel, sus datos y su acceso al sistema.
                    </span>
                </flux:subheading>
            </div>
        </div>

        @if ($this->esSuperAdmin())
            <button
                type="button"
                wire:click="crear"
                class="group inline-flex shrink-0 items-center justify-center gap-2.5 rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-200 ease-out hover:scale-105 hover:bg-amber-600 hover:shadow-xl hover:shadow-amber-500/40 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
            >
                <span class="flex size-6 items-center justify-center rounded-full bg-white/20 transition-transform duration-200 ease-out group-hover:rotate-90">
                    <flux:icon.plus class="size-4" />
                </span>
                Crear Nuevo Empleado
            </button>
        @endif
    </div>

    {{-- ======================================================
         TARJETAS MÉTRICAS POR PUESTO (KPIs)
         ====================================================== --}}

    <div class="mb-4 flex animate-fade-in items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.chart-bar class="size-5 text-slate-400 transition-colors duration-300 dark:text-slate-500" />
            <h2 class="text-base font-bold text-slate-900 dark:text-white">Personal por puesto</h2>
        </div>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Conteo en tiempo real</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        @foreach ($this->kpisPuestos as $kpi)
            <div
                class="group animate-fade-in-up rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 ease-out hover:-translate-y-1 hover:border-slate-200 hover:shadow-md active:scale-[0.98] dark:border-slate-700/50 dark:bg-slate-800 dark:hover:border-slate-600"
                style="animation-delay: {{ $loop->index * 70 }}ms"
            >
                <div class="flex items-start justify-between gap-3">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl {{ $kpi['color_fondo'] }} transition-transform duration-300 ease-out group-hover:scale-110">
                        <flux:icon :name="$kpi['icono']" class="size-5 {{ $kpi['color_texto'] }} transition-colors duration-300" />
                    </span>
                    <span class="text-3xl font-bold leading-none text-slate-900 dark:text-white">{{ $kpi['conteo'] }}</span>
                </div>

                <div class="mt-4">
                    <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">{{ $kpi['etiqueta'] }}</p>
                    <p class="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
                        {{ $kpi['conteo'] === 1 ? 'empleado' : 'empleados' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ======================================================
         FILTROS
         ====================================================== --}}

    <div class="mb-6 animate-fade-in-up rounded-2xl border border-slate-200 bg-white p-5 shadow-sm [animation-delay:80ms] dark:border-slate-700 dark:bg-slate-800">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="busqueda-empleados" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Buscar
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                        <flux:icon.magnifying-glass class="size-4" />
                    </span>
                    <input
                        id="busqueda-empleados"
                        type="text"
                        wire:model.live="busqueda"
                        placeholder="Nombre, correo electrónico…"
                        class="block w-full rounded-xl border-0 bg-white py-2.5 pl-10 pr-3.5 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                    />
                </div>
            </div>

            <div>
                <label for="filtro-estado" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Estado
                </label>
                <select
                    id="filtro-estado"
                    wire:model.live="filtroEstado"
                    class="block w-full rounded-xl border-0 bg-white py-2.5 pl-3.5 pr-8 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                >
                    <option value="todos">Todos</option>
                    <option value="activos">Activos</option>
                    <option value="inactivos">Inactivos</option>
                </select>
            </div>

            <div class="flex items-end">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $empleados->total() }}</span>
                    {{ $empleados->total() === 1 ? 'empleado encontrado' : 'empleados encontrados' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ======================================================
         TABLA DE EMPLEADOS
         ====================================================== --}}

    <div class="animate-fade-in-up overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm [animation-delay:160ms] dark:border-slate-700 dark:bg-slate-800">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-700/60">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white">Lista del personal</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Perfil, turno y nivel de acceso del equipo.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1180px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 dark:border-slate-700/60 dark:bg-slate-800/80">
                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Nombre</th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Apellidos</th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Teléfono</th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Rol del Sistema</th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Salario</th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Turno</th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Correo electrónico</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Estado</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Permisos</th>
                        @if ($this->esSuperAdmin())
                            <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    @forelse ($empleados as $empleado)
                        @php $rolEmpleado = $empleado->usuario?->roles->first()?->name; @endphp

                        <tr class="transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-amber-500/10 text-xs font-bold text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                        {{ Str::of($empleado->nombre)->substr(0, 1)->upper() }}{{ Str::of($empleado->apellidos)->substr(0, 1)->upper() }}
                                    </span>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $empleado->nombre }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-300">{{ $empleado->apellidos }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-600 dark:text-slate-400">{{ $empleado->telefono ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-4">
                                @if ($rolEmpleado)
                                    <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold dark:!bg-amber-500/20 dark:!text-amber-400">
                                        {{ Str::headline($rolEmpleado) }}
                                    </flux:badge>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-300">
                                {{ $empleado->salario !== null ? '$ '.number_format((float) $empleado->salario, 2) : '—' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                @if ($empleado->turno)
                                    <span class="inline-flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                        <flux:icon.clock class="size-3.5 text-slate-400 dark:text-slate-500" />
                                        {{ $empleado->turno }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-600 dark:text-slate-400">{{ $empleado->correo_electronico ?? $empleado->usuario?->email ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-center">
                                <x-estado-badge :estado="$empleado->esta_activo ? 'Activo' : 'Inactivo'" />
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-center">
                                @if ($this->esSuperAdmin())
                                    <button
                                        type="button"
                                        wire:click="abrirModalPermisos({{ $empleado->id_empleado }})"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm transition-all duration-200 ease-out hover:scale-[1.02] hover:border-amber-300 hover:bg-amber-50 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-slate-600 dark:bg-slate-800 dark:text-amber-400 dark:hover:border-amber-500/60 dark:hover:bg-amber-500/10"
                                    >
                                        <flux:icon.key class="size-3.5 text-amber-600 transition-colors duration-200 dark:text-amber-400" />
                                        Configurar
                                    </button>
                                @else
                                    <span class="inline-flex items-center text-slate-400 dark:text-slate-600" title="Solo el Super Admin puede configurar permisos">
                                        <flux:icon.lock-closed class="size-4" />
                                    </span>
                                @endif
                            </td>

                            @if ($this->esSuperAdmin())
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $empleado->id_empleado }})" class="transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                            <flux:icon.pencil-square class="size-4 text-slate-500 transition-colors duration-200 dark:text-slate-400" />
                                            Editar
                                        </flux:button>

                                        <flux:button type="button" size="sm" variant="outline" wire:click="toggleActivo({{ $empleado->id_empleado }})" class="transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                            <flux:icon.power class="size-4 text-slate-500 transition-colors duration-200 dark:text-slate-400" />
                                            {{ $empleado->esta_activo ? 'Desactivar' : 'Activar' }}
                                        </flux:button>

                                        <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $empleado->id_empleado }})" wire:confirm="¿Eliminar este empleado?" class="transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                            <flux:icon.trash class="size-4" />
                                            Eliminar
                                        </flux:button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $this->esSuperAdmin() ? 10 : 9 }}" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="flex size-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700/40">
                                        <flux:icon.users class="size-7 text-slate-400 dark:text-slate-500" />
                                    </span>
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">No hay empleados registrados</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron empleados que coincidan con la búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-center dark:border-slate-700/60">
            <div class="flex w-full justify-center overflow-x-auto">
                {{ $empleados->links() }}
            </div>
        </div>
    </div>

    {{-- ======================================================
         MODAL: CREAR / EDITAR EMPLEADO
         ====================================================== --}}

    <div x-data="{ abierto: @entangle('mostrarModal') }" @keydown.escape.window="abierto = false">
        <div
            x-cloak
            x-show="abierto"
            aria-modal="true"
            role="dialog"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            x-transition:enter="transition-all duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-all duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="abierto = false"
        >
            <div
                class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 dark:bg-zinc-900 dark:ring-zinc-700 sm:p-8"
                x-transition:enter="transition-all duration-300 ease-out"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition-all duration-200 ease-in"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
            >
                <form wire:submit="guardar">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/15">
                                <flux:icon.user-plus class="size-5 text-amber-600 dark:text-amber-400" />
                            </span>
                            <div>
                                <flux:heading size="lg" class="!text-slate-800 !font-semibold dark:!text-slate-100">
                                    {{ $empleadoId ? 'Editar Empleado' : 'Crear Nuevo Empleado' }}
                                </flux:heading>
                                <flux:subheading class="!text-slate-600 !font-medium dark:!text-slate-400">
                                    {{ $empleadoId
                                        ? 'Actualiza los datos personales del empleado y su acceso al sistema.'
                                        : 'Registra al empleado y define si podrá acceder al sistema.' }}
                                </flux:subheading>
                            </div>
                        </div>

                        <button
                            type="button"
                            aria-label="Cerrar"
                            @click="abierto = false"
                            class="shrink-0 rounded-lg p-1.5 text-slate-400 transition-all duration-300 ease-out hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-90 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                        >
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="nombre" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Nombre</label>
                            <div class="relative">
                                <input
                                    id="nombre"
                                    type="text"
                                    wire:model.blur="nombre"
                                    placeholder="Ejemplo: Juan"
                                    required
                                    autocomplete="off"
                                    @class([$this->claseInput('nombre')])
                                />
                                @if (! $errors->has('nombre') && trim($nombre) !== '')
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <flux:icon.check-circle class="size-5 text-emerald-500" />
                                    </span>
                                @endif
                            </div>
                            @error('nombre')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="apellidos" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Apellidos</label>
                            <div class="relative">
                                <input
                                    id="apellidos"
                                    type="text"
                                    wire:model.blur="apellidos"
                                    placeholder="Ejemplo: Pérez López"
                                    required
                                    autocomplete="off"
                                    @class([$this->claseInput('apellidos')])
                                />
                                @if (! $errors->has('apellidos') && trim($apellidos) !== '')
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <flux:icon.check-circle class="size-5 text-emerald-500" />
                                    </span>
                                @endif
                            </div>
                            @error('apellidos')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefono" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Teléfono</label>
                            <div class="relative">
                                <input
                                    id="telefono"
                                    type="tel"
                                    wire:model.blur="telefono"
                                    placeholder="Ejemplo: 5512345678"
                                    @class([$this->claseInput('telefono')])
                                />
                                @if (! $errors->has('telefono') && trim($telefono) !== '')
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <flux:icon.check-circle class="size-5 text-emerald-500" />
                                    </span>
                                @endif
                            </div>
                            @error('telefono')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rol" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Rol del Sistema</label>
                            <select
                                id="rol"
                                wire:model.blur="rol"
                                class="block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                            >
                                <option value="">Sin rol asignado…</option>
                                @foreach ($this->rolesDisponibles as $rolDisponible)
                                    <option value="{{ $rolDisponible->name }}">{{ Str::headline($rolDisponible->name) }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1.5 text-xs text-slate-500">Categoría principal del empleado. No incluye a Super Admin.</p>
                            @error('rol')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="correo_electronico" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Correo Electrónico</label>
                            <div class="relative">
                                <input
                                    id="correo_electronico"
                                    type="email"
                                    wire:model.blur="correo_electronico"
                                    placeholder="empleado@hotel.com"
                                    required
                                    @class([$this->claseInput('correo_electronico')])
                                />
                                @if (! $errors->has('correo_electronico') && trim($correo_electronico) !== '')
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <flux:icon.check-circle class="size-5 text-emerald-500" />
                                    </span>
                                @endif
                            </div>
                            @error('correo_electronico')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="salario" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Salario</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-sm text-slate-400">$</span>
                                <input
                                    id="salario"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    wire:model.blur="salario"
                                    placeholder="0.00"
                                    class="block w-full rounded-xl border-0 bg-white py-2.5 pl-8 pr-3.5 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                                />
                            </div>
                            @error('salario')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="turno" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Turno</label>
                            <select
                                id="turno"
                                wire:model.blur="turno"
                                class="block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                            >
                                <option value="">Selecciona un turno…</option>
                                @foreach ($turnos as $turnoDisponible)
                                    <option value="{{ $turnoDisponible }}">{{ $turnoDisponible }}</option>
                                @endforeach
                            </select>
                            @error('turno')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="esta_activo" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-zinc-200">Estado</label>
                            <select
                                id="esta_activo"
                                wire:model.blur="esta_activo"
                                class="block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                            >
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('esta_activo')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <div class="flex items-start justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">Acceso al sistema</p>
                                    <p class="mt-0.5 text-sm text-slate-500">
                                        {{ $acceso_sistema ? 'El empleado podrá iniciar sesión en la plataforma.' : 'El empleado no podrá iniciar sesión en la plataforma.' }}
                                    </p>
                                </div>

                                <label class="inline-flex shrink-0 cursor-pointer items-center">
                                    <input type="checkbox" wire:model="acceso_sistema" class="peer sr-only" />
                                    <span class="relative h-6 w-11 rounded-full bg-slate-300 transition-colors duration-300 ease-out after:absolute after:left-1 after:top-1 after:size-4 after:rounded-full after:bg-white after:shadow after:transition-transform after:duration-300 after:ease-out peer-checked:bg-amber-500 peer-checked:after:translate-x-5 peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500 peer-focus-visible:ring-offset-1 dark:bg-slate-600"></span>
                                </label>
                            </div>
                            @error('acceso_sistema')
                                <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($acceso_sistema)
                            <div class="sm:col-span-2">
                                <div class="animate-fade-in flex items-start justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                                    <div>
                                        <label for="contrasena" class="text-sm font-semibold text-slate-800 dark:text-zinc-200">
                                            Contraseña {{ $empleadoId ? '(opcional)' : 'inicial' }}
                                        </label>
                                        <p class="mt-0.5 text-sm text-slate-500">
                                            @if ($empleadoId)
                                                Déjala vacía para conservar la contraseña actual.
                                            @else
                                                Mínimo 8 caracteres.
                                            @endif
                                        </p>
                                    </div>

                                    <input
                                        id="contrasena"
                                        type="password"
                                        wire:model.blur="contrasena"
                                        placeholder="••••••••"
                                        autocomplete="new-password"
                                        class="block w-44 rounded-xl border-0 bg-white px-3.5 py-2 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                                    />
                                </div>
                                @error('contrasena')
                                    <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="abierto = false"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-slate-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-amber-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 active:scale-95"
                        >
                            <span wire:loading.remove wire:target="guardar">
                                <flux:icon.check class="size-4" />
                            </span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                            <span wire:loading.remove wire:target="guardar">{{ $empleadoId ? 'Guardar cambios' : 'Crear empleado' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ======================================================
         MODAL: MATRIZ DE SEGURIDAD GRANULAR
         ====================================================== --}}

    @php
        $accionesVisibles = collect($this->accionesMatriz());
    @endphp

    <div x-data="{ abierto: @entangle('mostrarModalPermisos') }" @keydown.escape.window="abierto = false">
        <div
            x-cloak
            x-show="abierto"
            aria-modal="true"
            role="dialog"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            x-transition:enter="transition-all duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-all duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="abierto = false"
        >
            <div
                class="flex max-h-[92vh] w-full max-w-5xl flex-col rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 dark:bg-zinc-900 dark:ring-zinc-700 sm:p-8"
                x-transition:enter="transition-all duration-300 ease-out"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition-all duration-200 ease-in"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
            >
                <form wire:submit="guardarPermisosGranulares" class="flex flex-col overflow-hidden">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/15">
                                <flux:icon.shield-check class="size-5 text-amber-600 dark:text-amber-400" />
                            </span>
                            <div>
                                <flux:heading size="lg" class="!text-slate-800 !font-semibold dark:!text-slate-100">Seguridad Granular</flux:heading>
                                <flux:subheading class="!text-slate-600 !font-medium dark:!text-slate-400">
                                    Matriz de permisos directos de
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $empleadoNombrePermisos }}</span>.
                                </flux:subheading>
                            </div>
                        </div>

                        <button
                            type="button"
                            aria-label="Cerrar"
                            @click="abierto = false"
                            class="shrink-0 rounded-lg p-1.5 text-slate-400 transition-all duration-300 ease-out hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-90 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                        >
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>

                    <flux:callout color="sky" icon="information-circle" class="mb-4">
                        <p>
                            Estos permisos se asignan directamente al usuario y son independientes
                            de los que otorga su <strong>Rol del Sistema</strong>. Solo el Super Admin
                            puede modificarlos.
                        </p>
                    </flux:callout>

                    <div class="min-h-0 overflow-auto rounded-xl border border-slate-200 dark:border-zinc-700">
                        <table class="w-full min-w-[720px] border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/90 dark:border-zinc-700 dark:bg-zinc-800/60">
                                    <th scope="col" class="sticky top-0 z-10 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:bg-zinc-800/90 dark:text-zinc-300">
                                        Módulo
                                    </th>

                                    @foreach ($accionesVisibles as $accion)
                                        <th scope="col" class="sticky top-0 z-10 bg-slate-50 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 dark:bg-zinc-800/90 dark:text-zinc-300">
                                            {{ $accion['etiqueta'] }}
                                        </th>
                                    @endforeach

                                    <th scope="col" class="sticky top-0 z-10 bg-slate-50 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-amber-600 dark:bg-zinc-800/90 dark:text-amber-400">
                                        TODOS
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                                @forelse ($this->permisosPorModulo as $modulo => $permisosDelModulo)
                                    <tr class="transition-colors duration-200 hover:bg-slate-50/70">
                                        <td class="px-4 py-3">
                                            <span class="flex items-center gap-3">
                                                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-amber-500/10">
                                                    <flux:icon :name="$this->iconoModulo($modulo)" class="size-4 text-amber-600" />
                                                </span>
                                                <span class="font-semibold text-slate-900 dark:text-zinc-200">
                                                    {{ $this->etiquetaModulo($modulo) }}
                                                </span>
                                            </span>
                                        </td>

                                        @foreach ($accionesVisibles as $accion)
                                            @php
                                                $permiso = $permisosDelModulo->first(
                                                    fn ($p) => Str::after($p->name, '.') === $accion['clave']
                                                );
                                            @endphp

                                            <td class="px-3 py-3 text-center">
                                                @if ($permiso)
                                                    <label class="inline-flex cursor-pointer items-center" title="{{ $accion['etiqueta'] }} en {{ $this->etiquetaModulo($modulo) }}">
                                                        <input
                                                            type="checkbox"
                                                            wire:model.live="permisosUsuario"
                                                            value="{{ $permiso->name }}"
                                                            class="peer sr-only"
                                                        />
                                                        <span class="flex size-5 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent shadow-sm transition-all duration-200 ease-out hover:border-amber-400 active:scale-90 peer-checked:border-amber-500 peer-checked:bg-amber-500 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500 peer-focus-visible:ring-offset-1 dark:border-zinc-600 dark:bg-zinc-800 dark:peer-checked:border-amber-500 dark:peer-checked:bg-amber-500">
                                                            <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M16.704 4.81a.75.75 0 0 1 .04 1.06l-8.5 9a.75.75 0 0 1-1.075-.034l-4.5-5a.75.75 0 1 1 1.08-1.04l3.96 4.4 7.96-8.42a.75.75 0 0 1 1.06-.046Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </label>
                                                @else
                                                    <span class="inline-block text-slate-300 dark:text-zinc-600" title="Este módulo no posee la acción {{ strtolower($accion['etiqueta']) }}">—</span>
                                                @endif
                                            </td>
                                        @endforeach

                                        <td class="px-3 py-3 text-center">
                                            <label class="inline-flex cursor-pointer items-center" title="Seleccionar o deseleccionar todas las acciones de {{ $this->etiquetaModulo($modulo) }}">
                                                <input
                                                    type="checkbox"
                                                    wire:click="alternarTodosDelModulo('{{ $modulo }}')"
                                                    @checked($this->todosSeleccionadosDelModulo($modulo))
                                                    class="peer sr-only"
                                                />
                                                <span class="flex size-5 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent shadow-sm transition-all duration-200 ease-out hover:border-amber-500 active:scale-90 peer-checked:border-amber-600 peer-checked:bg-amber-600 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-amber-600 peer-focus-visible:ring-offset-1 dark:border-zinc-600 dark:bg-zinc-800 dark:peer-checked:border-amber-600 dark:peer-checked:bg-amber-600">
                                                    <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M16.704 4.81a.75.75 0 0 1 .04 1.06l-8.5 9a.75.75 0 0 1-1.075-.034l-4.5-5a.75.75 0 1 1 1.08-1.04l3.96 4.4 7.96-8.42a.75.75 0 0 1 1.06-.046Z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </label>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $accionesVisibles->count() + 2 }}" class="px-4 py-10 text-center">
                                            <p class="text-sm text-slate-500">No hay permisos registrados en el sistema.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-zinc-800">
                        <p class="text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">{{ count($permisosUsuario) }}</span>
                            permiso(s) directo(s) seleccionados.
                        </p>

                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="abierto = false"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-slate-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-out hover:scale-[1.02] hover:bg-amber-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 active:scale-95"
                            >
                                <span wire:loading.remove wire:target="guardarPermisosGranulares">
                                    <flux:icon.check class="size-4" />
                                </span>
                                <span wire:loading wire:target="guardarPermisosGranulares">Guardando...</span>
                                <span wire:loading.remove wire:target="guardarPermisosGranulares">Guardar permisos</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>