<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LoginController extends Controller implements HasMiddleware
{
    use AuthenticatesUsers;

    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout']),
        ];
    }

    protected function redirectTo(): string
    {
        if (session()->has('reserva.pendiente')) {
            return route('reserva.confirmar');
        }

        $usuario = auth()->user();

        if ($usuario?->hasRole('cliente')) {
            return route('mis-reservaciones');
        }

        if ($usuario?->can('dashboard.ver')) {
            return '/dashboard';
        }

        if ($usuario?->can('reservaciones.ver')) {
            return route('reservaciones');
        }

        if ($usuario?->can('checkin_checkout.ver')) {
            return route('checkin-checkout');
        }

        if ($usuario?->can('limpieza.ver')) {
            return route('limpieza');
        }

        return '/';
    }
}
