<?php

use App\Models\User;
use Livewire\Volt\Volt as LivewireVolt;

test('inactive users cannot authenticate', function () {
    $user = User::factory()->inactive()->create();

    $response = LivewireVolt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasErrors('email')
        ->assertNoRedirect();

    $this->assertGuest();
});

test('inactive users get logged out automatically', function () {
    $user = User::factory()->inactive()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect('/login');

    $this->assertGuest();
});

test('inactive users are logged out from the admin users route', function () {
    $user = User::factory()->inactive()->create();
    $user->givePermissionTo('usuarios.ver');

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertRedirect('/login');

    $this->assertGuest();
});