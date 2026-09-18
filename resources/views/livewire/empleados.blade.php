<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Empleados</flux:heading>
            <flux:subheading>
                <span class="inline-flex items-center gap-2">
                    <span class="relative flex size-2" aria-hidden="true">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Gestiona el personal del hotel, sus datos y su acceso al sistema.
                </span>
            </flux:subheading>
        </div>

        @if ($this->esSuperAdmin())
            <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
                <flux:icon.plus class="size-4" />
                Crear Nuevo Empleado
            </flux:button>
        @endif
    </div>

    <flux:card>
        <div class="mb-6 grid gap-4 sm:grid-cols-2">
            <flux:field>
                <flux:label>Buscar</flux:label>
                <flux:input wire:model.live="busqueda" placeholder="Nombre, puesto o correo electrónico…" />
            </flux:field>

            <flux:field>
                <flux:label>Estado</flux:label>
                <select wire:model.live="filtroEstado" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                    <option value="todos">Todos</option>
                    <option value="activos">Activos</option>
                    <option value="inactivos">Inactivos</option>
                </select>
            </flux:field>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nombre</flux:table.column>
                <flux:table.column>Apellidos</flux:table.column>
                <flux:table.column>Teléfono</flux:table.column>
                <flux:table.column>Puesto</flux:table.column>
                <flux:table.column>Salario</flux:table.column>
                <flux:table.column>Turno</flux:table.column>
                <flux:table.column>Correo electrónico</flux:table.column>
                <flux:table.column align="center">Estado</flux:table.column>
                @if ($this->esSuperAdmin())
                    <flux:table.column align="end">Acciones</flux:table.column>
                @endif
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($empleados as $empleado)
                    <flux:table.row :key="$empleado->id_empleado">
                        <flux:table.cell variant="strong">{{ $empleado->nombre }}</flux:table.cell>
                        <flux:table.cell>{{ $empleado->apellidos }}</flux:table.cell>
                        <flux:table.cell>{{ $empleado->telefono ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $empleado->puesto ?? '—' }}</flux:table.cell>
                        <flux:table.cell>
                            {{ $empleado->salario !== null ? '$ '.number_format((float) $empleado->salario, 2) : '—' }}
                        </flux:table.cell>
                        <flux:table.cell>{{ $empleado->turno ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $empleado->correo_electronico ?? $empleado->usuario?->email ?? '—' }}</flux:table.cell>
                        <flux:table.cell align="center">
                            <x-estado-badge :estado="$empleado->esta_activo ? 'Activo' : 'Inactivo'" />
                        </flux:table.cell>

                        @if ($this->esSuperAdmin())
                            <flux:table.cell align="end">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $empleado->id_empleado }})">
                                        <flux:icon.pencil-square class="size-4" />
                                        Editar
                                    </flux:button>

                                    <flux:button type="button" size="sm" variant="outline" wire:click="toggleActivo({{ $empleado->id_empleado }})">
                                        {{ $empleado->esta_activo ? 'Desactivar' : 'Activar' }}
                                    </flux:button>

                                    <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $empleado->id_empleado }})" wire:confirm="¿Eliminar este empleado?">
                                        <flux:icon.trash class="size-4" />
                                        Eliminar
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        @endif
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell :colspan="$this->esSuperAdmin() ? 9 : 8" align="center">
                            <p class="py-8">No hay empleados que coincidan con la búsqueda.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $empleados->links() }}
        </div>
    </flux:card>

    <flux:modal name="empleado-form" wire:model="mostrarModal" class="w-full max-w-2xl">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg">{{ $empleadoId ? 'Editar Empleado' : 'Crear Nuevo Empleado' }}</flux:heading>
                <flux:subheading>
                    {{ $empleadoId
                        ? 'Actualiza los datos personales del empleado y su acceso al sistema.'
                        : 'Registra al empleado y define si podrá acceder al sistema.' }}
                </flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ejemplo: Juan" required autocomplete="off" />
                    <flux:error name="nombre" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellidos</flux:label>
                    <flux:input wire:model="apellidos" placeholder="Ejemplo: Pérez López" required autocomplete="off" />
                    <flux:error name="apellidos" />
                </flux:field>

                <flux:field>
                    <flux:label>Teléfono</flux:label>
                    <flux:input type="tel" wire:model="telefono" placeholder="Ejemplo: 5512345678" />
                    <flux:error name="telefono" />
                </flux:field>

                <flux:field>
                    <flux:label>Puesto</flux:label>
                    <flux:input wire:model="puesto" placeholder="Ejemplo: Recepcionista" />
                    <flux:error name="puesto" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Correo Electrónico</flux:label>
                    <flux:input type="email" wire:model="correo_electronico" placeholder="empleado@hotel.com" required />
                    <flux:error name="correo_electronico" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Rol del Sistema</flux:label>
                    <select wire:model="rol" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Sin rol asignado…</option>
                        @foreach ($roles as $rolDisponible)
                            <option value="{{ $rolDisponible->name }}">{{ $rolDisponible->name }}</option>
                        @endforeach
                    </select>
                    <flux:description>El rol no incluye a Super Admin.</flux:description>
                    <flux:error name="rol" />
                </flux:field>

                <flux:field>
                    <flux:label>Salario</flux:label>
                    <flux:input type="number" step="0.01" min="0" wire:model="salario" placeholder="0.00" />
                    <flux:error name="salario" />
                </flux:field>

                <flux:field>
                    <flux:label>Turno</flux:label>
                    <select wire:model="turno" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Selecciona un turno…</option>
                        @foreach ($turnos as $turnoDisponible)
                            <option value="{{ $turnoDisponible }}">{{ $turnoDisponible }}</option>
                        @endforeach
                    </select>
                    <flux:error name="turno" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Estado</flux:label>
                    <select wire:model="esta_activo" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                    <flux:error name="esta_activo" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:switch
                        wire:model="acceso_sistema"
                        label="Acceso al Sistema"
                        description="{{ $acceso_sistema ? 'El empleado podrá iniciar sesión en la plataforma.' : 'El empleado no podrá iniciar sesión en la plataforma.' }}"
                    />
                    <flux:error name="acceso_sistema" />
                </flux:field>

                @if ($acceso_sistema)
                    <flux:field class="sm:col-span-2">
                        <flux:label>Contraseña {{ $empleadoId ? '(opcional)' : 'inicial' }}</flux:label>
                        <flux:input type="password" wire:model="contrasena" placeholder="Mínimo 8 caracteres" autocomplete="new-password" />
                        @if ($empleadoId)
                            <flux:description>Déjala vacía para conservar la contraseña actual.</flux:description>
                        @endif
                        <flux:error name="contrasena" />
                    </flux:field>
                @endif
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="cerrarModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">
                    <flux:icon.check class="size-4" />
                    {{ $empleadoId ? 'Guardar cambios' : 'Crear empleado' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>