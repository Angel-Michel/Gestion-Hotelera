<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Volt\Volt as LivewireVolt;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = LivewireVolt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('clientes cannot authenticate through the personal login section', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $cliente = User::factory()->create();
    $cliente->assignRole('cliente');

    $response = $this->post('/login', [
        'email' => $cliente->email,
        'password' => 'password',
        'role' => 'personal',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['email' => 'El usuario no está registrado']);
});

test('personal employees can authenticate through the personal login section', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $gerente = User::factory()->create();
    $gerente->assignRole('gerente');

    $response = $this->post('/login', [
        'email' => $gerente->email,
        'password' => 'password',
        'role' => 'personal',
    ]);

    $this->assertAuthenticatedAs($gerente);
    $response->assertRedirect('/dashboard');
});

test('clientes can authenticate through the clientes login section', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $cliente = User::factory()->create();
    $cliente->assignRole('cliente');

    $response = $this->post('/login', [
        'email' => $cliente->email,
        'password' => 'password',
        'role' => 'clientes',
    ]);

    $this->assertAuthenticatedAs($cliente);
    $response->assertRedirect(route('mis-reservaciones', absolute: false));
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
