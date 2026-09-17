<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - NovaStay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900 bg-white min-h-screen flex">

    <!-- Columna Izquierda (Formulario) -->
    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8 md:p-16">
        
        <div class="w-full max-w-md">
            
            <!-- Logo Header -->
            <div class="flex flex-col items-center justify-center mb-10">
                <div class="bg-amber-600 text-white p-2 rounded-lg mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h1 class="text-2xl font-bold leading-none text-slate-900">NovaStay</h1>
                <span class="text-xs text-amber-600 uppercase tracking-widest font-semibold mt-1">Hotel Management</span>
            </div>

            <!-- Selector de Rol (Toggle) -->
            <div class="bg-[#f3f4f6] p-1.5 rounded-xl flex mb-8">
                <button id="btn-personal" onclick="setRole('personal')" class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200 bg-white shadow-sm text-slate-900 border border-slate-200">
                    Personal
                </button>
                <button id="btn-clientes" onclick="setRole('clientes')" class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-500 hover:text-slate-700">
                    Clientes
                </button>
            </div>

            <!-- Cabecera Dinámica del Formulario -->
            <div class="mb-8">
                <h2 id="form-title" class="text-3xl font-bold text-[#0f172a] mb-2 font-serif">Acceso al sistema</h2>
                <p id="form-subtitle" class="text-sm text-slate-500">Ingresa tus credenciales de administrador</p>
            </div>

           <!-- Formulario Real -->
    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="role" id="role-input" value="personal">

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">Correo electrónico</label>
            <input type="email" name="email" id="email-input" class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition shadow-sm" placeholder="usuario@novastay.com" required>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">Contraseña</label>
            <div class="relative">
                <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition shadow-sm" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                <button type="button" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <div id="extra-personal" class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                <label for="remember" class="ml-2 text-sm text-slate-600">Recordarme</label>
            </div>
            <a href="#" class="text-sm font-medium text-amber-600 hover:text-amber-700 ml-auto">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-semibold py-3.5 rounded-lg transition shadow-md mt-2">
            Iniciar sesión
        </button>
    </form>
            <div class="text-center mt-12">
                <a href="{{ route('home') }}" class="text-sm text-slate-400 hover:text-slate-600 transition">&larr; Volver al inicio</a>
            </div>
        </div>
    </div>

    <!-- Columna Derecha (Imagen decorativa) -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">
        <!-- Capa 1: La imagen local conectada con asset() -->
        <div class="absolute inset-0 bg-cover bg-center opacity-80" style="background-image: url('{{ asset('images/bg-login.jpg') }}');"></div>
        
        <!-- Capa 2: Degradado oscuro para dar contraste -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/60 to-transparent"></div>
        
        <!-- Capa 3: Textos dinámicos (Asegúrate de copiar esto) -->
        <div class="relative z-10 flex flex-col justify-end p-16 w-full h-full">
            <h2 id="hero-title" class="text-4xl md:text-5xl font-bold text-white mb-4">Control Total.</h2>
            <p id="hero-subtitle" class="text-slate-300 text-lg max-w-lg">Supervisa reservaciones, asigna roles y gestiona la experiencia de tus huéspedes con eficiencia y precisión.</p>
        </div>
    </div>

    <!-- Script de Mutación del DOM -->
    <script>
        function setRole(role) {
            // Elementos del formulario
            const btnPersonal = document.getElementById('btn-personal');
            const btnClientes = document.getElementById('btn-clientes');
            const title = document.getElementById('form-title');
            const subtitle = document.getElementById('form-subtitle');
            const emailInput = document.getElementById('email-input');
            const extraPersonal = document.getElementById('extra-personal');
            const extraClientes = document.getElementById('extra-clientes');
            const roleInput = document.getElementById('role-input');
            
            // Elementos de la imagen derecha
            const heroTitle = document.getElementById('hero-title');
            const heroSubtitle = document.getElementById('hero-subtitle');

            roleInput.value = role;

            if (role === 'personal') {
                // UI Botones
                btnPersonal.className = 'flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200 bg-white shadow-sm text-slate-900 border border-slate-200';
                btnClientes.className = 'flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-500 hover:text-slate-700';

                // Textos Formulario
                title.innerText = 'Acceso al sistema';
                subtitle.innerText = 'Ingresa tus credenciales de administrador';
                emailInput.placeholder = 'usuario@novastay.com';
                
                // Mostrar/Ocultar
                extraPersonal.classList.remove('hidden');
                extraClientes.classList.add('hidden');

                // Textos Imagen
                heroTitle.innerText = 'Control Total.';
                heroSubtitle.innerText = 'Supervisa reservaciones, asigna roles y gestiona la experiencia de tus huéspedes con eficiencia y precisión.';
            } else {
                // UI Botones
                btnClientes.className = 'flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200 bg-white shadow-sm text-slate-900 border border-slate-200';
                btnPersonal.className = 'flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-500 hover:text-slate-700';

                // Textos Formulario
                title.innerText = 'Bienvenido de vuelta';
                subtitle.innerText = 'Accede a tu cuenta para gestionar tus reservaciones';
                emailInput.placeholder = 'tu@email.com';
                
                // Mostrar/Ocultar
                extraPersonal.classList.add('hidden');
                extraClientes.classList.remove('hidden');

                // Textos Imagen
                heroTitle.innerText = 'Tu escape te espera.';
                heroSubtitle.innerText = 'Reserva habitaciones de lujo, revisa tu historial y prepárate para una experiencia inolvidable.';
            }
        }
    </script>
</body>
</html>