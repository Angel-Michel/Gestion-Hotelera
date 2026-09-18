<?php

use App\Livewire\Empleados;
use App\Livewire\GestionRoles;
use App\Livewire\MatrizPermisos;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('guests are redirected to login on hotel module routes', function () {
    $uris = [
        '/dashboard', '/reservaciones', '/habitaciones', '/clientes',
        '/checkin-checkout', '/limpieza', '/pagos', '/servicios',
        '/gastos', '/empleados', '/temporadas', '/reportes',
        '/roles', '/permisos', '/configuracion',
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

test('only super-admins can access the roles, permissions and employees pages', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $plano = User::factory()->create();
    $this->actingAs($plano);
    $this->get('/roles')->assertForbidden();
    $this->get('/permisos')->assertForbidden();
    $this->get('/empleados')->assertForbidden();

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    $this->actingAs($admin);
    $this->get('/roles')->assertOk();
    $this->get('/permisos')->assertOk();
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
        ->assertSee('Roles')
        ->assertSee('Permisos')
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
        ->assertDontSee('Roles')
        ->assertDontSee('Permisos')
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
        ->test(GestionRoles::class)
        ->call('abrirModalCrear')
        ->set('nombre', 'contabilidad')
        ->call('guardarRol')
        ->assertHasNoErrors()
        ->assertSee('creado correctamente');

    expect(Role::where('name', 'contabilidad')->exists())->toBeTrue();
});

test('a super-admin can create a role with initial permissions assigned', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $permiso = Permission::findByName('reportes.ver');

    Livewire::actingAs($admin)
        ->test(GestionRoles::class)
        ->call('abrirModalCrear')
        ->set('nombre', 'auditor')
        ->set('permisosSeleccionados', [(string) $permiso->id])
        ->call('guardarRol')
        ->assertHasNoErrors();

    expect(Role::findByName('auditor')->hasPermissionTo('reportes.ver'))->toBeTrue();
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

test('employees can be created with a system account, hashed password and role', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('crear')
        ->set('nombre', 'Jorge')
        ->set('apellidos', 'Ramos')
        ->set('correo_electronico', 'jorge.ramos@example.com')
        ->set('contrasena', 'secret123')
        ->set('rol', 'recepcionista')
        ->set('telefono', '5512345678')
        ->set('puesto', 'Recepcionista')
        ->set('salario', '12500.00')
        ->set('turno', 'Tarde')
        ->set('acceso_sistema', true)
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertSee('creado correctamente');

    $usuario = User::where('email', 'jorge.ramos@example.com')->first();
    $empleado = Empleado::where('id_usuario', $usuario->id)->first();

    expect($usuario)->not->toBeNull()
        ->and($usuario->hasRole('recepcionista'))->toBeTrue()
        ->and(Hash::check('secret123', $usuario->password))->toBeTrue()
        ->and($empleado)->not->toBeNull()
        ->and($empleado->nombre)->toBe('Jorge')
        ->and($empleado->apellidos)->toBe('Ramos')
        ->and($empleado->telefono)->toBe('5512345678')
        ->and($empleado->salario)->toBe('12500.00')
        ->and($empleado->turno)->toBe('Tarde')
        ->and($empleado->esta_activo)->toBeTrue();
});

test('employees without system access are registered without a user account', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('crear')
        ->set('nombre', 'Laura')
        ->set('apellidos', 'Ruiz')
        ->set('correo_electronico', 'laura.ruiz@example.com')
        ->set('acceso_sistema', false)
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertSee('creado correctamente');

    expect(User::where('email', 'laura.ruiz@example.com')->exists())->toBeFalse()
        ->and(Empleado::where('correo_electronico', 'laura.ruiz@example.com')->exists())->toBeTrue();
});

test('only the super-admin can create employees', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $recepcionista = User::factory()->create();
    $recepcionista->assignRole('recepcionista');

    Livewire::actingAs($recepcionista)
        ->test(Empleados::class)
        ->call('crear')
        ->set('nombre', 'Pedro')
        ->set('apellidos', 'Sosa')
        ->set('correo_electronico', 'pedro.sosa@example.com')
        ->set('acceso_sistema', false)
        ->call('guardar')
        ->assertStatus(403);

    expect(Empleado::where('correo_electronico', 'pedro.sosa@example.com')->exists())->toBeFalse();
});

test('the employee form excludes the super-admin role from the dropdown', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('crear')
        ->assertDontSee('value="super-admin"');

    expect(Role::where('name', 'super-admin')->exists())->toBeTrue();
});

test('employees cannot be created with the super-admin role', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('crear')
        ->set('nombre', 'Iván')
        ->set('apellidos', 'Soto')
        ->set('correo_electronico', 'ivan.soto@example.com')
        ->set('contrasena', 'secret123')
        ->set('rol', 'super-admin')
        ->set('acceso_sistema', true)
        ->call('guardar')
        ->assertHasErrors(['rol']);

    expect(User::where('email', 'ivan.soto@example.com')->exists())->toBeFalse()
        ->and(Empleado::where('correo_electronico', 'ivan.soto@example.com')->exists())->toBeFalse();
});

