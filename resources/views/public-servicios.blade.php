<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servicios - {{ config('app.name', 'NovaStay') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased pt-24">

    <!-- ==========================================
         1. BARRA DE NAVEGACIÓN (Restaurada)
         ========================================== -->
    <nav class="flex items-center justify-between px-8 py-4 bg-white shadow-sm fixed w-full top-0 z-50">
        <div class="flex items-center space-x-2">
            <span class="text-xl font-bold text-slate-900">NovaStay</span>
            <span class="text-xs text-slate-500 uppercase tracking-wider">Hotel Management</span>
        </div>

        <div class="hidden md:flex items-center space-x-8">
            <a href="/" class="text-slate-600 hover:text-slate-900 transition">Inicio</a>
            <a href="/#habitaciones" class="text-slate-600 hover:text-slate-900 transition">Habitaciones</a>
            <a href="{{ route('servicios.public') }}" class="text-amber-600 font-medium transition">Servicios</a>
        </div>

        <div class="flex items-center space-x-4">
            @auth
                @if (auth()->user()->hasRole('cliente'))
                    <a href="{{ route('mis-reservaciones') }}" class="text-amber-600 font-semibold hover:text-amber-700 transition">Mis reservaciones</a>
                @endif
                <span class="text-slate-700 font-medium">{{ auth()->user()->name }}</span>
            @else
                <a href="{{ route('login') }}" class="text-slate-700 font-medium hover:text-slate-900 transition">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition">Crear cuenta</a>
            @endauth
            <a href="/#reservar" class="bg-slate-900 text-white px-4 py-2 rounded-lg font-medium hover:bg-slate-800 transition">Reservar</a>
        </div>
    </nav>

    <!-- ==========================================
         2. SECCIÓN PRINCIPAL DE SERVICIOS
         ========================================== -->
    <section class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Encabezado de la sección -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                    Comodidades de primer nivel
                </h2>
                <p class="mt-4 text-lg text-slate-500">
                    Diseñamos cada espacio pensando en tu confort. Disfruta de instalaciones exclusivas y servicio personalizado durante toda tu estancia en NovaStay.
                </p>
            </div>

            <!-- CONTENEDOR GRID: 2 Columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Tarjeta 1: Restaurante Gourmet -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1974&auto=format&fit=crop" alt="Restaurante Gourmet" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md border border-white/20 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        $45.00 USD
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Restaurante Gourmet</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Cena de 3 tiempos con maridaje. Alta cocina internacional preparada con ingredientes locales.
                                </p>
                                <button class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Agregar a la reserva
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Spa & Bienestar -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=2070&auto=format&fit=crop" alt="Spa y Bienestar" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md border border-white/20 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        $120.00 USD
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Spa & Bienestar</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Acceso a circuito de hidroterapia y masaje relajante de 60 minutos para liberar tensión.
                                </p>
                                <button class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Agregar a la reserva
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3: Piscina Infinita -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1582610116397-edb318620f90?q=80&w=2070&auto=format&fit=crop" alt="Piscina Infinita" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-emerald-500/80 backdrop-blur-md border border-emerald-400 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        Incluido
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Piscina Infinita</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Espectaculares vistas panorámicas con control de temperatura, servicio de toallas y bar en el agua.
                                </p>
                                <button class="w-full bg-slate-100 hover:bg-white text-slate-900 font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Acceso libre
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 4: Gimnasio 24/7 -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop" alt="Gimnasio" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-emerald-500/80 backdrop-blur-md border border-emerald-400 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        Incluido
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Gimnasio 24/7</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Equipamiento cardiovascular y de fuerza de última generación, disponible a cualquier hora del día.
                                </p>
                                <button class="w-full bg-slate-100 hover:bg-white text-slate-900 font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Acceso libre
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 5: Minibar Premium -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=2070&auto=format&fit=crop" alt="Minibar Premium" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md border border-white/20 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        $85.00 USD
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Minibar Premium</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Selección exclusiva de licores internacionales, vinos boutique y snacks artesanales en tu habitación.
                                </p>
                                <button class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Agregar a la reserva
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 6: Room Service -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1635350736475-c8cef4b21906?q=80&w=2070&auto=format&fit=crop" alt="Room Service" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md border border-white/20 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        A la carta
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Room Service 24/7</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Experiencia gastronómica sin salir de la cama. Nuestro menú completo está a una llamada de distancia.
                                </p>
                                <button class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    Ver menú
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 7: Transporte VIP -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?q=80&w=2070&auto=format&fit=crop" alt="Transporte VIP" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md border border-white/20 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        $60.00 USD
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Transporte Privado</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Traslados de lujo desde y hacia el aeropuerto, además de servicio de chofer a disposición.
                                </p>
                                <button class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Reservar traslado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 8: Eventos y Bodas -->
                <div class="relative overflow-hidden rounded-2xl group h-96 shadow-lg cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2098&auto=format&fit=crop" alt="Eventos y Convenciones" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-slate-800/80 backdrop-blur-md border border-slate-600 text-white px-4 py-1.5 rounded-full text-sm font-bold z-10 shadow-sm">
                        Cotizar
                    </div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                        <h3 class="text-3xl font-serif font-bold text-white mb-3">Eventos y Bodas</h3>
                        <div class="overflow-hidden">
                            <div class="transform translate-y-16 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col gap-4">
                                <p class="text-slate-200 text-sm leading-relaxed">
                                    Salones modulares y espacios al aire libre, equipados con tecnología para albergar grandes celebraciones.
                                </p>
                                <button class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    Solicitar presupuesto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- FIN DEL CONTENEDOR GRID -->
        </div>
    </section>

</body>
</html>