<div class="pb-10">
    <!-- Título de la página con acento sutil -->
    <div class="mb-8 border-b border-slate-200/60 pb-5">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard General</h2>
        <p class="text-sm text-slate-500 mt-1.5 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Operación del hotel en tiempo real
        </p>
    </div>

    <!-- 4 Tarjetas de Métricas (Diseño Elevado con Micro-interacciones) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Ocupación (Tema Azul) -->
        <div class="relative bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100/80 overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-50 p-2.5 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600 uppercase tracking-wider">
                    Hoy
                </span>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-slate-500">Ocupación actual</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $ocupacion ?? 0 }}%</span>
                </div>
                <p class="mt-2 text-sm text-slate-500 font-medium">
                    <span class="text-blue-600 font-bold bg-blue-50/50 px-1.5 py-0.5 rounded">{{ $habitacionesOcupadas ?? 0 }}</span> de {{ $totalHabitaciones ?? 0 }} hab.
                </p>
            </div>
        </div>

        <!-- Reservas Activas (Tema Índigo) -->
        <div class="relative bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100/80 overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex justify-between items-start mb-4">
                <div class="bg-indigo-50 p-2.5 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600 uppercase tracking-wider">
                    Activas
                </span>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-slate-500">Reservas</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $reservasActivas ?? 0 }}</span>
                </div>
                <p class="mt-2 text-sm text-slate-500 font-medium">
                    Pendientes y confirmadas
                </p>
            </div>
        </div>

        <!-- Ingresos (Tema Esmeralda) -->
        <div class="relative bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100/80 overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex justify-between items-start mb-4">
                <div class="bg-emerald-50 p-2.5 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-1 text-[10px] font-bold uppercase tracking-wider border border-emerald-200">
                    + Al mes
                </span>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-slate-500">Ingresos brutos</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-slate-900 tracking-tight">${{ number_format($ingresosMes ?? 0, 2) }}</span>
                </div>
                <p class="mt-2 text-sm text-emerald-600 font-semibold capitalize bg-emerald-50/50 w-fit px-2 py-0.5 rounded">
                    Corte: {{ now()->translatedFormat('F Y') }}
                </p>
            </div>
        </div>

        <!-- Tareas (Tema Ámbar) -->
        <div class="relative bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100/80 overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-amber-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex justify-between items-start mb-4">
                <div class="bg-amber-50 p-2.5 rounded-xl text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <span class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 px-2 py-1 text-[10px] font-bold uppercase tracking-wider border border-amber-200">
                    Atención
                </span>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-slate-500">Limpieza</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-slate-900 tracking-tight">0</span>
                </div>
                <p class="mt-2 text-sm text-slate-500 font-medium">
                    Pendientes por resolver
                </p>
            </div>
        </div>

    </div>

    <!-- Tablas con Diseño Limpio -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Próximas Llegadas -->
        <div class="bg-white rounded-2xl p-1 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.06)] border border-slate-200/60 flex flex-col h-full">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-xl">
                <h3 class="text-base font-bold text-slate-800">Próximas llegadas</h3>
                <button class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">Ver agenda →</button>
            </div>
            <div class="flex-grow flex flex-col items-center justify-center py-14 px-6 text-center">
                <div class="bg-slate-50 p-5 rounded-full mb-4 shadow-inner">
                    <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">El lobby está despejado</h4>
                <p class="text-sm text-slate-500 mt-1 max-w-[250px]">No hay clientes programados para llegar en las próximas horas.</p>
            </div>
        </div>

        <!-- Limpieza Pendiente -->
        <div class="bg-white rounded-2xl p-1 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.06)] border border-slate-200/60 flex flex-col h-full">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-xl">
                <h3 class="text-base font-bold text-slate-800">Limpieza pendiente</h3>
                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">0 Tareas</span>
            </div>
            <div class="flex-grow flex flex-col items-center justify-center py-14 px-6 text-center">
                <div class="bg-emerald-50 p-5 rounded-full mb-4 shadow-inner">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">Todo impecable</h4>
                <p class="text-sm text-slate-500 mt-1 max-w-[250px]">El equipo de mantenimiento no tiene órdenes pendientes en este momento.</p>
            </div>
        </div>

    </div>
</div>