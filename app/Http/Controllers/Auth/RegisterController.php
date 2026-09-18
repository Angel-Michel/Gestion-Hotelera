<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
        ];
    }

    public function show(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'activo' => true,
        ]);

        $rol = Role::firstOrCreate([
            'name' => 'cliente',
            'guard_name' => 'web',
        ]);
        $usuario->assignRole($rol);

        $partes = preg_split('/\s+/', trim($datos['name']), 2);

        Cliente::create([
            'user_id' => $usuario->id,
            'nombre' => $partes[0],
            'apellido' => $partes[1] ?? '',
            'email' => $datos['email'],
            'telefono' => $datos['telefono'] ?? null,
        ]);

        Auth::login($usuario);

        if (session()->has('reserva.pendiente')) {
            return redirect()->route('reserva.confirmar');
        }

        return redirect()->route('home');
    }
}
