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
                Gestiona el personal del hotel, vincúlalo a usuarios y asígnales roles.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nuevo empleado
        </flux:button>
    </div>

    <flux:card>
        <div class="mb-6 grid gap-4 sm:grid-cols-2">
            <flux:field>
                <flux:label>Buscar</flux:label>
                <flux:input wire:model.live="busqueda" placeholder="Nombre, apellidos o puesto…" />
            </flux:field>

            <flux:field>
                <flux:label>Estado</flux:label>
                <select wire:model.live="filtroEstado" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                    <option value="todos">Todos</option>
                    <option value="activos">Activos</option>
                    <option value="inactivos">Inactivos</option>
                </select>
            </flux:field>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nombre</flux:table.column>
                <flux:table.column>Puesto</flux:table.column>
                <flux:table.column>Usuario vinculado</flux:table.column>
                <flux:table.column>Rol</flux:table.column>
                <flux:table.column align="center">Estado</flux:table.column>
                <flux:table.column align="end">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($empleados as $empleado)
                    <flux:table.row :key="$empleado->id_empleado">
                        <flux:table.cell variant="strong">{{ $empleado->nombre }} {{ $empleado->apellidos }}</flux:table.cell>
                        <flux:table.cell>{{ $empleado->puesto }}</flux:table.cell>
                        <flux:table.cell>{{ $empleado->usuario?->name ?? '—' }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($empleado->usuario?->roles->first())
                                <flux:badge color="blue" size="sm">{{ $empleado->usuario->roles->first()->name }}</flux:badge>
                            @else
                                <span class="text-zinc-400">—</span>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <x-estado-badge :estado="$empleado->esta_activo ? 'Activo' : 'Inactivo'" />
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button type="button" size="sm" variant="outline" wire:click="toggleActivo({{ $empleado->id_empleado }})">
                                    {{ $empleado->esta_activo ? 'Desactivar' : 'Activar' }}
                                </flux:button>
                                <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $empleado->id_empleado }})">
                                    <flux:icon.pencil-square class="size-4" />
                                    Editar
                                </flux:button>
                                <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $empleado->id_empleado }})" wire:confirm="¿Eliminar este empleado?">
                                    <flux:icon.trash class="size-4" />
                                    Eliminar
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" align="center">
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
                <flux:heading size="lg">{{ $empleadoId ? 'Editar empleado' : 'Nuevo empleado' }}</flux:heading>
                <flux:subheading>Completa los datos del empleado y su acceso al sistema.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="nombre" required />
                    <flux:error name="nombre" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellidos</flux:label>
                    <flux:input wire:model="apellidos" required />
                    <flux:error name="apellidos" />
                </flux:field>

                <flux:field>
                    <flux:label>Puesto</flux:label>
                    <flux:input wire:model="puesto" placeholder="Ejemplo: Recepcionista" required />
                    <flux:error name="puesto" />
                </flux:field>

                <flux:field>
                    <flux:label>Teléfono</flux:label>
                    <flux:input wire:model="telefono" />
                    <flux:error name="telefono" />
                </flux:field>

                <flux:field>
                    <flux:label>Salario</flux:label>
                    <flux:input type="number" step="0.01" min="0" wire:model="salario" />
                    <flux:error name="salario" />
                </flux:field>

                <flux:field>
                    <flux:label>Horario</flux:label>
                    <flux:input wire:model="horario" placeholder="Ejemplo: 8:00 - 16:00" />
                    <flux:error name="horario" />
                </flux:field>

                <flux:field>
                    <flux:label>Usuario del sistema</flux:label>
                    <select wire:model="id_usuario" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Sin usuario vinculado…</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                        @endforeach
                    </select>
                    <flux:error name="id_usuario" />
                </flux:field>

                <flux:field>
                    <flux:label>Rol de Spatie</flux:label>
                    <select wire:model="rol" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Sin rol asignado…</option>
                        @foreach ($roles as $rolDisponible)
                            <option value="{{ $rolDisponible->name }}">{{ $rolDisponible->name }}</option>
                        @endforeach
                    </select>
                    <flux:error name="rol" />
                </flux:field>

                <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-700 sm:col-span-2 dark:text-zinc-300">
                    <input type="checkbox" wire:model="esta_activo" class="size-4 rounded border-zinc-300 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                    Empleado activo
                </label>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="cerrarModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">
                    <flux:icon.check class="size-4" />
                    Guardar
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
