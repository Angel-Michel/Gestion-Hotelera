@use('Illuminate\Support\Str')

@php
    $totalPermisos = $this->permisosPorModulo->flatten()->count();
    $totalRoles = $this->roles->count();
@endphp

<div>
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

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Roles y Permisos</flux:heading>
            <flux:subheading>
                Crea roles, edita sus nombres y asigna permisos por módulo mediante la matriz.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="abrirModalCrear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nuevo Rol
        </flux:button>
    </div>

    <flux:card class="mb-6">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="lg">Roles registrados</flux:heading>
                <flux:text size="sm">
                    {{ $totalRoles }} rol(es) · {{ $totalPermisos }} permiso(s) en {{ $this->permisosPorModulo->count() }} módulo(s).
                </flux:text>
            </div>

            <flux:badge color="blue" size="sm">Cambios guardados al instante</flux:badge>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Rol</flux:table.column>
                <flux:table.column>Permisos asignados</flux:table.column>
                <flux:table.column align="center">Usuarios con este rol</flux:table.column>
                <flux:table.column align="end">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->roles as $rol)
                    <flux:table.row :key="$rol->id">
                        <flux:table.cell variant="strong">
                            <span class="inline-flex items-center gap-2">
                                <flux:icon.shield-check class="size-4 text-blue-500" />
                                {{ $rol->name }}
                            </span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <flux:badge color="zinc" size="sm">
                                    {{ $rol->permissions_count }} permiso(s)
                                </flux:badge>

                                @foreach ($rol->permissions->take(3) as $permiso)
                                    <flux:badge color="blue" size="sm">
                                        {{ Str::after($permiso->name, '.') }}
                                    </flux:badge>
                                @endforeach

                                @if ($rol->permissions->count() > 3)
                                    <span class="text-xs text-zinc-400">
                                        +{{ $rol->permissions->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        </flux:table.cell>

                        <flux:table.cell align="center">
                            <flux:badge color="emerald" size="sm">
                                {{ $rol->users_count }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                @if ($this->esRolProtegido($rol->name))
                                    <span class="inline-flex items-center text-zinc-400" title="Rol del sistema: no se puede modificar">
                                        <flux:icon.lock-closed class="size-4" />
                                    </span>
                                @else
                                    <flux:button type="button" size="sm" variant="outline" wire:click="abrirModalEditar({{ $rol->id }})">
                                        <flux:icon.pencil-square class="size-4" />
                                        Editar
                                    </flux:button>
                                    <flux:button type="button" size="sm" variant="danger" wire:click="seleccionarRolAEliminar({{ $rol->id }})">
                                        <flux:icon.trash class="size-4" />
                                        Eliminar
                                    </flux:button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" align="center">
                            <p class="py-8">No hay roles registrados.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:card>
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="lg">Matriz de permisos por módulo</flux:heading>
                <flux:text size="sm">
                    Marca o desmarca cada permiso por rol. Usa las casillas de las cabeceras para seleccionar todo un rol o módulo.
                </flux:text>
            </div>

            <flux:badge color="blue" size="sm">Cambios guardados al instante</flux:badge>
        </div>

        <div class="overflow-x-auto">
            <flux:table class="min-w-[900px]">
                <flux:table.columns>
                    <flux:table.column>Permiso</flux:table.column>

                    @foreach ($this->roles as $rol)
                        <flux:table.column align="center">
                            <div class="flex flex-col items-center gap-1">
                                <flux:badge color="blue" size="sm">{{ $rol->name }}</flux:badge>
                                <span class="text-xs font-normal text-zinc-400">
                                    {{ $rol->users_count }} usuario(s) · {{ $rol->permissions_count }} permiso(s)
                                </span>

                                @if ($this->esRolInmutable($rol->name))
                                    <span class="inline-flex items-center text-zinc-500" title="Rol del sistema: permisos inmutables">
                                        <flux:icon.lock-closed class="size-3.5" />
                                    </span>
                                @else
                                    <label class="inline-flex cursor-pointer items-center gap-1 text-xs text-zinc-500" title="Marcar o desmarcar todos los permisos de este rol">
                                        <input type="checkbox" wire:click="alternarTodosDeRol({{ $rol->id }})" @checked($this->rolTieneTodosLosPermisos($rol)) class="size-3.5 rounded border-zinc-300 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                                        <span class="font-semibold uppercase">Todos</span>
                                    </label>
                                @endif
                            </div>
                        </flux:table.column>
                    @endforeach
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->permisosPorModulo as $modulo => $permisos)
                        <flux:table.row>
                            <flux:table.cell variant="strong">
                                <span class="inline-flex items-center gap-2">
                                    <span class="size-1.5 rounded-full bg-blue-500"></span>
                                    {{ $this->etiquetaModulo($modulo) }}
                                </span>
                            </flux:table.cell>

                            @foreach ($this->roles as $rol)
                                <flux:table.cell align="center">
                                    @if ($this->esRolInmutable($rol->name))
                                        <span class="inline-flex items-center text-zinc-500">
                                            <flux:icon.lock-closed class="size-3.5" />
                                        </span>
                                    @else
                                        <input type="checkbox" wire:click="alternarModulo({{ $rol->id }}, '{{ $modulo }}')" @checked($this->rolTieneTodosDelModulo($rol, $modulo)) title="Marcar o desmarcar todo el módulo {{ $this->etiquetaModulo($modulo) }} para {{ $rol->name }}" class="size-4 rounded border-zinc-300 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                                    @endif
                                </flux:table.cell>
                            @endforeach
                        </flux:table.row>

                        @foreach ($permisos as $permiso)
                            <flux:table.row :key="$modulo . '-' . $permiso->id">
                                <flux:table.cell class="pl-8">
                                    {{ $this->etiquetaAccion(Str::after($permiso->name, '.')) }}
                                </flux:table.cell>

                                @foreach ($this->roles as $rol)
                                    <flux:table.cell align="center">
                                        @if ($this->esRolInmutable($rol->name))
                                            <span class="text-zinc-400">
                                                {{ $this->rolTienePermiso($rol, $permiso->id) ? '●' : '·' }}
                                            </span>
                                        @else
                                            <input type="checkbox" wire:click="actualizarPermisos({{ $rol->id }}, {{ $permiso->id }})" @checked($this->rolTienePermiso($rol, $permiso->id)) title="{{ $this->etiquetaModulo($modulo) }} · {{ $this->etiquetaAccion(Str::after($permiso->name, '.')) }} · {{ $rol->name }}" class="size-4 rounded border-zinc-300 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                                        @endif
                                    </flux:table.cell>
                                @endforeach
                            </flux:table.row>
                        @endforeach
                    @empty
                        <flux:table.row>
                            <flux:table.cell align="center" colspan="{{ $this->roles->count() + 1 }}">
                                <p class="py-8">No hay permisos registrados. Ejecuta el seeder de roles y permisos.</p>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4 flex flex-col gap-2 border-t border-zinc-800/10 pt-4 sm:flex-row sm:items-center sm:justify-between dark:border-white/20">
            <p class="text-xs text-zinc-500">
                {{ $totalRoles }} rol(es) · {{ $totalPermisos }} permiso(s) en {{ $this->permisosPorModulo->count() }} módulo(s).
            </p>
            <p class="text-xs text-zinc-500">
                Los cambios en la matriz se guardan automáticamente.
            </p>
        </div>
    </flux:card>

    <flux:modal name="crear-rol" wire:model="mostrarModalCrear" class="w-full max-w-2xl">
        <form wire:submit="guardarRol">
            <div class="mb-6">
                <flux:heading size="lg">Crear Nuevo Rol</flux:heading>
                <flux:subheading>
                    Registra un rol nuevo y selecciona los permisos que tendrá.
                </flux:subheading>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Nombre del Rol</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ejemplo: contabilidad" required />
                    <flux:error name="nombre" />
                </flux:field>

                <div>
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-sm font-medium text-zinc-800 dark:text-white">
                            Permisos del rol
                        </p>

                        <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                            <input type="checkbox" wire:model="seleccionarTodos" class="size-4 rounded border-zinc-300 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                            Seleccionar todos
                        </label>
                    </div>

                    <div class="grid max-h-80 gap-5 overflow-y-auto pr-1 sm:grid-cols-2">
                        @foreach ($this->permisosPorModulo as $modulo => $permisos)
                            <div>
                                <p class="mb-2 border-b border-zinc-800/10 pb-1 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:border-white/20 dark:text-blue-400">
                                    {{ $this->etiquetaModulo($modulo) }}
                                </p>

                                <div class="space-y-1.5">
                                    @foreach ($permisos as $permiso)
                                        <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                            <input type="checkbox" value="{{ $permiso->id }}" wire:model="permisosSeleccionados" class="size-4 rounded border-zinc-300 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                                            {{ $permiso->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="cerrarModalCrear">
                    Cancelar
                </flux:button>

                <flux:button type="submit" variant="primary">
                    <flux:icon.check class="size-4" />
                    Guardar nuevo rol
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="editar-rol" wire:model="mostrarModalEditar" class="w-full max-w-md">
        <form wire:submit="actualizarRol">
            <div class="mb-6">
                <flux:heading size="lg">Editar rol</flux:heading>
                <flux:subheading>
                    Cambia el nombre del rol seleccionado.
                </flux:subheading>
            </div>

            <flux:field>
                <flux:label>Nombre del Rol</flux:label>
                <flux:input wire:model="nombre" placeholder="Ejemplo: contabilidad" required />
                <flux:error name="nombre" />
            </flux:field>

            <div class="mt-6 flex items-center justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="cerrarModalEditar">
                    Cancelar
                </flux:button>

                <flux:button type="submit" variant="primary">
                    <flux:icon.check class="size-4" />
                    Guardar cambios
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="eliminar-rol" wire:model="mostrarModalEliminar" class="w-full max-w-md">
        <div class="mb-6">
            <flux:heading size="lg" class="!text-red-600 dark:!text-red-400">
                Eliminar rol
            </flux:heading>
            <flux:subheading>
                Esta acción no se puede deshacer.
            </flux:subheading>
        </div>

        <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
            ¿Estás seguro de que deseas eliminar el rol
            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $nombreRolAEliminar }}</span>?
            Se retirará el acceso a los usuarios asignados a este rol.
        </p>

        <div class="mt-6 flex items-center justify-end gap-3">
            <flux:button type="button" variant="ghost" wire:click="cerrarModalEliminar">
                Cancelar
            </flux:button>

            <flux:button type="button" variant="danger" wire:click="eliminarRol">
                <flux:icon.trash class="size-4" />
                Sí, eliminar
            </flux:button>
        </div>
    </flux:modal>
</div>
