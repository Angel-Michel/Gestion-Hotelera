```blade
<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Usuarios
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Administra los usuarios, roles y accesos del sistema.
                </p>
            </div>

            {{-- Botón Nuevo usuario --}}
            @can('usuarios.crear')
                <a
                    href="{{ route('usuarios.create') }}"
                    style="
                        display: inline-block;
                        padding: 10px 20px;
                        background: black;
                        color: white;
                        border-radius: 8px;
                        font-weight: bold;
                        text-decoration: none;
                    "
                >
                    NUEVO USUARIO
                </a>
            @endcan
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div
                style="
                    padding: 12px 16px;
                    background: #dcfce7;
                    color: #166534;
                    border: 1px solid #bbf7d0;
                    border-radius: 8px;
                    font-size: 14px;
                "
            >
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabla de usuarios --}}
        <div
            class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-900"
        >

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    {{-- Encabezados --}}
                    <thead
                        class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800"
                    >
                        <tr>

                            <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">
                                Usuario
                            </th>

                            <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">
                                Rol
                            </th>

                            <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">
                                Código de empleado
                            </th>

                            <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">
                                Estado
                            </th>

                            <th class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300">
                                Último acceso
                            </th>

                            <th class="px-6 py-4 text-right font-medium text-neutral-600 dark:text-neutral-300">
                                Acciones
                            </th>

                        </tr>
                    </thead>

                    {{-- Usuarios --}}
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse ($usuarios as $usuario)

                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">

                                {{-- Usuario --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        {{-- Iniciales --}}
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800"
                                        >
                                            <span class="text-sm font-medium text-neutral-600 dark:text-neutral-300">
                                                {{ $usuario->initials() }}
                                            </span>
                                        </div>

                                        {{-- Nombre y correo --}}
                                        <div>

                                            <p class="font-medium text-neutral-900 dark:text-white">
                                                {{ $usuario->name }}
                                            </p>

                                            <p class="text-neutral-500 dark:text-neutral-400">
                                                {{ $usuario->email }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                {{-- Rol --}}
                                <td class="px-6 py-4">

                                    @forelse ($usuario->roles as $rol)

                                        <span
                                            class="inline-flex rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                        >
                                            {{ $rol->name }}
                                        </span>

                                    @empty

                                        <span class="text-neutral-400">
                                            Sin rol
                                        </span>

                                    @endforelse

                                </td>

                                {{-- Código de empleado --}}
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-300">

                                    {{ $usuario->codigo_empleado ?? '—' }}

                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4">

                                    @if ($usuario->activo)

                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400"
                                        >
                                            Activo
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400"
                                        >
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

                                        {{-- Editar --}}
                                        @can('usuarios.editar')

                                            <a
                                                href="{{ route('usuarios.edit', $usuario) }}"
                                                title="Editar usuario"
                                                aria-label="Editar usuario de {{ $usuario->name }}"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-500 shadow-sm transition-all duration-200 hover:scale-105 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600 active:scale-95 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:border-blue-500/60 dark:hover:bg-blue-500/10 dark:hover:text-blue-400"
                                            >
                                                <flux:icon.pencil-square class="size-4" />
                                            </a>

                                        @endcan

                                        {{-- Roles y permisos --}}
                                        @can('roles_permisos.editar')

                                            <button
                                                type="button"
                                                title="Configurar roles y permisos"
                                                aria-label="Configurar roles y permisos de {{ $usuario->name }}"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-500 shadow-sm transition-all duration-200 hover:scale-105 hover:border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 active:scale-95 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:border-neutral-500 dark:hover:bg-neutral-700 dark:hover:text-neutral-200"
                                            >
                                                <flux:icon.cog-6-tooth class="size-4" />
                                            </button>

                                        @endcan

                                        {{-- Eliminar --}}
                                        @can('usuarios.eliminar')

                                            <button
                                                type="button"
                                                title="Eliminar usuario"
                                                aria-label="Eliminar usuario de {{ $usuario->name }}"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 bg-white text-red-500 shadow-sm transition-all duration-200 hover:scale-105 hover:border-red-300 hover:bg-red-50 hover:text-red-600 active:scale-95 dark:border-red-500/40 dark:bg-neutral-800 dark:text-red-400 dark:hover:border-red-500/60 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                            >
                                                <flux:icon.trash class="size-4" />
                                            </button>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400"
                                >
                                    No hay usuarios registrados.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</x-layouts.app>
```
