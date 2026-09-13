<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>

        @include('partials.head')

        <style>
            /* ==========================================================
               ESTILO DEL MENÚ LATERAL
               ========================================================== */

            /* Fondo general del sidebar */
            [data-flux-sidebar] {
                background-color: #101B3D !important;
                border-color: #26345F !important;
            }

            /* Fondo del cuerpo cuando se encuentra dentro del sidebar */
            [data-flux-sidebar] * {
                border-color: #26345F;
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

            /* Efecto al pasar el mouse */
            [data-flux-sidebar] a:hover {
                background-color: #1D2B52 !important;
                color: #FFFFFF !important;
            }

            /* Íconos al pasar el mouse */
            [data-flux-sidebar] a:hover svg {
                color: #FFFFFF !important;
                stroke: #FFFFFF !important;
            }

            /* Módulo actualmente seleccionado */
            [data-flux-sidebar] a[aria-current="page"] {
                background-color: #263A70 !important;
                color: #FFFFFF !important;
            }

            /* Ícono del módulo seleccionado */
            [data-flux-sidebar] a[aria-current="page"] svg {
                color: #FFFFFF !important;
                stroke: #FFFFFF !important;
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
                border-color: #26345F !important;
            }
        </style>

    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-800">

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

            {{-- Logo --}}
            <a
                href="{{ route('dashboard') }}"
                class="mr-5 flex items-center space-x-2"
                wire:navigate
            >

                <x-app-logo
                    class="size-8"
                    href="#"
                />

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
                    <flux:navlist.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate
                    >
                        Dashboard
                    </flux:navlist.item>

                    {{-- Reservaciones --}}
                    <flux:navlist.item
                        icon="calendar-days"
                        href="#"
                    >
                        Reservaciones
                    </flux:navlist.item>

                    {{-- Habitaciones --}}
                    <flux:navlist.item
                        icon="building-office-2"
                        href="#"
                    >
                        Habitaciones
                    </flux:navlist.item>

                    {{-- Clientes --}}
                    <flux:navlist.item
                        icon="users"
                        href="#"
                    >
                        Clientes
                    </flux:navlist.item>

                    {{-- Check-in / Check-out --}}
                    <flux:navlist.item
                        icon="arrow-right-start-on-rectangle"
                        href="#"
                    >
                        Check-in / Check-out
                    </flux:navlist.item>

                    {{-- Limpieza --}}
                    <flux:navlist.item
                        icon="sparkles"
                        href="#"
                    >
                        Limpieza
                    </flux:navlist.item>

                    {{-- Pagos --}}
                    <flux:navlist.item
                        icon="credit-card"
                        href="#"
                    >
                        Pagos
                    </flux:navlist.item>

                    {{-- Servicios --}}
                    <flux:navlist.item
                        icon="wrench-screwdriver"
                        href="#"
                    >
                        Servicios
                    </flux:navlist.item>

                    {{-- Gastos --}}
                    <flux:navlist.item
                        icon="banknotes"
                        href="#"
                    >
                        Gastos
                    </flux:navlist.item>

                    {{-- Empleados --}}
                    <flux:navlist.item
                        icon="identification"
                        href="#"
                    >
                        Empleados
                    </flux:navlist.item>

                    {{-- Temporadas --}}
                    <flux:navlist.item
                        icon="sun"
                        href="#"
                    >
                        Temporadas
                    </flux:navlist.item>

                    {{-- Reportes --}}
                    <flux:navlist.item
                        icon="chart-bar"
                        href="#"
                    >
                        Reportes
                    </flux:navlist.item>

                    {{-- Usuarios --}}
                    @can('usuarios.ver')

                        <flux:navlist.item
                            icon="user-group"
                            :href="route('usuarios.index')"
                            :current="request()->routeIs('usuarios.*')"
                            wire:navigate
                        >
                            Usuarios
                        </flux:navlist.item>

                    @endcan

                    {{-- Gestión de usuarios (Estado Activo/Inactivo) --}}
                    @can('usuarios.ver')

                        <flux:navlist.item
                            icon="switch-horizontal"
                            :href="route('admin.users.index')"
                            :current="request()->routeIs('admin.users.*')"
                            wire:navigate
                        >
                            Gestión de usuarios
                        </flux:navlist.item>

                    @endcan

                    {{-- Roles y permisos --}}
                    @can('roles_permisos.ver')

                        <flux:navlist.item
                            icon="shield-check"
                            :href="route('roles-permisos.index')"
                            :current="request()->routeIs('roles-permisos.*')"
                            wire:navigate
                        >
                            Roles y permisos
                        </flux:navlist.item>

                    @endcan

                    {{-- Configuración --}}
                    <flux:navlist.item
                        icon="cog-6-tooth"
                        href="#"
                    >
                        Configuración
                    </flux:navlist.item>

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