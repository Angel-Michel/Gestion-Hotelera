<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->givePermissionTo('usuarios.ver');
});

test('el mensaje flash se muestra como notificacion flotante con auto ocultado a los 5 segundos', function () {
    $this->actingAs($this->admin)
        ->withSession(['success' => 'Usuario creado correctamente.'])
        ->get(route('usuarios.index'))
        ->assertOk()
        ->assertSee('x-data="{ visible: true }"', false)
        ->assertSee('x-init="setTimeout(() => visible = false, 5000)"', false)
        ->assertSee('x-show="visible"', false)
        ->assertSee('x-transition.duration.300ms', false)
        ->assertSee('Usuario creado correctamente.');
});

test('sin mensaje flash la notificacion flotante no se renderiza', function () {
    $this->actingAs($this->admin)
        ->get(route('usuarios.index'))
        ->assertOk()
        ->assertDontSee('setTimeout(() => visible = false, 5000)');
});

test('cada tipo de mensaje flash usa su propio estilo y rol de accesibilidad', function () {
    $this->actingAs($this->admin)
        ->withSession(['error' => 'La habitacion ya esta ocupada.'])
        ->get(route('usuarios.index'))
        ->assertOk()
        ->assertSee('role="alert"', false)
        ->assertSee('bg-rose-50', false);

    $this->actingAs($this->admin)
        ->withSession(['aviso' => 'Revisa los datos ingresados.'])
        ->get(route('usuarios.index'))
        ->assertOk()
        ->assertSee('role="status"', false)
        ->assertSee('bg-amber-50', false);
});

test('la notificacion flotante esta incluida en todos los layouts de la aplicacion', function () {
    $layouts = [
        resource_path('views/components/layouts/app/sidebar.blade.php'),
        resource_path('views/components/layouts/auth/simple.blade.php'),
        resource_path('views/components/layouts/auth/split.blade.php'),
    ];

    foreach ($layouts as $layout) {
        expect(File::exists($layout))->toBeTrue();

        expect(File::get($layout))->toContain('<x-flash-messages />');
    }
});

test('la notificacion encaja arriba de la interfaz sin tapar el contenido', function () {
    $html = File::get(resource_path('views/components/flash-messages.blade.php'));

    expect($html)
        ->toContain('fixed inset-x-0 top-20')
        ->toContain('z-[100]')
        ->toContain('aria-live="polite"');
});

test('los avisos de estado de las pantallas de auth tambien se ocultan solos', function () {
    expect(File::get(resource_path('views/components/auth-session-status.blade.php')))
        ->toContain('x-init="setTimeout(() => visible = false, 5000)"');

    expect(File::get(resource_path('views/livewire/auth/verify-email.blade.php')))
        ->toContain('x-init="setTimeout(() => visible = false, 5000)"');

    expect(File::get(resource_path('views/livewire/settings/profile.blade.php')))
        ->toContain('x-init="setTimeout(() => visible = false, 5000)"');
});
