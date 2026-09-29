<?php

namespace App\Providers;

use App\Livewire\Habitaciones\Index as HabitacionesIndex;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Livewire deriva el nombre "habitaciones" tanto de la clase App\Livewire\Habitaciones
        // como de un contenedor Index dentro de la subcarpeta, porque les borra el sufijo
        // ".index". Se registra el nombre explícito para que el contenedor modular no
        // colisione con el componente monolítico que se conserva.
        Livewire::component('habitaciones.index', HabitacionesIndex::class);
    }
}
