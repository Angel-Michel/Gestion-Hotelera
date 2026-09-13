<?php

use App\Livewire\Usuarios\Index as UsuariosIndex;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to login on the admin users route', function () {
    $this->get('/admin/users')
        ->assertRedirect('/login');
});

test('users without permission cannot access the admin users route', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertForbidden();
});

test('users with permission can access the admin users route', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('usuarios.ver');

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertOk();
});

test('lists all registered users', function () {
    $admin = User::factory()->create(['name' => 'Admin Uno']);
    $usuario = User::factory()->create(['name' => 'Usuario Dos']);
    $admin->givePermissionTo('usuarios.ver');

    $this->actingAs($admin);

    Livewire::test(UsuariosIndex::class)
        ->assertSee('Admin Uno')
        ->assertSee('Usuario Dos');
});

test('filters users by name in real time', function () {
    $admin = User::factory()->create(['name' => 'Admin Uno']);
    $usuario = User::factory()->create(['name' => 'Juan Perez']);
    $admin->givePermissionTo('usuarios.ver');

    $this->actingAs($admin);

    Livewire::test(UsuariosIndex::class)
        ->set('busqueda', 'Juan')
        ->assertSee('Juan Perez')
        ->assertDontSee('Admin Uno');
});

test('filters users by email in real time', function () {
    $admin = User::factory()->create(['name' => 'Admin Uno']);
    $usuario = User::factory()->create(['name' => 'Juan Perez', 'email' => 'juan@hotel.test']);
    $admin->givePermissionTo('usuarios.ver');

    $this->actingAs($admin);

    Livewire::test(UsuariosIndex::class)
        ->set('busqueda', 'juan@hotel.test')
        ->assertSee('Juan Perez')
        ->assertDontSee('Admin Uno');
});

test('toggles the active status of a user', function () {
    $admin = User::factory()->create(['name' => 'Admin Uno']);
    $usuario = User::factory()->create(['activo' => true]);
    $admin->givePermissionTo('usuarios.ver');

    $this->actingAs($admin);

    Livewire::test(UsuariosIndex::class)
        ->call('toggleActivo', $usuario->id);

    expect($usuario->fresh()->activo)->toBeFalse();
});