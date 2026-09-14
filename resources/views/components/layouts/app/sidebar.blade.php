<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')

        <style>
            /* ==========================================================
               ESTILO DEL MENÚ LATERAL
               ========================================================== */

            /* Fondo general del sidebar (paleta Novastay) */
            [data-flux-sidebar] {
                background-color: var(--color-novastay-fondo, #101B3D) !important;
                border-color: var(--color-novastay-borde, #26345F) !important;
            }

            /* Fondo del cuerpo cuando se encuentra dentro del sidebar */
            [data-flux-sidebar] * {
                border-color: var(--color-novastay-borde, #26345F);
            }

            /* Título "Gestión Hotelera" */
            [data-flux-sidebar] [data-flux-navlist-group-heading] {
                color: #FFFFFF !important;
            }

            /* Texto de los módulos */
            [data-flux-sidebar] a {
                color: #FFFFFF !important;
            }

            /* Íconos de los módulos */
            [data-flux-sidebar] svg {
                color: #FFFFFF !important;
                stroke: #FFFFFF !important;
            }

            /* Efecto al pasar el mouse: transparencia azul clara */
            [data-flux-sidebar] a:hover {
                background-color: rgb(147 197 253 / 0.14) !important;
                color: #DBEAFE !important;
            }

            /* Íconos al pasar el mouse */
            [data-flux-sidebar] a:hover svg {
                color: #BFDBFE !important;
                stroke: #BFDBFE !important;
            }

            /* Módulo actualmente seleccionado */
            [data-flux-sidebar] a[aria-current="page"] {
                background-color: rgb(59 130 246 / 0.28) !important;
                color: #FFFFFF !important;
            }

            /* Ícono del módulo seleccionado */
            [data-flux-sidebar] a[aria-current="page"] svg {
                color: #BFDBFE !important;
                stroke: #BFDBFE !important;
            }

            /* ==========================================================
               PERFIL DEL USUARIO
               ========================================================== */

            /* Nombre y correo del usuario */
            [data-flux-sidebar] [data-flux-profile] {
                color: #FFFFFF !important;
            }

            /* ==========================================================
               MENÚ INFERIOR
               ========================================================== */

            /* Repository y Documentation */
            [data-flux-sidebar] a[href*="github.com"],
            [data-flux-sidebar] a[href*="laravel.com"] {
                color: #FFFFFF !important;
            }

            [data-flux-sidebar] a[href*="github.com"] svg,
            [data-flux-sidebar] a[href*="laravel.com"] svg {
                color: #FFFFFF !important;
                stroke: #FFFFFF !important;
            }

            /* Hover de Repository y Documentation */
            [data-flux-sidebar] a[href*="github.com"]:hover,
            [data-flux-sidebar] a[href*="laravel.com"]:hover {
                background-color: #1D2B52 !important;
                color: #FFFFFF !important;
            }

            [data-flux-sidebar] a[href*="github.com"]:hover svg,
            [data-flux-sidebar] a[href*="laravel.com"]:hover svg {
                color: #FFFFFF !important;
                stroke: #FFFFFF !important;
            }

            /* ==========================================================
               MENÚ DEL USUARIO
               ========================================================== */

            /* Fondo del menú desplegable */
            [data-flux-menu] {
                border-color: var(--color-novastay-borde, #26345F) !important;
            }

            /* Ícono dorado del módulo de seguridad */
            [data-flux-sidebar] a.text-blue-500 svg {
                color: var(--color-novastay-dorado, #C9A227) !important;
                stroke: var(--color-novastay-dorado, #C9A227) !important;
            }
        </style>
    </head>

    <body class="min-h-screen bg-[#F8FAFC] dark:bg-zinc-800">
        {{-- ==========================================================
             MENÚ LATERAL
             ========================================================== --}}
        <flux:sidebar
            sticky
            stashable
            class="border-r border-[#26345F] bg-[#101B3D] dark:border-[#26345F] dark:bg-[#101B3D]"
        >

            {{-- Botón para cerrar el menú en dispositivos pequeños --}}
            <flux:sidebar.toggle
                class="lg:hidden"
                icon="x-mark"
            />

            {{-- Logotipo Novastay --}}
            <a
                href="{{ route('dashboard') }}"
                class="mr-5 flex items-center space-x-3"
                wire:navigate
            >
                <x-app-logo
                    class="size-8"
                    href="#"
                />

                <span class="text-lg font-semibold tracking-[0.22em] text-white uppercase">
                    Novastay
                </span>
            </a>

            {{-- ==========================================================
                 MENÚ PRINCIPAL - GESTIÓN HOTELERA
                 ========================================================== --}}

            <flux:navlist variant="outline">

                <flux:navlist.group
                    heading="Gestión Hotelera"
                    class="grid"
                >

                    {{-- Dashboard --}}
                    @can('dashboard.ver')
                        <flux:navlist.item
                            icon="home"
                            :href="route('dashboard')"
                            :current="request()->routeIs('dashboard')"
                            wire:navigate
                        >
                            Dashboard
                        </flux:navlist.item>
                    @endcan

                    {{-- Reservaciones --}}
                    @can('reservaciones.ver')
                        <flux:navlist.item
                            icon="calendar-days"
                            :href="route('reservaciones')"
                            :current="request()->routeIs('reservaciones')"
                            wire:navigate
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
                        >
                            Servicios
                        </flux:navlist.item>
                    @endcan

                    {{-- Gastos --}}
                    @can('gastos.ver')
                        <flux:navlist.item
                            icon="banknotes"
                            :href="route('gastos')"
                            :current="request()->routeIs('gastos')"
                            wire:navigate
                        >
                            Gastos
                        </flux:navlist.item>
                    @endcan

                    {{-- Empleados (solo Super Administrador) --}}
                    @role('super-admin')
                        <flux:navlist.item
                            icon="identification"
                            :href="route('empleados')"
                            :current="request()->routeIs('empleados')"
                            wire:navigate
                        >
                            Empleados
                        </flux:navlist.item>
                    @endrole

                    {{-- Temporadas --}}
                    @can('temporadas.ver')
                        <flux:navlist.item
                            icon="sun"
                            :href="route('temporadas')"
                            :current="request()->routeIs('temporadas')"
                            wire:navigate
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
                        >
                            Reportes
                        </flux:navlist.item>
                    @endcan

                    {{-- Roles y permisos (solo Super Administrador) --}}
                    @role('super-admin')
                        <flux:navlist.item
                            icon="shield-check"
                            class="text-blue-500"
                            :href="route('roles-permisos')"
                            :current="request()->routeIs('roles-permisos*')"
                            wire:navigate
                        >
                            Roles y permisos
                        </flux:navlist.item>
                    @endrole

                    {{-- Configuración --}}
                    @can('configuracion.ver')
                        <flux:navlist.item
                            icon="cog-6-tooth"
                            :href="route('configuracion')"
                            :current="request()->routeIs('configuracion')"
                            wire:navigate
                        >
                            Configuración
                        </flux:navlist.item>
                    @endcan

                </flux:navlist.group>

            </flux:navlist>

            {{-- Espacio --}}
            <flux:spacer />

            {{-- ==========================================================
                 ENLACES INFORMATIVOS
                 ========================================================== --}}

            <flux:navlist variant="outline">

                <flux:navlist.item
                    icon="folder-git-2"
                    href="https://github.com/laravel/livewire-starter-kit"
                    target="_blank"
                >
                    Repository
                </flux:navlist.item>

                <flux:navlist.item
                    icon="book-open-text"
                    href="https://laravel.com/docs/starter-kits"
                    target="_blank"
                >
                    Documentation
                </flux:navlist.item>

            </flux:navlist>

            {{-- ==========================================================
                 MENÚ DEL USUARIO - ESCRITORIO
                 ========================================================== --}}

            <flux:dropdown
                position="bottom"
                align="start"
            >

                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">

                    <flux:menu.radio.group>

                        <div class="p-0 text-sm font-normal">

                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">

                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">

                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>

                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">

                                    <span class="truncate font-semibold">
                                        {{ auth()->user()->name }}
                                    </span>

                                    <span class="truncate text-xs">
                                        {{ auth()->user()->email }}
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
                            {{ __('Log Out') }}
                        </flux:menu.item>

                    </form>

                </flux:menu>

            </flux:dropdown>

        </flux:sidebar>

        {{-- ==========================================================
             MENÚ DEL USUARIO - MÓVIL
             ========================================================== --}}

        <flux:header class="lg:hidden">

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

                        <div class="p-0 text-sm font-normal">

                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">

                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">

                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>

                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">

                                    <span class="truncate font-semibold">
                                        {{ auth()->user()->name }}
                                    </span>

                                    <span class="truncate text-xs">
                                        {{ auth()->user()->email }}
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
                            {{ __('Log Out') }}
                        </flux:menu.item>

                    </form>

                </flux:menu>

            </flux:dropdown>

        </flux:header>

        {{-- Contenido de la página --}}
        {{ $slot }}

        @fluxScripts

    </body>

</html>