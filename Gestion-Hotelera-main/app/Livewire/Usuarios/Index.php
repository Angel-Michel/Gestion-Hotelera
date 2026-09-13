<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Url(as: 'q')]
    public string $busqueda = '';

    /**
     * Alterna el estado Activo / Inactivo de un usuario.
     */
    public function toggleActivo(int $userId): void
    {
        $usuario = User::findOrFail($userId);

        $usuario->activo = ! $usuario->activo;
        $usuario->save();
    }

    /**
     * Lista de usuarios filtrada por nombre o correo electrónico.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    #[Computed]
    public function usuarios()
    {
        return User::query()
            ->with('roles')
            ->when($this->busqueda !== '', function ($query) {
                $query->where('name', 'like', "%{$this->busqueda}%")
                    ->orWhere('email', 'like', "%{$this->busqueda}%");
            })
            ->orderBy('name')
            ->get();
    }
}