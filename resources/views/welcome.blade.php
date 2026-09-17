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
    <nav class="flex items-center justify-between px-8 py-4 bg-white/90 backdrop-blur-md shadow-sm fixed w-full top-0 z-50">
        <div class="flex items-center space-x-2">
            <span class="text-xl font-bold text-slate-900">NovaStay</span>
            <span class="text-xs text-slate-500 uppercase tracking-wider">Hotel Management</span>
        </div>

        <div class="hidden md:flex items-center space-x-8">
            <a href="{{ route('home') }}" class="text-amber-600 font-medium hover:text-amber-700 transition">Inicio</a>
            <a href="#habitaciones" class="text-slate-600 hover:text-slate-900 transition">Habitaciones</a>
            <a href="#servicios" class="text-slate-600 hover:text-slate-900 transition">Servicios</a>
        </div>

        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="text-slate-700 font-medium hover:text-slate-900 transition">Iniciar sesión</a>
            <a href="#reservar" class="bg-slate-900 text-white px-4 py-2 rounded-lg font-medium hover:bg-slate-800 transition">Reservar</a>
        </div>
    </nav>

    <!-- Sección Hero / Principal -->
    <header class="relative pt-24 pb-32 bg-slate-900 text-white overflow-hidden">
        <!-- Fondo con imagen -->
        <div class="absolute inset-0 opacity-40 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="absolute inset-0 bg-slate-950/50"></div>

        <div class="relative max-w-5xl mx-auto px-6 text-center py-20">
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-4">
                ... se convierte en recuerdo.
            </h1>
            <p class="text-slate-300 text-lg md:text-xl mb-8">
                Habitaciones de lujo, servicio personalizado y experiencias únicas para cada huésped.
            </p>
            <a href="#explorar" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-medium px-6 py-3 rounded-lg transition shadow-lg">
                Explorar habitaciones
            </a>
        </div>

        <!-- Buscador Flotante de Disponibilidad -->
        <div class="absolute -bottom-8 left-0 right-0 max-w-4xl mx-auto px-4 z-20">
            <div class="bg-white rounded-2xl shadow-2xl p-6 text-slate-900 border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Buscar disponibilidad</h3>
                
                <form action="{{ route('rooms.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Check-in -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Check-in</label>
                        <input type="date" name="check_in" class="w-full border border-slate-200 rounded-lg p-2.5 text-sm bg-slate-50 text-slate-800 focus:ring-2 focus:ring-slate-900 outline-none" value="2026-09-15">
                    </div>
                    <!-- Check-out -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Check-out</label>
                        <input type="date" name="check_out" class="w-full border border-slate-200 rounded-lg p-2.5 text-sm bg-slate-50 text-slate-800 focus:ring-2 focus:ring-slate-900 outline-none" value="2026-09-18">
                    </div>
                    <!-- Huéspedes -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Huéspedes</label>
                        <input type="text" name="guests" class="w-full border border-slate-200 rounded-lg p-2.5 text-sm bg-slate-50 text-slate-800 focus:ring-2 focus:ring-slate-900 outline-none" value="2 huéspedes">
                    </div>
                    <!-- Botón -->
                    <div>
                        <button type="submit" class="w-full bg-[#1e293b] hover:bg-slate-800 text-white font-medium py-2.5 px-4 rounded-lg transition shadow-md">
                            Buscar disponibilidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

</body>
</html>