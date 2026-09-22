<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NovaStay') }}</title>
    
    <!-- Fonts y Estilos compilados por Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased">

    <!-- Barra de Navegación Superior -->
    <nav class="fixed top-0 z-50 w-full border-b border-slate-200/60 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 transition-colors duration-200">
                <span class="font-serif text-xl font-bold tracking-wide text-slate-900">NovaStay</span>
                <span class="hidden text-[10px] font-semibold uppercase tracking-widest text-amber-600 md:block">Hotel Management</span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-medium md:flex">
                <a href="{{ route('home') }}" class="text-amber-600 transition-colors duration-200 hover:text-amber-700">Inicio</a>
                <a href="{{ route('habitaciones.public') }}" class="text-slate-600 transition-colors duration-200 hover:text-amber-600">Habitaciones</a>
                <a href="{{ route('servicios.public') }}" class="text-slate-600 transition-colors duration-200 hover:text-amber-600">Servicios</a>
            </div>

            <div class="flex items-center gap-4 text-sm font-medium">
                @auth
                    @if (auth()->user()->hasRole('cliente'))
                        <a href="{{ route('mis-reservaciones') }}" class="text-amber-600 transition-colors duration-200 hover:text-amber-700">Mis reservaciones</a>
                    @endif
                    <span class="text-slate-700">{{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 transition-colors duration-200 hover:text-amber-600">Iniciar sesión</a>
                @endauth
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="fixed top-20 left-1/2 -translate-x-1/2 z-50 bg-emerald-100 border border-emerald-300 text-emerald-900 text-sm font-medium px-5 py-3 rounded-xl shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Sección Hero / Principal -->
    <header class="relative pt-24 pb-24 min-h-[85vh] bg-slate-900 text-white flex flex-col justify-between">
        <!-- Fondo con imagen -->
        <div class="absolute inset-0 opacity-40 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="absolute inset-0 bg-slate-950/50"></div>

        <div class="relative max-w-5xl mx-auto px-6 text-center py-20">
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-4">
                ... igual que un hogar.
            </h1>
            <p class="text-slate-300 text-lg md:text-xl mb-8">
                Habitaciones de lujo, servicio personalizado y experiencias únicas para cada huésped.
            </p>
            <!-- Botón central conectado a la ruta de habitaciones -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('habitaciones.public') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-medium px-6 py-3 rounded-lg transition shadow-lg">
                    Explorar habitaciones
                </a>
                <a href="{{ route('servicios.public') }}" class="inline-block border border-white/70 hover:bg-white/10 text-white font-medium px-6 py-3 rounded-lg transition">
                    Explorar servicios
                </a>
            </div>
        </div>
    </header>

    <!-- Pie de Página -->
    <footer class="bg-slate-900 text-slate-300">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-6 py-12 max-w-6xl mx-auto">

            <!-- Columna 1: Acerca de / Marca -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <span class="text-xl font-bold text-white">NovaStay</span>
                    <span class="text-xs text-slate-500 uppercase tracking-wider">Hotel Management</span>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">
                    Una experiencia de hospedaje única que combina elegancia, confort y servicio personalizado
                    para que cada estancia sea un recuerdo inolvidable.
                </p>
            </div>

            <!-- Columna 2: Contacto -->
            <div>
                <h3 class="text-white font-semibold mb-4">Contacto</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start space-x-3">
                        <span class="text-amber-600">📍</span>
                        <span>Av. Principal #123, Zona Hotelera</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="text-amber-600">📞</span>
                        <span>+52 (55) 1234-5678</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="text-amber-600">✉️</span>
                        <span>contacto@novastay.com</span>
                    </li>
                </ul>
            </div>

            <!-- Columna 3: Enlaces rápidos / Horarios -->
            <div>
                <h3 class="text-white font-semibold mb-4">Enlaces rápidos</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-amber-500 transition">Inicio</a>
                    </li>
                    <li>
                        <a href="{{ route('habitaciones.public') }}" class="hover:text-amber-500 transition">Habitaciones</a>
                    </li>
                    <li>
                        <a href="{{ route('servicios.public') }}" class="hover:text-amber-500 transition">Servicios</a>
                    </li>
                </ul>
                <p class="mt-4 text-sm text-slate-400">🕐 Atención 24/7</p>
            </div>
        </div>

        <!-- Línea divisoria y derechos de autor -->
        <div class="border-t border-slate-800">
            <p class="text-center text-sm text-slate-500 py-6 px-6">
                © 2026 NovaStay Hotel Management. Todos los derechos reservados.
            </p>
        </div>
    </footer>

</body>
</html>