
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-slate-50 text-slate-700 dark:bg-zinc-950 dark:text-zinc-200">

        {{-- ==========================================================
             MENÚ LATERAL
             ========================================================== --}}

        <flux:sidebar
            sticky
            stashable
            class="border-r border-slate-200 bg-white dark:border-zinc-800 dark:bg-zinc-900"
        >

            {{-- Botón para cerrar el sidebar en dispositivos pequeños --}}
            <flux:sidebar.toggle
                class="lg:hidden"
                icon="x-mark"
            />


            {{-- ======================================================
                 LOGOTIPO
                 ====================================================== --}}

            <a
                href="{{ route('dashboard') }}"
                class="mb-7 flex items-center gap-3 px-2"
                wire:navigate
            >

                <x-app-logo
                    class="size-8"
                    href="#"
                />

                <div class="flex flex-col leading-tight">

                    <span class="text-sm font-semibold tracking-wide text-slate-800 dark:text-white">
                        Gestión Hotelera
                    </span>

                    <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                        Administración
                    </span>

                </div>

            </a>


            {{-- ======================================================
                 MENÚ PRINCIPAL
                 ====================================================== --}}

            <flux:navlist variant="outline">


                {{-- ==================================================
                     PRINCIPAL
                     ================================================== --}}

                <flux:navlist.group
                    heading="Principal"
                    class="grid"
                >

                    {{-- Dashboard --}}
                    @can('dashboard.ver')

                        <flux:navlist.item
                            icon="home"
                            :href="route('dashboard')"
                            :current="request()->routeIs('dashboard')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Dashboard
                        </flux:navlist.item>

                    @endcan

                </flux:navlist.group>


                {{-- ==================================================
                     OPERACIÓN
                     ================================================== --}}

                <flux:navlist.group
                    heading="Operación"
                    class="mt-5 grid"
                >

                    {{-- Reservaciones --}}
                    @can('reservaciones.ver')

                        <flux:navlist.item
                            icon="calendar-days"
                            :href="route('reservaciones')"
                            :current="request()->routeIs('reservaciones')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Reservaciones
                        </flux:navlist.item>

                    @endcan


                    {{-- Habitaciones --}}
                    @can('habitaciones.ver')

                        <flux:navlist.item
                            icon="building-office-2"
                            :href="route('habitaciones')"
                            :current="request()->routeIs('habitaciones')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Habitaciones
                        </flux:navlist.item>

                    @endcan


                    {{-- Clientes --}}
                    @can('clientes.ver')

                        <flux:navlist.item
                            icon="users"
                            :href="route('clientes')"
                            :current="request()->routeIs('clientes')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Clientes
                        </flux:navlist.item>

                    @endcan


                    {{-- Check-in / Check-out --}}
                    @can('checkin_checkout.ver')

                        <flux:navlist.item
                            icon="arrow-right-start-on-rectangle"
                            :href="route('checkin-checkout')"
                            :current="request()->routeIs('checkin-checkout')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Check-in / Check-out
                        </flux:navlist.item>

                    @endcan


                    {{-- Limpieza --}}
                    @can('limpieza.ver')

                        <flux:navlist.item
                            icon="sparkles"
                            :href="route('limpieza')"
                            :current="request()->routeIs('limpieza')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Limpieza
                        </flux:navlist.item>

                    @endcan


                    {{-- Pagos --}}
                    @can('pagos.ver')

                        <flux:navlist.item
                            icon="credit-card"
                            :href="route('pagos')"
                            :current="request()->routeIs('pagos')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Pagos
                        </flux:navlist.item>

                    @endcan


                    {{-- Servicios --}}
                    @can('servicios.ver')

                        <flux:navlist.item
                            icon="wrench-screwdriver"
                            :href="route('servicios')"
                            :current="request()->routeIs('servicios')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Servicios
                        </flux:navlist.item>

                    @endcan

                </flux:navlist.group>


                {{-- ==================================================
                     ADMINISTRACIÓN
                     ================================================== --}}

                <flux:navlist.group
                    heading="Administración"
                    class="mt-5 grid"
                >

                    {{-- Gastos --}}
                    @can('gastos.ver')

                        <flux:navlist.item
                            icon="banknotes"
                            :href="route('gastos')"
                            :current="request()->routeIs('gastos')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Gastos
                        </flux:navlist.item>

                    @endcan


                    {{-- Empleados --}}
                    @can('empleados.ver')

                        <flux:navlist.item
                            icon="identification"
                            :href="route('empleados')"
                            :current="request()->routeIs('empleados')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Empleados
                        </flux:navlist.item>

                    @endcan


                    {{-- Temporadas --}}
                    @can('temporadas.ver')

                        <flux:navlist.item
                            icon="sun"
                            :href="route('temporadas')"
                            :current="request()->routeIs('temporadas')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Temporadas
                        </flux:navlist.item>

                    @endcan


                    {{-- Reportes --}}
                    @can('reportes.ver')

                        <flux:navlist.item
                            icon="chart-bar"
                            :href="route('reportes')"
                            :current="request()->routeIs('reportes')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Reportes
                        </flux:navlist.item>

                    @endcan


                    {{-- Roles y permisos --}}
                    @can('roles_permisos.ver')

                        <flux:navlist.item
                            icon="shield-check"
                            :href="route('roles-permisos')"
                            :current="request()->routeIs('roles-permisos*')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Roles y permisos
                        </flux:navlist.item>

                    @endcan


                    {{-- Configuración --}}
                    @can('configuracion.ver')

                        <flux:navlist.item
                            icon="cog-6-tooth"
                            :href="route('configuracion')"
                            :current="request()->routeIs('configuracion')"
                            wire:navigate
                            class="rounded-lg"
                        >
                            Configuración
                        </flux:navlist.item>

                    @endcan

                </flux:navlist.group>

            </flux:navlist>


            {{-- ======================================================
                 ESPACIO FLEXIBLE
                 ====================================================== --}}

            <flux:spacer />


            {{-- ======================================================
                 PERFIL DEL USUARIO - ESCRITORIO
                 ====================================================== --}}

            <flux:dropdown
                position="bottom"
                align="start"
            >

                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />


                <flux:menu class="w-[240px]">

                    {{-- Información del usuario --}}
                    <flux:menu.radio.group>

                        <div class="p-2 text-sm">

                            <div class="flex items-center gap-3">

                                {{-- Iniciales --}}
                                <span
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-sm font-semibold text-slate-700 dark:bg-zinc-800 dark:text-zinc-200"
                                >
                                    {{ auth()->user()->initials() }}
                                </span>


                                {{-- Datos --}}
                                <div class="min-w-0 flex-1">

                                    <span class="block truncate font-semibold text-slate-800 dark:text-white">
                                        {{ auth()->user()->name }}
                                    </span>

                                    <span class="block truncate text-xs text-slate-500 dark:text-zinc-400">
                                        {{ auth()->user()->email }}
                                    </span>

                                    <span class="mt-1 block truncate text-[10px] font-semibold uppercase tracking-wider text-[#B08D57]">
                                        {{ auth()->user()->getRoleNames()->implode(', ') }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </flux:menu.radio.group>


                    <flux:menu.separator />


                    {{-- Cerrar sesión --}}
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="w-full"
                    >

                        @csrf

                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full"
                        >
                            Cerrar sesión
                        </flux:menu.item>

                    </form>

                </flux:menu>

            </flux:dropdown>

        </flux:sidebar>


        {{-- ==========================================================
             MENÚ DEL USUARIO - MÓVIL
             ========================================================== --}}

        <flux:header class="lg:hidden border-b border-slate-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">

            <flux:sidebar.toggle
                class="lg:hidden"
                icon="bars-2"
                inset="left"
            />

            <flux:spacer />


            <flux:dropdown
                position="top"
                align="end"
            >

                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />


                <flux:menu>

                    <flux:menu.radio.group>

                        <div class="p-2 text-sm">

                            <div class="flex items-center gap-2">

                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-sm font-semibold text-slate-700 dark:bg-zinc-800 dark:text-zinc-200"
                                >
                                    {{ auth()->user()->initials() }}
                                </span>


                                <div class="min-w-0 flex-1">

                                    <span class="block truncate font-semibold text-slate-800 dark:text-white">
                                        {{ auth()->user()->name }}
                                    </span>

                                    <span class="block truncate text-xs text-slate-500 dark:text-zinc-400">
                                        {{ auth()->user()->email }}
                                    </span>

                                    <span class="mt-1 block truncate text-[10px] font-semibold uppercase tracking-wider text-[#B08D57]">
                                        {{ auth()->user()->getRoleNames()->implode(', ') }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </flux:menu.radio.group>


                    <flux:menu.separator />


                    {{-- Cerrar sesión --}}
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="w-full"
                    >

                        @csrf

                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full"
                        >
                            Cerrar sesión
                        </flux:menu.item>

                    </form>

                </flux:menu>

            </flux:dropdown>

        </flux:header>


        {{-- ==========================================================
             CONTENIDO DE LA PÁGINA
             ========================================================== --}}

        {{ $slot }}

        @fluxScripts

    </body>

</html>
