<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('the seeder creates the system roles and their permissions', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    foreach (['super-admin', 'gerente', 'recepcionista', 'limpieza'] as $roleName) {
        expect(Role::where('name', $roleName)->exists())->toBeTrue();
    }

    expect(Role::findByName('super-admin')->permissions()->count())
        ->toBe(Permission::count());

    foreach (['dashboard.ver', 'reservaciones.ver', 'reservaciones.crear'] as $permission) {
        expect(Permission::where('name', $permission)->exists())->toBeTrue();
    }
});

test('the seeder creates the initial super admin user', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::where('email', 'admin@hotel.com')->first();

    expect($admin)->not->toBeNull();
    expect($admin->name)->toBe('Super Admin');
    expect(Hash::check('password', $admin->password))->toBeTrue();
    expect($admin->hasRole('super-admin'))->toBeTrue();
    expect($admin->hasPermissionTo('dashboard.ver'))->toBeTrue();
});