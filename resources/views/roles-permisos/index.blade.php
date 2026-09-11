<x-layouts.app>

    <div class="p-6">

        {{-- Encabezado --}}
        <div class="mb-6">

            <h1 class="text-2xl font-semibold text-gray-800">
                Gestión de Personal
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Administra los permisos de acceso al sistema
            </p>

        </div>


        {{-- Mensaje de éxito --}}
        @if(session('success'))

            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- Formulario --}}
        <form
            action="{{ route('roles-permisos.update') }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- Tabla de Gestión de Personal --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1000px] text-sm">

                        {{-- Encabezado --}}
                        <thead class="border-b border-gray-200 bg-gray-50">

                            <tr>

                                <th class="px-5 py-4 text-left font-semibold text-gray-700">
                                    MÓDULO
                                </th>

                                @foreach($roles as $rol)

                                    <th class="px-5 py-4 text-center font-semibold text-gray-700">
                                        {{ $rol->name }}
                                    </th>

                                @endforeach

                                <th class="px-5 py-4 text-center font-semibold text-gray-700">
                                    TODOS
                                </th>

                            </tr>

                        </thead>


                        {{-- Módulos --}}
                        <tbody class="divide-y divide-gray-100">

                            @foreach($modulos as $nombreModulo => $claveModulo)

                                @php

                                    $permisoVer = $claveModulo . '.ver';

                                    $existeVer = \Spatie\Permission\Models\Permission::where(
                                        'name',
                                        $permisoVer
                                    )->exists();

                                @endphp


                                <tr class="align-middle">

                                    {{-- Nombre del módulo --}}
                                    <td class="px-5 py-4">

                                        <span class="font-medium text-gray-800">
                                            {{ strtoupper($nombreModulo) }}
                                        </span>

                                    </td>


                                    {{-- Permiso de cada rol --}}
                                    @foreach($roles as $rol)

                                        @php

                                            $tienePermiso = $existeVer
                                                ? $rol->hasPermissionTo($permisoVer)
                                                : false;

                                        @endphp


                                        <td class="px-5 py-4 text-center">

                                            @if($existeVer)

                                                <input
                                                    type="checkbox"
                                                    name="permisos[{{ $rol->name }}][{{ $claveModulo }}][ver]"
                                                    value="1"
                                                    {{ $tienePermiso ? 'checked' : '' }}
                                                    class="module-permission h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                    data-role="{{ $rol->name }}"
                                                    data-module="{{ $claveModulo }}"
                                                >

                                            @else

                                                <span class="text-xs text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                    @endforeach


                                    {{-- TODOS --}}
                                    <td class="px-5 py-4 text-center">

                                        @if($existeVer)

                                            <input
                                                type="checkbox"
                                                class="all-module-permission h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                data-module="{{ $claveModulo }}"
                                                title="Seleccionar este módulo para todos los roles"
                                            >

                                        @else

                                            <span class="text-xs text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Parte inferior --}}
                <div class="border-t border-gray-200 bg-gray-50 px-5 py-4">

                    <div class="flex items-center justify-between">

                        {{-- Nivel de seguridad --}}
                        <p class="text-sm text-gray-500">

                            Seguridad de acceso nivel:

                            <span class="font-medium text-gray-700">
                                Granular
                            </span>

                        </p>


                        {{-- Confirmar permisos --}}
                        <button
                            type="submit"
                            style="
                                display: inline-flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                width: auto !important;
                                min-width: 160px !important;
                                height: 42px !important;
                                padding: 10px 20px !important;
                                background-color: #2563eb !important;
                                color: #ffffff !important;
                                border: 1px solid #2563eb !important;
                                border-radius: 8px !important;
                                font-size: 14px !important;
                                font-weight: 500 !important;
                                line-height: 20px !important;
                                text-align: center !important;
                                opacity: 1 !important;
                                visibility: visible !important;
                                cursor: pointer !important;
                            "
                        >
                            <span
                                style="
                                    display: inline !important;
                                    color: #ffffff !important;
                                    visibility: visible !important;
                                    opacity: 1 !important;
                                    font-size: 14px !important;
                                    font-weight: 500 !important;
                                    line-height: 20px !important;
                                "
                            >
                                Confirmar permisos
                            </span>
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- JavaScript --}}
    <script>

        /*
        |--------------------------------------------------------------------------
        | Seleccionar / deseleccionar un módulo para todos los roles
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.all-module-permission').forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                const module = this.dataset.module;

                document.querySelectorAll(
                    '.module-permission[data-module="' + module + '"]'
                ).forEach(function (roleCheckbox) {

                    roleCheckbox.checked = checkbox.checked;

                });

            });

        });

    </script>

</x-layouts.app>