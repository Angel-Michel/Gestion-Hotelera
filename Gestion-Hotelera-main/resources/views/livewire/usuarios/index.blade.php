<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    {{-- Encabezado --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                Gestión de usuarios
            </h1>

            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                Activa o desactiva el acceso de los usuarios al sistema.
            </p>
        </div>

        <div class="w-full sm:w-80">
            <input
                type="search"
                wire:model.live="busqueda"
                placeholder="Buscar por nombre o correo electrónico"
                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/40 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500"
            />
        </div>
    </div>

    {{-- Listado de usuarios --}}
    <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Encabezados --}}
                <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">Usuario</th>
                        <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">Rol</th>
                        <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">Código de empleado</th>
                        <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">Estado</th>
                        <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">Último acceso</th>
                        <th class="px-6 py-4 text-right font-medium text-neutral-600 dark:text-neutral-300">Acciones</th>
                    </tr>
                </thead>

                {{-- Usuarios --}}
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($this->usuarios as $usuario)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                            {{-- Usuario --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
                                        <span class="text-sm font-medium text-neutral-600 dark:text-neutral-300">
                                            {{ $usuario->initials() }}
                                        </span>
                                    </div>

                                    <div>
                                        <p class="font-medium text-neutral-900 dark:text-white">{{ $usuario->name }}</p>
                                        <p class="text-neutral-500 dark:text-neutral-400">{{ $usuario->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Rol --}}
                            <td class="px-6 py-4">
                                @forelse ($usuario->roles as $rol)
                                    <span class="inline-flex rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                        {{ $rol->name }}
                                    </span>
                                @empty
                                    <span class="text-neutral-400">Sin rol</span>
                                @endforelse
                            </td>

                            {{-- Código de empleado --}}
                            <td class="px-6 py-4 text-neutral-600 dark:text-neutral-300">
                                {{ $usuario->codigo_empleado ?? '—' }}
                            </td>

                            {{-- Estado --}}
                            <td class="px-6 py-4">
                                @if ($usuario->activo)
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- Último acceso --}}
                            <td class="px-6 py-4 text-neutral-600 dark:text-neutral-300">
                                {{ $usuario->ultimo_acceso?->format('d/m/Y H:i') ?? 'Nunca' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="toggleActivo({{ $usuario->id }})"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400/50 disabled:cursor-not-allowed disabled:opacity-50 {{ $usuario->activo ? 'border border-neutral-300 bg-white text-neutral-700 hover:bg-red-50 hover:text-red-600 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-red-500/10 dark:hover:text-red-400' : 'border border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20' }}"
                                    >
                                        <span class="relative inline-flex h-4 w-7 shrink-0 items-center rounded-full transition-colors {{ $usuario->activo ? 'bg-amber-500' : 'bg-neutral-400 dark:bg-neutral-600' }}">
                                            <span class="inline-block h-3 w-3 transform rounded-full bg-white shadow transition-transform {{ $usuario->activo ? 'translate-x-3.5' : 'translate-x-0.5' }}"></span>
                                        </span>
                                        {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                                No hay usuarios que coincidan con la búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>