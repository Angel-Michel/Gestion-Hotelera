@use('Illuminate\Support\Str')

@php
    $totalRoles = $this->roles->count();
@endphp

<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4 animate-fade-in">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    @if ($mensajeError)
        <flux:callout variant="danger" icon="x-circle" class="mb-4 animate-fade-in">
            <p>{{ $mensajeError }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex animate-fade-in flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500/15 shadow-sm shadow-amber-500/20">
                <flux:icon.shield-check class="size-6 text-amber-600 dark:text-amber-400" />
            </span>

            <div>
                <flux:heading size="xl" class="!text-slate-900 !font-bold text-2xl dark:!text-white">Catálogo de Puestos</flux:heading>
                <flux:subheading class="!text-slate-600 !font-medium dark:!text-slate-300">
                    <span class="inline-flex items-center gap-2">
                        <span class="relative flex size-2" aria-hidden="true">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                        </span>
                        Crea y renombra los puestos del equipo; los niveles de acceso se ajustan en la Matriz de Permisos.
                    </span>
                </flux:subheading>
            </div>
        </div>

        @if ($this->esSuperAdmin())
            <flux:button type="button" variant="primary" wire:click="abrirModalCrear" class="group shrink-0 transition-all duration-200 ease-out hover:scale-[1.02] active:scale-95">
                <span class="flex size-5 items-center justify-center rounded-full bg-white/20 transition-transform duration-200 ease-out group-hover:rotate-90">
                    <flux:icon.plus class="size-3.5" />
                </span>
                Nuevo puesto
            </flux:button>
        @endif
    </div>

    <flux:card class="animate-fade-in-up !bg-white shadow-sm [animation-delay:120ms] dark:!bg-slate-800">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="lg" class="!text-slate-800 !font-semibold dark:!text-slate-100">Roles registrados</flux:heading>
                <flux:text size="sm">
                    {{ $totalRoles }} rol(es) configurados en el sistema.
                </flux:text>
            </div>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider dark:!text-slate-300">Rol</flux:table.column>
                <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider dark:!text-slate-300">Permisos asignados</flux:table.column>
                <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider dark:!text-slate-300">Usuarios con este rol</flux:table.column>
                @if ($this->esSuperAdmin())
                    <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider dark:!text-slate-300">Acciones</flux:table.column>
                @endif
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->roles as $rol)
                    <flux:table.row :key="$rol->id" class="transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <flux:table.cell variant="strong" class="!text-slate-900 !font-medium dark:!text-slate-100">
                            <span class="inline-flex items-center gap-2">
                                <flux:icon.shield-check class="size-4 text-amber-600 dark:text-amber-400" />
                                {{ Str::headline($rol->name) }}
                            </span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold dark:!bg-amber-500/20 dark:!text-amber-400">
                                {{ $rol->permissions_count }} permiso(s)
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell align="center">
                            <flux:badge color="emerald" size="sm" class="!bg-emerald-100 !text-emerald-800 !font-semibold dark:!bg-emerald-500/20 dark:!text-emerald-400">
                                {{ $rol->users_count }}
                            </flux:badge>
                        </flux:table.cell>

                        @if ($this->esSuperAdmin())
                            <flux:table.cell align="end">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button type="button" size="sm" variant="outline" wire:click="abrirModalEditar({{ $rol->id }})" class="transition-all duration-200 ease-out hover:scale-[1.02] active:scale-95">
                                        <flux:icon.pencil-square class="size-4 text-slate-500 transition-colors duration-200 dark:text-slate-400" />
                                        Editar
                                    </flux:button>

                                    @if ($this->esRolNoEliminable($rol->name, $rol->id))
                                        <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-500 dark:bg-slate-700/50 dark:text-slate-400" title="{{ $this->motivoRolNoEliminable($rol->name, $rol->id) }}">
                                            <flux:icon.lock-closed class="size-4" />
                                            Protegido
                                        </span>
                                    @else
                                        <flux:button type="button" size="sm" variant="danger" wire:click="seleccionarRolAEliminar({{ $rol->id }})" class="transition-all duration-200 ease-out hover:scale-[1.02] active:scale-95">
                                            <flux:icon.trash class="size-4" />
                                            Eliminar
                                        </flux:button>
                                    @endif
                                </div>
                            </flux:table.cell>
                        @endif
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" align="center">
                            <p class="py-8 text-slate-500 dark:text-slate-400">No hay roles registrados.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <div x-data="{ abierto: @entangle('mostrarModalCrear') }" @keydown.escape.window="abierto = false">
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
                class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 dark:bg-zinc-900 dark:ring-zinc-700"
                x-transition:enter="transition-all duration-300 ease-out"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition-all duration-200 ease-in"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
            >
                <form wire:submit="guardarRol">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/15">
                                <flux:icon.user-plus class="size-5 text-amber-600 dark:text-amber-400" />
                            </span>
                            <flux:heading size="lg" class="!text-slate-900 !font-bold dark:!text-white">Crear nuevo puesto</flux:heading>
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

                    <div class="space-y-2">
                        <label for="nombre-rol" class="block text-sm font-semibold text-slate-700 dark:text-zinc-200">
                            Nombre del puesto
                        </label>
                        <input
                            id="nombre-rol"
                            type="text"
                            wire:model="nombre"
                            placeholder="Ejemplo: recepción, mantenimiento..."
                            required
                            class="block w-full rounded-lg border-0 bg-white px-3.5 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                        />
                        @error('nombre')
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="abierto = false"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 ease-out hover:bg-slate-50 hover:shadow hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-out hover:bg-amber-600 hover:shadow-lg hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 active:scale-95"
                        >
                            <span wire:loading.remove wire:target="guardarRol">
                                <flux:icon.check class="size-4" />
                            </span>
                            <span wire:loading wire:target="guardarRol">Guardando...</span>
                            <span wire:loading.remove wire:target="guardarRol">Guardar puesto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ======================================================
         MODAL: EDITAR ROL (solo el nombre)
         ====================================================== --}}

    <div x-data="{ abierto: @entangle('mostrarModalEditar') }" @keydown.escape.window="abierto = false">
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
                class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 dark:bg-zinc-900 dark:ring-zinc-700"
                x-transition:enter="transition-all duration-300 ease-out"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition-all duration-200 ease-in"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
            >
                <form wire:submit="actualizarRol">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/15">
                                <flux:icon.pencil-square class="size-5 text-amber-600 dark:text-amber-400" />
                            </span>
                            <flux:heading size="lg" class="!text-slate-900 !font-bold dark:!text-white">Editar rol</flux:heading>
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

                    <div class="space-y-2">
                        <label for="nombre-rol-editar" class="block text-sm font-semibold text-slate-700 dark:text-zinc-200">
                            Nombre del Rol
                        </label>
                        <input
                            id="nombre-rol-editar"
                            type="text"
                            wire:model="nombre"
                            placeholder="Ejemplo: contabilidad"
                            required
                            class="block w-full rounded-lg border-0 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 transition-all duration-300 ease-out placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 dark:bg-zinc-900 dark:text-white dark:ring-zinc-700"
                        />
                        @error('nombre')
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                            <span wire:loading.remove wire:target="actualizarRol">
                                <flux:icon.check class="size-4" />
                            </span>
                            <span wire:loading wire:target="actualizarRol">Guardando...</span>
                            <span wire:loading.remove wire:target="actualizarRol">Guardar cambio</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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