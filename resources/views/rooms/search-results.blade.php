<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados de Búsqueda - NovaStay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <!-- Navbar -->
    <nav class="flex items-center justify-between px-8 py-4 bg-white shadow-sm border-b border-slate-100">
        <div class="flex items-center space-x-2">
            <div class="bg-amber-600 text-white p-1.5 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <span class="text-xl font-bold text-slate-900 leading-none block">NovaStay</span>
                <span class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold block">Hotel Management</span>
            </div>
        </div>
        <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-900 transition">Inicio</a>
            <a href="#" class="text-amber-600 border-b-2 border-amber-600 pb-1">Habitaciones</a>
            <a href="#" class="text-slate-500 hover:text-slate-900 transition">Servicios</a>
        </div>
        <div class="flex items-center space-x-4 text-sm font-medium">
            <a href="{{ route('login') }}" class="text-slate-700 font-medium hover:text-slate-900 transition">Iniciar sesión</a>
            <a href="#" class="bg-[#111827] text-white px-5 py-2 rounded-lg hover:bg-slate-800 transition">Reservar</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        
        <!-- Header de Resultados -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Habitaciones disponibles</h1>
                <p class="text-slate-500 text-sm">
                    Mostrando opciones disponibles &middot; <span class="font-medium text-slate-700">{{ $checkIn ?? 'Fecha de entrada' }} &rarr; {{ $checkOut ?? 'Fecha de salida' }}</span> &middot; {{ $guests ?? 'Huéspedes no definidos' }}
                </p>
            </div>
            <button class="mt-4 md:mt-0 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium shadow-sm hover:bg-slate-50 transition">
                Todos los tipos &darr;
            </button>
        </div>

        <!-- Grid de Habitaciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Tarjeta 1: Disponible -->
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition duration-300 flex flex-col">
                <div class="relative h-56 bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80" alt="Habitación Estándar" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm">Disponible</div>
                    <div class="absolute top-4 right-4 bg-white text-slate-700 text-xs font-bold px-3 py-1 rounded-md shadow-sm">#101</div>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Estándar</h3>
                            <p class="text-xs text-slate-500">Piso 1 &middot; 2 personas</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-amber-600">$850</span>
                            <p class="text-[10px] text-slate-400 uppercase">por noche</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-4 line-clamp-2">Habitación estándar con vista al jardín, cama matrimonial y todas las comodidades modernas para una estancia perfecta.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">WiFi</span>
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">TV</span>
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">Aire acondicionado</span>
                        <span class="text-slate-400 text-[11px] px-1 py-1">+2 más</span>
                    </div>
                    <div class="mt-auto grid grid-cols-2 gap-3">
                        <button class="border border-slate-200 text-slate-700 text-sm font-medium py-2 rounded-lg hover:bg-slate-50 transition">Ver detalles</button>
                        <button class="bg-[#111827] text-white text-sm font-medium py-2 rounded-lg hover:bg-slate-800 transition">Reservar ahora</button>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Ocupada -->
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm opacity-80 flex flex-col grayscale-[20%]">
                <div class="relative h-56 bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80" alt="Habitación Estándar" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm">Ocupada</div>
                    <div class="absolute top-4 right-4 bg-white text-slate-700 text-xs font-bold px-3 py-1 rounded-md shadow-sm">#102</div>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Estándar</h3>
                            <p class="text-xs text-slate-500">Piso 1 &middot; 2 personas</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-amber-600">$850</span>
                            <p class="text-[10px] text-slate-400 uppercase">por noche</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-4">Habitación estándar con vista al jardín, cama matrimonial y todas las comodidades.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">WiFi</span>
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">TV</span>
                    </div>
                    <div class="mt-auto grid grid-cols-2 gap-3">
                        <button class="border border-slate-200 text-slate-700 text-sm font-medium py-2 rounded-lg hover:bg-slate-50 transition">Ver detalles</button>
                        <button class="bg-slate-300 text-slate-500 text-sm font-medium py-2 rounded-lg cursor-not-allowed">No disponible</button>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Reservada / Doble -->
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm flex flex-col">
                <div class="relative h-56 bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=80" alt="Habitación Doble" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm">Reservada</div>
                    <div class="absolute top-4 right-4 bg-white text-slate-700 text-xs font-bold px-3 py-1 rounded-md shadow-sm">#103</div>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Doble</h3>
                            <p class="text-xs text-slate-500">Piso 1 &middot; 4 personas</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-amber-600">$1,200</span>
                            <p class="text-[10px] text-slate-400 uppercase">por noche</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-4">Habitación doble con dos camas queen, vista a la piscina y espacio amplio para familias.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">WiFi</span>
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">TV</span>
                        <span class="bg-slate-100 text-slate-600 text-[11px] px-2 py-1 rounded-md">Aire acondicionado</span>
                        <span class="text-slate-400 text-[11px] px-1 py-1">+3 más</span>
                    </div>
                    <div class="mt-auto grid grid-cols-2 gap-3">
                        <button class="border border-slate-200 text-slate-700 text-sm font-medium py-2 rounded-lg hover:bg-slate-50 transition">Ver detalles</button>
                        <button class="bg-slate-300 text-slate-500 text-sm font-medium py-2 rounded-lg cursor-not-allowed">No disponible</button>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>