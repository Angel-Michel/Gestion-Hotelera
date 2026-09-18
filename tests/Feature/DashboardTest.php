<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('managers can visit the dashboard', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $gerente = User::factory()->create();
    $gerente->assignRole('gerente');

    $this->actingAs($gerente);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
