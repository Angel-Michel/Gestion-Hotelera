@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg p-8">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Gestión Hotelera
                </h1>

                <p class="mt-2 text-gray-500">
                    Inicia sesión para acceder al sistema
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo electrónico
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('email')
                        <span class="text-sm text-red-600 mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('password')
                        <span class="text-sm text-red-600 mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        >

                        <span class="ml-2 text-sm text-gray-600">
                            Recordarme
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm text-indigo-600 hover:text-indigo-800"
                        >
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

<button
    type="submit"
    style="width: 100%; padding: 12px; background-color: #1f2937; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;"
>
    Iniciar sesión
</button>
            </form>

        </div>
    </div>
</div>
@endsection