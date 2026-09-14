<?php

use App\Livewire\Empleados;
use App\Livewire\RolesPermisos;
use App\Models\Cliente;
use App\Models\Empleado;
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

test('authenticated users can visit the general hotel module pages', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $uris = [
        '/dashboard', '/reservaciones', '/habitaciones', '/clientes',
        '/checkin-checkout', '/limpieza', '/pagos', '/servicios',
        '/gastos', '/temporadas', '/reportes', '/configuracion',
    ];

    foreach ($uris as $uri) {
        $this->get($uri)->assertOk();
    }
});

test('only super-admins can access the roles and employees pages', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $plano = User::factory()->create();
    $this->actingAs($plano);
    $this->get('/roles-permisos')->assertForbidden();
    $this->get('/empleados')->assertForbidden();

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    $this->actingAs($admin);
    $this->get('/roles-permisos')->assertOk();
    $this->get('/empleados')->assertOk();
});

test('the sidebar shows every section to the super-admin', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $this->actingAs($admin)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Reservaciones')
        ->assertSee('Reportes')
        ->assertSee('Empleados')
        ->assertSee('Roles y permisos')
        ->assertSee('Configuración');
});

test('the sidebar hides restricted sections from the recepcionista', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $recepcionista = User::factory()->create();
    $recepcionista->assignRole('recepcionista');

    $this->actingAs($recepcionista)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Reservaciones')
        ->assertSee('Clientes')
        ->assertSee('Limpieza')
        ->assertDontSee('Empleados')
        ->assertDontSee('Roles y permisos')
        ->assertDontSee('Reportes')
        ->assertDontSee('Pagos');
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

test('employees can be searched and filtered by status', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Empleado::create([
        'nombre' => 'María', 'apellidos' => 'Torres', 'puesto' => 'Recepcionista', 'esta_activo' => true,
    ]);
    Empleado::create([
        'nombre' => 'Pedro', 'apellidos' => 'Sosa', 'puesto' => 'Mantenimiento', 'esta_activo' => false,
    ]);

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->assertSee('María')
        ->assertSee('Pedro')
        ->set('busqueda', 'Recepcionista')
        ->assertSee('María')
        ->assertDontSee('Pedro')
        ->set('busqueda', '')
        ->set('filtroEstado', 'inactivos')
        ->assertSee('Pedro')
        ->assertDontSee('María');
});

test('employees can be toggled active or inactive', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $empleado = Empleado::create([
        'nombre' => 'Laura', 'apellidos' => 'Vega', 'puesto' => 'Limpieza', 'esta_activo' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('toggleActivo', $empleado->id_empleado)
        ->assertSee('desactivado correctamente');

    expect($empleado->fresh()->esta_activo)->toBeFalse();
});

test('employees can be linked to a user with a spatie role', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $usuario = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('crear')
        ->set('nombre', 'Jorge')
        ->set('apellidos', 'Ramos')
        ->set('puesto', 'Recepcionista')
        ->set('id_usuario', (string) $usuario->id)
        ->set('rol', 'recepcionista')
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertSee('creado correctamente');

    expect($usuario->fresh()->hasRole('recepcionista'))->toBeTrue();
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

test('the sidebar displays the Novastay wordmark', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Novastay');
});

test('room statuses render with consistent labels', function () {
    $this->seed();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/habitaciones')
        ->assertOk()
        ->assertSee('Disponible')
        ->assertSee('Ocupada')
        ->assertSee('Mantenimiento')
        ->assertSee('Limpieza');
});
