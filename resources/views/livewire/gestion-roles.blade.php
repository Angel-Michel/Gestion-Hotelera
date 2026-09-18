@use('Illuminate\Support\Str')

@php
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
            <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl">Gestión de Roles</flux:heading>
            <flux:subheading class="!text-slate-600 !font-medium">
                <span class="inline-flex items-center gap-2">
                    <span class="relative flex size-2" aria-hidden="true">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Crea roles, edita sus nombres y administra los niveles de acceso.
                </span>
            </flux:subheading>
        </div>

        @if ($this->esSuperAdmin())
            <flux:button type="button" variant="primary" wire:click="abrirModalCrear" class="shrink-0">
                <flux:icon.plus class="size-4" />
                Crear Nuevo Rol
            </flux:button>
        @endif
    </div>

    <flux:card>
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">Roles registrados</flux:heading>
                <flux:text size="sm">
                    {{ $totalRoles }} rol(es) configurados en el sistema.
                </flux:text>
            </div>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Rol</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Permisos asignados</flux:table.column>
                <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Usuarios con este rol</flux:table.column>
                <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->roles as $rol)
                    <flux:table.row :key="$rol->id">
                        <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">
                            <span class="inline-flex items-center gap-2">
                                <flux:icon.shield-check class="size-4 text-amber-600" />
                                {{ Str::headline($rol->name) }}
                            </span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold">
                                {{ $rol->permissions_count }} permiso(s)
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell align="center">
                            <flux:badge color="emerald" size="sm" class="!bg-emerald-100 !text-emerald-800 !font-semibold">
                                {{ $rol->users_count }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                @if (! $this->esSuperAdmin())
                                    <span class="inline-flex items-center text-zinc-400" title="Solo el Super Admin puede editar o eliminar roles">
                                        <flux:icon.lock-closed class="size-4" />
                                    </span>
                                @elseif ($this->esRolProtegido($rol->name))
                                    <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-500" title="Rol del sistema: no se puede modificar">
                                        <flux:icon.lock-closed class="size-4" />
                                        Protegido
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

    <flux:modal name="crear-rol" wire:model="mostrarModalCrear" class="w-full max-w-2xl">
        <form wire:submit="guardarRol">
            <div class="mb-6">
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">Crear Nuevo Rol</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium">
                    Registra un nivel de acceso nuevo y selecciona sus permisos iniciales.
                </flux:subheading>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Nombre del Rol</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ejemplo: mantenimiento, auditor..." required />
                    <flux:error name="nombre" />
                </flux:field>

                <div>
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <flux:heading size="lg" class="!text-slate-800 !font-semibold">Permisos iniciales</flux:heading>

                        <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                            <input type="checkbox" wire:model.live="seleccionarTodos" class="size-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                            Seleccionar todos
                        </label>
                    </div>

                    <flux:text size="sm" class="mb-4">
                        Marca los permisos que el rol podrá ejercer al momento de crearse.
                    </flux:text>

                    <div class="max-h-72 space-y-4 overflow-y-auto rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                        @foreach ($this->permisosPorModulo as $modulo => $permisosDelModulo)
                            <fieldset>
                                <legend class="mb-2 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    {{ $this->etiquetaModulo($modulo) }}
                                </legend>

                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach ($permisosDelModulo as $permiso)
                                        <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                            <input
                                                type="checkbox"
                                                wire:model.live="permisosSeleccionados"
                                                value="{{ $permiso->id }}"
                                                class="size-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800"
                                            />
                                            {{ $this->etiquetaAccion(Str::after($permiso->name, '.')) }}
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
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

    <flux:modal name="editar-rol" wire:model="mostrarModalEditar" class="w-full max-w-2xl">
        <form wire:submit="actualizarRol">
            <div class="mb-6">
                <flux:heading size="lg" class="!text-slate-800 !font-semibold">Editar rol</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium">
                    Renombra el rol y ajusta los permisos que tendrá asignados.
                </flux:subheading>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Nombre del Rol</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ejemplo: contabilidad" required />
                    <flux:error name="nombre" />
                </flux:field>

                <div>
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <flux:heading size="lg" class="!text-slate-800 !font-semibold">Permisos del rol</flux:heading>

                        <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                            <input type="checkbox" wire:model.live="seleccionarTodos" class="size-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800" />
                            Seleccionar todos
                        </label>
                    </div>

                    <flux:text size="sm" class="mb-4">
                        Marca los permisos que el rol podrá ejercer.
                    </flux:text>

                    <div class="max-h-72 space-y-4 overflow-y-auto rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                        @foreach ($this->permisosPorModulo as $modulo => $permisosDelModulo)
                            <fieldset>
                                <legend class="mb-2 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    {{ $this->etiquetaModulo($modulo) }}
                                </legend>

                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach ($permisosDelModulo as $permiso)
                                        <label class="flex cursor-pointer items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                            <input
                                                type="checkbox"
                                                wire:model.live="permisosSeleccionados"
                                                value="{{ $permiso->id }}"
                                                class="size-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0 dark:border-zinc-600 dark:bg-zinc-800"
                                            />
                                            {{ $this->etiquetaAccion(Str::after($permiso->name, '.')) }}
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                    </div>
                </div>
            </div>

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
            <flux:subheading class="!text-slate-600 !font-medium">
                Esta acción no se puede deshacer.
            </flux:subheading>
        </div>

        <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
            ¿Estás seguro de que deseas eliminar el rol
            <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $nombreRolAEliminar }}</span>?
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