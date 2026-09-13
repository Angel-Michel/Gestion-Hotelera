<?php

use App\Livewire\RolesPermisos;
use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

test('guests are redirected to login on hotel module routes', function () {
    $uris = [
        '/dashboard', '/reservaciones', '/habitaciones', '/clientes',
        '/checkin-checkout', '/limpieza', '/pagos', '/servicios',
        '/gastos', '/empleados', '/temporadas', '/reportes',
        '/roles-permisos', '/configuracion',
    ];

    foreach ($uris as $uri) {
        $this->get($uri)->assertRedirect('/login');
    }
});

test('authenticated users can visit every hotel module page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $uris = [
        '/dashboard', '/reservaciones', '/habitaciones', '/clientes',
        '/checkin-checkout', '/limpieza', '/pagos', '/servicios',
        '/gastos', '/empleados', '/temporadas', '/reportes',
        '/roles-permisos', '/configuracion',
    ];

    foreach ($uris as $uri) {
        $this->get($uri)->assertOk();
    }
});

test('the hotel tables exist with their required columns', function () {
    expect(Schema::hasTable('tipos_habitacion'))->toBeTrue()
        ->and(Schema::hasColumns('tipos_habitacion', ['id', 'nombre', 'descripcion', 'precio_base', 'capacidad']))->toBeTrue()
        ->and(Schema::hasColumns('habitaciones', ['id', 'numero_habitacion', 'tipo_habitacion_id', 'estado', 'piso']))->toBeTrue()
        ->and(Schema::hasColumns('clientes', ['id', 'nombre', 'apellido', 'email', 'telefono', 'tipo_identificacion', 'numero_identificacion']))->toBeTrue()
        ->and(Schema::hasColumns('reservas', ['id', 'cliente_id', 'user_id', 'check_in', 'check_out', 'estado', 'monto_total']))->toBeTrue()
        ->and(Schema::hasColumns('reserva_habitacion', ['id', 'reserva_id', 'habitacion_id', 'precio_por_noche']))->toBeTrue()
        ->and(Schema::hasColumns('pagos', ['id', 'reserva_id', 'monto', 'metodo_pago', 'fecha_pago', 'notas']))->toBeTrue()
        ->and(Schema::hasColumns('servicios', ['id', 'nombre', 'precio']))->toBeTrue()
        ->and(Schema::hasColumns('reserva_servicio', ['id', 'reserva_id', 'servicio_id', 'cantidad', 'subtotal']))->toBeTrue()
        ->and(Schema::hasColumns('limpieza', ['id', 'habitacion_id', 'user_id', 'estado', 'notas']))->toBeTrue()
        ->and(Schema::hasColumns('gastos', ['id', 'concepto', 'monto', 'categoria', 'fecha_gasto']))->toBeTrue()
        ->and(Schema::hasColumns('temporadas', ['id', 'nombre', 'fecha_inicio', 'fecha_fin', 'multiplicador_precio']))->toBeTrue();
});

test('the seeder creates the admin user with the super-admin role and hotel data', function () {
    $this->seed();

    $admin = User::where('email', 'admin@example.com')->first();

    expect($admin)->not->toBeNull()
        ->and($admin->hasRole('super-admin'))->toBeTrue()
        ->and(TipoHabitacion::count())->toBeGreaterThan(0)
        ->and(Habitacion::count())->toBeGreaterThan(0)
        ->and(Cliente::count())->toBeGreaterThan(0);
});

test('a super-admin can create roles from the livewire component', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Livewire::actingAs($admin)
        ->test(RolesPermisos::class)
        ->call('abrirModalCrear')
        ->set('nombre', 'contabilidad')
        ->call('guardarRol')
        ->assertHasNoErrors()
        ->assertSee('creado correctamente');

    expect(Role::where('name', 'contabilidad')->exists())->toBeTrue();
});

test('protected system roles cannot be deleted from the livewire component', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $rol = Role::where('name', 'super-admin')->firstOrFail();

    Livewire::actingAs($admin)
        ->test(RolesPermisos::class)
        ->call('eliminarRol', $rol->id)
        ->assertSee('no puede eliminarse');

    expect(Role::where('name', 'super-admin')->exists())->toBeTrue();
});