test('employees can be created as inactive and with a valid shift', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('crear')
        ->set('nombre', 'Carla')
        ->set('apellidos', 'Núñez')
        ->set('correo_electronico', 'carla.nunez@example.com')
        ->set('turno', 'Noche')
        ->set('esta_activo', '0')
        ->set('acceso_sistema', false)
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertSee('creado correctamente');

    $empleado = Empleado::where('correo_electronico', 'carla.nunez@example.com')->first();

    expect($empleado)->not->toBeNull()
        ->and($empleado->turno)->toBe('Noche')
        ->and($empleado->esta_activo)->toBeFalse();
});

test('the employee form only accepts the predefined shifts', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('crear')
        ->set('nombre', 'Roberto')
        ->set('apellidos', 'Díaz')
        ->set('correo_electronico', 'roberto.diaz@example.com')
        ->set('turno', '8:00 - 16:00')
        ->set('acceso_sistema', false)
        ->call('guardar')
        ->assertHasErrors(['turno']);

    expect(Empleado::where('correo_electronico', 'roberto.diaz@example.com')->exists())->toBeFalse();
});

test('a super-admin can edit the employee personal data and its system role', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $usuario = User::factory()->create(['email' => 'mario.lopez@example.com']);
    $usuario->assignRole('recepcionista');

    $empleado = Empleado::create([
        'nombre' => 'Mario',
        'apellidos' => 'López',
        'correo_electronico' => 'mario.lopez@example.com',
        'id_usuario' => $usuario->id,
        'puesto' => 'Recepcionista',
        'telefono' => '5512345678',
        'turno' => 'Tarde',
        'salario' => 9500.50,
    ]);

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('editar', $empleado->id_empleado)
        ->set('puesto', 'Jefa de Recepción')
        ->set('telefono', '5598765432')
        ->set('turno', 'Noche')
        ->set('salario', '11000.00')
        ->set('rol', 'gerente')
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertSee('actualizado correctamente');

    $empleado->refresh();

    expect($empleado->puesto)->toBe('Jefa de Recepción')
        ->and($empleado->telefono)->toBe('5598765432')
        ->and($empleado->turno)->toBe('Noche')
        ->and($empleado->salario)->toBe('11000.00')
        ->and($usuario->fresh()->hasRole('gerente'))->toBeTrue();
});

test('editing an existing super-admin employee preserves their super-admin role', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $usuario = User::factory()->create(['email' => 'sa.lopez@example.com']);
    $usuario->assignRole('super-admin');

    $empleado = Empleado::create([
        'nombre' => 'Saúl',
        'apellidos' => 'López',
        'correo_electronico' => 'sa.lopez@example.com',
        'id_usuario' => $usuario->id,
        'puesto' => 'Administrador',
    ]);

    Livewire::actingAs($admin)
        ->test(Empleados::class)
        ->call('editar', $empleado->id_empleado)
        ->assertSet('rol', '')
        ->set('turno', 'Rotativo')
        ->call('guardar')
        ->assertHasNoErrors();

    expect($usuario->fresh()->hasRole('super-admin'))->toBeTrue();
});

test('protected system roles cannot be deleted from the livewire component', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $rol = Role::where('name', 'super-admin')->firstOrFail();

    Livewire::actingAs($admin)
        ->test(GestionRoles::class)
        ->call('eliminarRol', $rol->id)
        ->assertSee('no puede eliminarse');

    expect(Role::where('name', 'super-admin')->exists())->toBeTrue();
});

test('only the super-admin can toggle permissions in the matrix', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $recepcionista = User::factory()->create();
    $recepcionista->assignRole('recepcionista');

    $rol = Role::findByName('recepcionista');
    $permiso = Permission::findByName('reportes.ver');

    Livewire::actingAs($recepcionista)
        ->test(MatrizPermisos::class)
        ->call('alternarPermiso', $rol->id, $permiso->id)
        ->assertStatus(403);

    expect($rol->fresh()->hasPermissionTo('reportes.ver'))->toBeFalse();
});

test('a super-admin can assign a permission to a non-system role in the matrix', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $rol = Role::findByName('recepcionista');
    $permiso = Permission::findByName('reportes.ver');

    Livewire::actingAs($admin)
        ->test(MatrizPermisos::class)
        ->call('alternarPermiso', $rol->id, $permiso->id)
        ->assertHasNoErrors()
        ->assertSee('asignado');

    expect($rol->fresh()->hasPermissionTo('reportes.ver'))->toBeTrue();

    Livewire::actingAs($admin)
        ->test(MatrizPermisos::class)
        ->call('alternarPermiso', $rol->id, $permiso->id)
        ->assertSee('retirado');

    expect($rol->fresh()->hasPermissionTo('reportes.ver'))->toBeFalse();
});

test('the matrix never modifies the immutable super-admin role', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $rol = Role::findByName('super-admin');
    $permiso = Permission::findByName('reportes.ver');

    Livewire::actingAs($admin)
        ->test(MatrizPermisos::class)
        ->call('alternarPermiso', $rol->id, $permiso->id)
        ->assertSee('no pueden modificarse');

    expect($rol->fresh()->hasPermissionTo('reportes.ver'))->toBeTrue();
});

test('the sidebar displays the Novastay wordmark', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Novastay');
});

test('the sidebar displays the system wordmark', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Gestion Hotelera'); 
});