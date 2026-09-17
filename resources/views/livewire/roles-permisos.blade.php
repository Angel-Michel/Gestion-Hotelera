@use('Illuminate\Support\Str')

@php
    $totalRoles = $this->roles->count();
@endphp

<div>
    <!-- Alertas -->
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

    <!-- Cabecera -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Gestión de Roles</flux:heading>
            <flux:subheading>
                Crea roles, edita sus nombres y administra los niveles de acceso.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="abrirModalCrear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nuevo Rol
        </flux:button>
    </div>

    <!-- Lista de Roles -->
    <flux:card class="mb-6">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="lg">Roles registrados</flux:heading>
                <flux:text size="sm">
                    {{ $totalRoles }} rol(es) configurados en el sistema.
                </flux:text>
            </div>
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
                                {{ Str::headline($rol->name) }}
                            </span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <flux:badge color="zinc" size="sm">
                                    {{ $rol->permissions_count }} permiso(s)
                                </flux:badge>
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

    <!-- Modal Crear Rol (Simplificado) -->
    <flux:modal name="crear-rol" wire:model="mostrarModalCrear" class="w-full max-w-md">
        <form wire:submit="guardarRol">
            <div class="mb-6">
                <flux:heading size="lg">Crear Nuevo Rol</flux:heading>
                <flux:subheading>
                    Registra un nivel de acceso nuevo en el sistema.
                </flux:subheading>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Nombre del Rol</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ejemplo: mantenimiento, auditor..." required />
                    <flux:error name="nombre" />
                </flux:field>
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

    <!-- Modal Editar Rol -->
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

    <!-- Modal Eliminar Rol -->
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