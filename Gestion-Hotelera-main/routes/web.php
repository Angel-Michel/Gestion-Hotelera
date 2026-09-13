
<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolPermisoController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::get('/usuarios', [UsuarioController::class, 'index'])
    ->middleware('permission:usuarios.ver')
    ->name('usuarios.index');

Route::get('/usuarios/create', [UsuarioController::class, 'create'])
    ->middleware('permission:usuarios.crear')
    ->name('usuarios.create');

Route::post('/usuarios', [UsuarioController::class, 'store'])
    ->middleware('permission:usuarios.crear')
    ->name('usuarios.store');

Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])
    ->middleware('permission:usuarios.editar')
    ->name('usuarios.edit');

Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])
    ->middleware('permission:usuarios.editar')
    ->name('usuarios.update');

Route::get('/roles-permisos', [RolPermisoController::class, 'index'])
    ->middleware('permission:roles_permisos.ver')
    ->name('roles-permisos.index');

Route::put('/roles-permisos', [RolPermisoController::class, 'update'])
    ->middleware('permission:roles_permisos.editar')
    ->name('roles-permisos.update');

Route::get('/admin/users', \App\Livewire\Usuarios\Index::class)
    ->middleware(['auth', 'permission:usuarios.ver'])
    ->name('admin.users.index');

