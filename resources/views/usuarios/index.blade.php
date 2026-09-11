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
                                                style="
                                                    display: inline-block;
                                                    padding: 7px 12px;
                                                    border: 1px solid #d1d5db;
                                                    border-radius: 8px;
                                                    color: #374151;
                                                    font-size: 12px;
                                                    font-weight: 600;
                                                    text-decoration: none;
                                                    background: white;
                                                "
                                            >
                                                Editar
                                            </a>

                                        @endcan

                                        {{-- Roles y permisos --}}
                                        @can('roles_permisos.editar')

                                            <button
                                                type="button"
                                                style="
                                                    display: inline-block;
                                                    padding: 7px 12px;
                                                    border: 1px solid #d1d5db;
                                                    border-radius: 8px;
                                                    color: #374151;
                                                    font-size: 12px;
                                                    font-weight: 600;
                                                    background: white;
                                                    cursor: pointer;
                                                "
                                            >
                                                Roles y permisos
                                            </button>

                                        @endcan

                                        {{-- Eliminar --}}
                                        @can('usuarios.eliminar')

                                            <button
                                                type="button"
                                                style="
                                                    display: inline-block;
                                                    padding: 7px 12px;
                                                    border: 1px solid #fecaca;
                                                    border-radius: 8px;
                                                    color: #dc2626;
                                                    font-size: 12px;
                                                    font-weight: 600;
                                                    background: white;
                                                    cursor: pointer;
                                                "
                                            >
                                                Eliminar
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
