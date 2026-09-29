<?php

use App\Livewire\Servicios\Cargos;
use App\Livewire\Servicios\FormModal;
use App\Livewire\Servicios\Index as ServiciosIndex;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\ReservaHabitacion;
use App\Models\ReservaServicio;
use App\Models\Servicio;
use App\Models\TipoHabitacion;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Attributes\Lazy;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('super-admin');

    $this->crearServicio = fn (string $nombre, float $precio, string $categoria = 'Minibar', ?string $descripcion = null): Servicio => Servicio::create([
        'nombre' => $nombre,
        'precio' => $precio,
        'categoria' => $categoria,
        'descripcion' => $descripcion,
    ]);

    $this->crearEmpleado = fn (string $nombre, string $puesto = 'Recepcionista', ?int $usuarioId = null): Empleado => Empleado::create([
        'id_usuario' => $usuarioId,
        'nombre' => $nombre,
        'apellidos' => 'Del Hotel',
        'puesto' => $puesto,
        'salario' => 18000,
        'esta_activo' => true,
    ]);

    /*
     | Reservación con huésped dentro del hotel: es el folio sobre el que se
     | admiten consumos extras. Todas tienen el mismo check-in para que el orden del
     | desplegable solo dependa del identificador.
     */
    $this->crearFolio = function (string $estado = 'Confirmada', float $precioNoche = 899.00): Reserva {
        $usuario = User::factory()->create();
        $usuario->assignRole('cliente');
        $usuarioId = $usuario->id;

        $cliente = Cliente::create([
            'user_id' => $usuarioId,
            'nombre' => 'Ana',
            'apellido' => 'López',
            'email' => 'ana'.uniqid().'@hotel.com',
            'tipo_identificacion' => 'RFC',
            'numero_identificacion' => 'LOAN'.uniqid(),
        ]);

        $tipo = TipoHabitacion::create([
            'nombre' => 'Estándar '.uniqid(),
            'precio_base' => $precioNoche,
            'capacidad' => 2,
        ]);

        $habitacion = Habitacion::create([
            'numero_habitacion' => (string) random_int(100, 999),
            'tipo_habitacion_id' => $tipo->id,
            'estado' => $estado === 'Confirmada' ? 'Ocupada' : 'Disponible',
            'piso' => 1,
        ]);

        $reserva = Reserva::create([
            'cliente_id' => $cliente->id,
            'user_id' => $usuarioId,
            'check_in' => now()->subDays(2)->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'estado' => $estado,
            'monto_total' => $precioNoche * 4,
        ]);

        ReservaHabitacion::create([
            'reserva_id' => $reserva->id,
            'habitacion_id' => $habitacion->id,
            'precio_por_noche' => $precioNoche,
        ]);

        return $reserva->fresh();
    };

    $this->cargarCargo = fn (Reserva $reserva, Servicio $servicio, Empleado $empleado, int $cantidad = 1, ?float $precio = null): ReservaServicio => ReservaServicio::create([
        'reserva_id' => $reserva->id,
        'servicio_id' => $servicio->id,
        'cantidad' => $cantidad,
        'precio_aplicado' => $precio ?? (float) $servicio->precio,
        'empleado_id' => $empleado->id_empleado,
        'subtotal' => round(($precio ?? (float) $servicio->precio) * $cantidad, 2),
    ]);
});

test('el catalogo y los cargos al folio guardan los datos del modulo', function () {
    expect(Schema::hasTable('servicios'))->toBeTrue()
        ->and(Schema::hasColumns('servicios', ['id', 'nombre', 'descripcion', 'categoria', 'precio']))->toBeTrue()
        ->and(Schema::hasColumns('reserva_servicio', ['id', 'reserva_id', 'servicio_id', 'cantidad', 'precio_aplicado', 'empleado_id', 'subtotal']))->toBeTrue()
        ->and(Schema::hasColumn('reserva_servicio', 'empleado_id'))->toBeTrue();
});

test('el modulo esta encarpetado en servicios con vistas separadas por operacion', function () {
    $base = resource_path('views/servicios');

    foreach (['index', 'create', 'edit'] as $vista) {
        expect(File::exists($base.'/'.$vista.'.blade.php'))->toBeTrue();
    }

    foreach (['header', 'table', 'form', 'cargo-form', 'cargos-table'] as $parcial) {
        expect(File::exists($base.'/partials/'.$parcial.'.blade.php'))->toBeTrue();
    }

    // El catálogo y los cargos son archivos independientes: el formulario del
    // catálogo no arrastra la lógica de cargos ni al revés.
    expect(File::get($base.'/partials/form.blade.php'))
        ->toContain('wire:model="nombre"')
        ->not->toContain('reserva_id');

    expect(File::get($base.'/partials/cargo-form.blade.php'))
        ->toContain('wire:model="reserva_id"')
        ->toContain('wire:model="empleado_id"')
        ->not->toContain('wire:model="descripcion"');

    // El contenedor delega tabla y cargos en sus parciales y monta los diálogos.
    expect(File::get($base.'/index.blade.php'))
        ->toContain("@include('servicios.partials.header')")
        ->toContain("@include('servicios.partials.table')")
        ->toContain("@include('servicios.partials.cargos-table')")
        ->toContain('livewire:servicios.form-modal')
        ->toContain('livewire:servicios.cargos');

    foreach (['Index', 'FormModal', 'Cargos'] as $componente) {
        expect(File::exists(app_path('Livewire/Servicios/'.$componente.'.php')))->toBeTrue();
    }
});

test('el contenedor lista el catalogo con los kpis los filtros y el alta', function () {
    ($this->crearServicio)('Minibar premium', 350.00, 'Minibar', 'Vinos y snacks.');

    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->assertOk()
        ->assertSee('Servicios')
        ->assertSee('1 servicio en catálogo')
        ->assertSee('Minibar premium')
        ->assertSee('Vinos y snacks.')
        ->assertSee('Minibar')
        ->assertSee('$350.00')
        // KPIs de la operación.
        ->assertSee('En catálogo')
        ->assertSee('Categorías')
        ->assertSee('Cargos registrados')
        ->assertSee('Consumos facturados')
        // Filtros.
        ->assertSee('Buscar por nombre o descripción...')
        ->assertSee('Todas las categorías');
});

test('la busqueda del catalogo cubre el nombre y la descripcion', function () {
    ($this->crearServicio)('Minibar premium', 350.00, 'Minibar', 'Vinos y snacks.');
    ($this->crearServicio)('Lavado exprés', 120.00, 'Lavandería', 'Camisetas y trajes.');

    $componente = Livewire::actingAs($this->admin)->test(ServiciosIndex::class);

    $nombres = fn (): array => $componente->instance()->servicios->pluck('nombre')->all();

    expect($nombres())->toBe(['Lavado exprés', 'Minibar premium']);

    $componente->set('search', 'Minibar');
    expect($nombres())->toBe(['Minibar premium']);

    // Busca por descripción, no solo por nombre.
    $componente->set('search', 'snacks');
    expect($nombres())->toBe(['Minibar premium']);

    $componente->set('search', '');
    expect($nombres())->toBe(['Lavado exprés', 'Minibar premium']);
});

test('el filtro por categoria recorta el catalogo y vuelve con el total', function () {
    ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    ($this->crearServicio)('Cena degustación', 900.00, 'Restaurante');

    $componente = Livewire::actingAs($this->admin)->test(ServiciosIndex::class);

    $componente->call('filtrarPorCategoria', 'Restaurante')
        ->assertSet('filtroCategoria', 'Restaurante')
        ->assertSee('Cena degustación')
        ->assertDontSee('Minibar premium')
        ->assertSee('Mostrando 1 servicio de 2');

    $componente->call('filtrarPorCategoria', ServiciosIndex::FILTRO_TODAS)
        ->assertSet('filtroCategoria', ServiciosIndex::FILTRO_TODAS)
        ->assertSee('Minibar premium')
        ->assertSee('Cena degustación')
        ->assertDontSee('Mostrando 1 servicio de 2');
});

test('una categoria desconocida vuelve al total sin romper la consulta', function () {
    ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');

    $componente = Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->call('filtrarPorCategoria', 'categoria-inexistente');

    expect($componente->get('filtroCategoria'))->toBe(ServiciosIndex::FILTRO_TODAS)
        ->and($componente->instance()->servicios->pluck('nombre')->all())->toBe(['Minibar premium']);
});

test('las tarjetas de categoria solo ofrecen las que tienen servicios', function () {
    ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    ($this->crearServicio)('Cena degustación', 900.00, 'Restaurante');
    ($this->crearServicio)('Lavado exprés', 120.00, 'Lavandería');

    $conteo = Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->instance()
        ->conteoPorCategoria();

    expect($conteo)->toBe(['Minibar' => 1, 'Restaurante' => 1, 'Lavandería' => 1])
        ->and($conteo)->not->toHaveKey('Transporte');
});

test('el catalogo se pagina de diez en diez servicios', function () {
    foreach (range(1, 12) as $indice) {
        ($this->crearServicio)('Servicio '.str_pad((string) $indice, 2, '0', STR_PAD_LEFT), 100.00);
    }

    $componente = Livewire::actingAs($this->admin)->test(ServiciosIndex::class);

    expect(substr_count($componente->html(), 'aria-label="Editar servicio"'))
        ->toBe(ServiciosIndex::POR_PAGINA)
        ->and($componente->html())->toContain('Servicio 01')
        ->and($componente->html())->not->toContain('Servicio 12');

    $componente->call('nextPage');

    expect($componente->html())->toContain('Servicio 12');
});

test('el contenedor no lleva estado de visibilidad y solo enruta el formulario', function () {
    $propiedades = (new ReflectionClass(ServiciosIndex::class))->getProperties();

    // Ninguna propiedad booleana controla la visibilidad: el <dialog> del
    // catálogo lo abre el propio formulario con `modal-show` una vez cargados
    // los datos, y el de cargos Flux desde el navegador.
    expect(array_map(fn ($p) => $p->getName(), $propiedades))
        ->not->toContain('mostrarModal')
        ->not->toContain('isOpenEditModal')
        ->not->toContain('servicioId')
        ->and(array_filter($propiedades, fn ($p) => $p->getType()?->getName() === 'bool'))->toBeEmpty();

    // `crear` y `editar` no preparan nada: solo avisan al formulario, que es el
    // dueño de los datos y de la apertura.
    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->call('crear')
        ->assertDispatched('servicio-crear')
        ->assertSet('mensajeExito', null);
});

test('el contenedor enruta la edicion al formulario con el identificador de la fila', function () {
    $servicio = ($this->crearServicio)('Lavado exprés', 120.50, 'Lavandería');

    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->call('editar', $servicio->id)
        ->assertDispatched('servicio-editar', id: $servicio->id);
});

test('el dialogo del catalogo lo abre el servidor y el de cargos Flux en el navegador', function () {
    $html = Livewire::actingAs($this->admin)->test(ServiciosIndex::class)->html();

    // El de cargos no necesita datos del folio: lo abre Alpine.
    expect($html)
        ->toContain("\$dispatch('modal-show', { name: 'servicio-cargo' })")
        ->toContain('novastay-servicio-modal')
        ->toMatch('/<dialog[^>]*class="[^"]*bg-transparent[^"]*"/s')
        ->toContain('<div wire:ignore>')
        ->and($html)->not->toContain('wire:model="mostrarModal"')
        // El del catálogo ya no se abre desde el navegador: lo abre el
        // formulario cuando ya trae los datos del servicio.
        ->and($html)->not->toContain("\$dispatch('modal-show', { name: 'servicio-form' })")
        ->and($html)->toContain('wire:click="crear"')
        ->and($html)->toContain("\$dispatch('modal-close', { name: 'servicio-form' })");
});

test('el formulario del catalogo vive dentro del dialogo sin carga diferida', function () {
    ($this->crearServicio)('Minibar premium', 350.00);

    $html = Livewire::actingAs($this->admin)->test(ServiciosIndex::class)->html();

    $dialogo = Str::between($html, 'data-modal="servicio-form"', 'data-modal="servicio-cargo"');

    /*
     | Un componente `#[Lazy]` descarta los eventos dirigidos a él antes de
     | cargarse, así que el primer clic sobre una fila abría el formulario de
     | alta. El del catálogo se monta con la pantalla; el de cargos sí sigue
     | diferido, porque no recibe datos del folio y no tiene eventos propios.
     */
    expect($dialogo)
        ->toContain('Agregar servicio')
        ->not->toContain('__lazyLoad')
        ->and((new ReflectionClass(FormModal::class))->getAttributes(Lazy::class))->toBeEmpty()
        ->and((new ReflectionClass(Cargos::class))->getAttributes(Lazy::class))->not->toBeEmpty();
});

test('el cristal de fondo de los modales vive en la hoja de estilos', function () {
    $css = file_get_contents(resource_path('css/app.css'));

    expect($css)->toContain('.novastay-servicio-modal::backdrop')
        ->toContain('backdrop-filter: blur(4px)')
        ->toContain('rgb(15 23 42 / 0.6)')
        ->toContain('transition: opacity 300ms');
});

test('la tabla del catalogo usa la paleta neutra con acento amber y bordes sutiles', function () {
    ($this->crearServicio)('Minibar premium', 350.00, 'Minibar', 'Vinos y snacks.');

    $html = Livewire::actingAs($this->admin)->test(ServiciosIndex::class)->html();

    // Tarjeta con sombra limpia y esquinas redondeadas.
    expect($html)->toContain('rounded-2xl bg-white shadow-xl shadow-slate-950/5 ring-1 ring-slate-900/5')
        // Etiqueta de categoría en ámbar.
        ->toContain('bg-amber-50')
        ->toContain('text-amber-800')
        ->toContain('ring-amber-200/70')
        // Encabezados neutros.
        ->toContain('text-[10px] font-bold uppercase tracking-wider text-slate-400')
        ->toContain('bg-slate-50/80')
        // Interacción de las acciones.
        ->toContain('hover:-translate-y-0.5')
        ->toContain('active:scale-95');
});

test('cada fila de la tabla abre el formulario con su propio identificador', function () {
    $servicio = ($this->crearServicio)('Minibar premium', 350.00);

    $html = Livewire::actingAs($this->admin)->test(ServiciosIndex::class)->html();

    expect($html)
        ->toContain('wire:click="editar('.$servicio->id.')')
        ->toContain('wire:target="editar('.$servicio->id.')')
        ->toContain('wire:click="eliminar('.$servicio->id.')')
        ->toContain('wire:confirm="¿Eliminar este servicio del catálogo?"')
        ->and(substr_count($html, 'aria-label="Editar servicio"'))->toBe(1);
});

test('el formulario del modal se presenta como una tarjeta premium con los campos del sistema', function () {
    Livewire::withoutLazyLoading();

    $html = Livewire::actingAs($this->admin)->test(FormModal::class)->html();

    expect($html)->toContain('Nuevo servicio')
        ->toContain('animate-modal-card-in')
        ->toContain('rounded-2xl')
        ->toContain('shadow-2xl')
        // Campos sin bordes duros, con fondo sutil y anillo ámbar al enfocar.
        ->toContain('bg-slate-50')
        ->toContain('hover:bg-slate-100')
        ->toContain('focus:ring-2')
        ->toContain('focus:ring-amber-500')
        // Iconos dentro de los campos.
        ->toContain('peer-focus:text-amber-500')
        // Botones: elevación al pasar el cursor y presión al hacer clic.
        ->toContain('hover:-translate-y-0.5')
        ->toContain('active:scale-95')
        // Cierre en el navegador, sin viaje al servidor.
        ->toContain("\$dispatch('modal-close', { name: 'servicio-form' })")
        ->and($html)->not->toContain('wire:click="cerrar"');
});

test('los campos con icono reservan el hueco con pl-11 y el desplegable con pl-10', function () {
    Livewire::withoutLazyLoading();

    $html = Livewire::actingAs($this->admin)->test(FormModal::class)->html();

    // Los tres campos de texto con icono: nombre, precio y descripción.
    expect(substr_count($html, 'pl-11 pr-3.5'))->toBe(3)
        ->and($html)->toContain('absolute left-3.5 top-1/2 size-5')
        // El desplegable de categoría lleva su propio `pl-10`.
        ->and(substr_count($html, 'focus:ring-amber-500 pl-10'))->toBe(1)
        // Sin select nativo visible.
        ->and($html)->toContain('class="sr-only"')
        ->not->toContain('appearance-none');
});

test('los desplegables del formulario son menus redondeados con sombra limpia', function () {
    Livewire::withoutLazyLoading();

    $html = Livewire::actingAs($this->admin)->test(FormModal::class)->html();

    expect($html)->toContain('aria-haspopup="listbox"')
        ->toContain('role="listbox"')
        ->toContain('role="option"')
        // `id` y etiqueta apuntan al botón real, no al select invisible.
        ->toContain('id="categoria"')
        ->toContain('id="nombre"')
        // Menú flotante: esquinas redondeadas, borde sutil y sombra elegante.
        ->toContain('rounded-xl border border-slate-200/80 bg-white p-1.5 shadow-xl shadow-slate-950/5')
        ->toContain('x-transition:enter')
        ->toContain('x-transition:leave')
        // Hover neutro y realce amber en la opción elegida.
        ->toContain('hover:bg-slate-100')
        ->toContain('bg-amber-50');
});

test('el formulario pide nombre categoria y precio y deja la descripcion como opcional', function () {
    Livewire::withoutLazyLoading();

    $html = Livewire::actingAs($this->admin)->test(FormModal::class)->html();

    $asterisco = '<span class="text-red-500 font-extrabold text-sm ml-0.5">*</span>';

    expect(substr_count($html, $asterisco))->toBe(3)
        ->and($html)->toContain('Nombre '.$asterisco)
        ->and($html)->toContain('Categoría '.$asterisco)
        ->toContain('Precio (MXN) '.$asterisco)
        ->toContain('>Descripción<');
});

test('el formulario no pide al servidor con cada tecla y bloquea el doble clic', function () {
    Livewire::withoutLazyLoading();

    $html = Livewire::actingAs($this->admin)->test(FormModal::class)->html();

    foreach (['nombre', 'categoria', 'precio', 'descripcion'] as $campo) {
        expect($html)->toContain('wire:model="'.$campo.'"');
    }

    expect($html)->not->toContain('wire:model.live=')
        ->and($html)->not->toContain('wire:model.blur=')
        ->toContain('wire:loading.attr="disabled"')
        ->toContain('wire:target="guardar"')
        ->toContain('Guardando...');
});

test('el desplegable de categorias sigue enlazado al estado de Livewire', function () {
    Livewire::withoutLazyLoading();

    $componente = Livewire::actingAs($this->admin)->test(FormModal::class);

    // El <select> oculto sigue siendo el que lleva el enlace con el componente.
    $componente
        ->assertSeeHtml('wire:model="categoria"')
        ->assertSet('categoria', Servicio::CATEGORIA_POR_DEFECTO)
        ->set('categoria', 'Lavandería')
        ->assertSee('Lavandería')
        // Todas las categorías del enumerado se ofrecen como opciones del menú.
        ->assertSee('Spa y Bienestar');
});

test('el alta de un servicio guarda nombre descripcion categoria y precio', function () {
    Livewire::withoutLazyLoading();

    Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->set('nombre', 'Lavado exprés')
        ->set('descripcion', 'Camisetas y trajes, entrega en 4 horas.')
        ->set('categoria', 'Lavandería')
        ->set('precio', '120.50')
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertDispatched('servicio-guardada', mensaje: 'Servicio creado correctamente.');

    $servicio = Servicio::where('nombre', 'Lavado exprés')->firstOrFail();

    expect($servicio->descripcion)->toBe('Camisetas y trajes, entrega en 4 horas.')
        ->and($servicio->categoria)->toBe('Lavandería')
        ->and((float) $servicio->precio)->toBe(120.50);
});

test('el formulario actualiza el servicio en edicion y avisa al contenedor', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar', 'Vinos y snacks.');

    Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->call('editar', $servicio->id)
        ->assertSet('nombre', 'Minibar premium')
        ->assertSet('categoria', 'Minibar')
        ->assertSet('precio', '350.00')
        ->assertSet('descripcion', 'Vinos y snacks.')
        ->assertSee('Editar servicio')
        ->assertSee('Guardar cambios')
        ->set('precio', '399.00')
        ->set('categoria', 'Restaurante')
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertDispatched('servicio-guardada', mensaje: 'Servicio actualizado correctamente.');

    expect($servicio->fresh()->categoria)->toBe('Restaurante')
        ->and((float) $servicio->fresh()->precio)->toBe(399.00);
});

test('un solo clic en la fila deja el formulario abierto con los datos del servicio', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Lavado exprés', 120.50, 'Lavandería', 'Camisetas y trajes.');

    // Es el camino que sigue el botón de la fila: una llamada, y el formulario
    // ya está cargado y con el diálogo pedido. Antes, el evento se perdía por
    // `#[Lazy]` y el diálogo se abría con el formulario de alta.
    $componente = Livewire::actingAs($this->admin)->test(FormModal::class);

    expect($componente->get('nombre'))->toBe('')
        ->and($componente->get('isOpenEditModal'))->toBeFalse();

    $componente->call('editar', $servicio->id)
        ->assertSet('servicioId', $servicio->id)
        ->assertSet('isOpenEditModal', true)
        ->assertSet('nombre', 'Lavado exprés')
        ->assertSet('categoria', 'Lavandería')
        ->assertSet('precio', '120.50')
        ->assertSet('descripcion', 'Camisetas y trajes.')
        ->assertDispatched('modal-show', name: 'servicio-form')
        ->assertSee('Editar servicio')
        ->assertSee('Guardar cambios');

    // Y la categoría que se acaba de abrir es la que se guarda si se vuelve a
    // enviar sin tocarla.
    $componente->call('guardar')
        ->assertHasNoErrors();

    expect($servicio->fresh()->categoria)->toBe('Lavandería');
});

test('el alta se abre vacia desde el mismo camino que el boton nuevo servicio', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar', 'Vinos.');

    $componente = Livewire::actingAs($this->admin)->test(FormModal::class)
        ->call('editar', $servicio->id)
        ->assertSet('nombre', 'Minibar premium');

    $componente->set('nombre', 'Otro')->call('crear')
        ->assertSet('servicioId', null)
        ->assertSet('isOpenEditModal', true)
        ->assertSet('nombre', '')
        ->assertSet('precio', '')
        ->assertSet('categoria', Servicio::CATEGORIA_POR_DEFECTO)
        ->assertDispatched('modal-show', name: 'servicio-form')
        ->assertSee('Nuevo servicio')
        ->assertSee('Agregar servicio');
});

test('tras guardar el formulario vuelve al modo alta con el dialogo cerrado', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');

    Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->call('editar', $servicio->id)
        ->set('categoria', 'Spa y Bienestar')
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertSet('isOpenEditModal', false)
        ->assertSet('servicioId', null)
        ->assertSet('categoria', Servicio::CATEGORIA_POR_DEFECTO)
        ->assertSee('Nuevo servicio');
});

test('el formulario rechaza una categoria ajena al enumerado del hotel', function () {
    Livewire::withoutLazyLoading();

    Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->set('nombre', 'Servicio raro')
        ->set('categoria', 'Helipuerto')
        ->set('precio', '10.00')
        ->call('guardar')
        ->assertHasErrors(['categoria']);

    expect(Servicio::where('nombre', 'Servicio raro')->exists())->toBeFalse();
});

test('el formulario rechaza un nombre duplicado y un precio invalido', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Minibar premium', 350.00);

    Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->set('nombre', 'Minibar premium')
        ->set('precio', '350.00')
        ->call('guardar')
        ->assertHasErrors(['nombre']);

    // En edición el propio servicio no cuenta como duplicado.
    Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->call('editar', $servicio->id)
        ->set('precio', '0')
        ->call('guardar')
        ->assertHasErrors(['precio']);

    expect((float) $servicio->fresh()->precio)->toBe(350.00);
});

test('el contenedor muestra el mensaje y cierra el modal tras guardar', function () {
    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->dispatch('servicio-guardada', mensaje: 'Servicio creado correctamente.')
        ->assertDispatched('modal-close', name: 'servicio-form')
        ->assertSet('mensajeExito', 'Servicio creado correctamente.')
        ->assertSee('Servicio creado correctamente.');
});

test('el contenedor elimina un servicio que todavia no tiene cargos', function () {
    $servicio = ($this->crearServicio)('Minibar premium', 350.00);

    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->call('eliminar', $servicio->id)
        ->assertSet('mensajeExito', 'Servicio eliminado correctamente.')
        ->assertDontSee('Minibar premium');

    expect(Servicio::whereKey($servicio->id)->exists())->toBeFalse();
});

test('un servicio con cargos en folios no se puede eliminar', function () {
    $folio = ($this->crearFolio)();
    $servicio = ($this->crearServicio)('Minibar premium', 350.00);
    ($this->cargarCargo)($folio, $servicio, ($this->crearEmpleado)('Lucía'));

    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->call('eliminar', $servicio->id)
        ->assertSet('mensajeError', 'No se puede eliminar: el servicio ya tiene cargos registrados en folios.')
        ->assertSet('mensajeExito', null)
        ->assertSee('ya tiene cargos registrados en folios');

    expect(Servicio::whereKey($servicio->id)->exists())->toBeTrue();
});

test('el formulario de cargos ofrece solo folios con huesped en el hotel', function () {
    $ocupada = ($this->crearFolio)('Confirmada');
    ($this->crearFolio)('Pendiente');
    ($this->crearFolio)('Finalizada');

    $ids = Livewire::actingAs($this->admin)
        ->test(Cargos::class)
        ->instance()
        ->reservas
        ->pluck('id')
        ->all();

    expect($ids)->toBe([$ocupada->id]);
});

test('el formulario de cargos se presenta con el contrato visual acordado', function () {
    Livewire::withoutLazyLoading();

    ($this->crearFolio)();
    ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    ($this->crearEmpleado)('Lucía');

    $html = Livewire::actingAs($this->admin)->test(Cargos::class)->html();

    expect($html)->toContain('Cargar consumo al folio')
        ->toContain('El cargo se suma de inmediato al total de check-out del huésped.')
        ->toContain('animate-modal-card-in')
        ->toContain('rounded-2xl')
        ->toContain('shadow-2xl')
        // Cantidad y precio aplicado: dos campos con icono y su hueco `pl-11`.
        ->and(substr_count($html, 'pl-11 pr-3.5'))->toBe(2)
        ->and($html)->toContain('absolute left-3.5 top-1/2 size-5')
        // Folio, servicio y empleado: desplegables redondeados con `pl-10`.
        ->and(substr_count($html, 'focus:ring-amber-500 pl-10'))->toBe(3)
        ->and($html)->toContain('rounded-xl border border-slate-200/80 bg-white p-1.5 shadow-xl shadow-slate-950/5')
        // Importe del cargo en curso y acción de guardado protegida.
        ->toContain('Importe del cargo')
        ->toContain('Cargar al folio')
        ->toContain('wire:loading.attr="disabled"')
        ->toContain('Cargando...');
});

test('el formulario de cargos avisa cuando no hay ninguna habitacion ocupada', function () {
    Livewire::withoutLazyLoading();

    $html = Livewire::actingAs($this->admin)->test(Cargos::class)->html();

    expect($html)->toContain('No hay habitaciones ocupadas')
        ->toContain('Los consumos se cargan sobre reservaciones con check-in realizado.')
        ->not->toContain('Selecciona un folio...');
});

test('elegir un servicio propone su precio de catalogo y deja aplicarlo', function () {
    Livewire::withoutLazyLoading();

    $folio = ($this->crearFolio)();
    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');

    Livewire::actingAs($this->admin)
        ->test(Cargos::class)
        ->set('reserva_id', (string) $folio->id)
        ->set('servicio_id', (string) $servicio->id)
        ->assertSet('precio_aplicado', '350.00')
        // El campo sigue siendo editable para aplicar un descuento.
        ->set('precio_aplicado', '300.00')
        ->set('cantidad', '3')
        ->assertSee('$900.00');
});

test('el cargo se guarda en el folio con cantidad precio aplicado y empleado responsable', function () {
    Livewire::withoutLazyLoading();

    $folio = ($this->crearFolio)();
    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    $empleado = ($this->crearEmpleado)('Lucía');

    Livewire::actingAs($this->admin)
        ->test(Cargos::class)
        ->set('reserva_id', (string) $folio->id)
        ->set('servicio_id', (string) $servicio->id)
        ->set('cantidad', '3')
        ->set('precio_aplicado', '300.00')
        ->set('empleado_id', (string) $empleado->id_empleado)
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertDispatched('cargo-registrado', mensaje: 'Consumo de Minibar premium cargado al folio correctamente.');

    $cargo = ReservaServicio::firstOrFail();

    expect($cargo->reserva_id)->toBe($folio->id)
        ->and($cargo->servicio_id)->toBe($servicio->id)
        ->and($cargo->cantidad)->toBe(3)
        ->and((float) $cargo->precio_aplicado)->toBe(300.00)
        ->and($cargo->empleado_id)->toBe($empleado->id_empleado)
        // El subtotal sale del precio aplicado, no del precio de catálogo.
        ->and((float) $cargo->subtotal)->toBe(900.00);
});

test('el cargo se suma al total de check-out de la reservacion', function () {
    Livewire::withoutLazyLoading();

    // Cuatro noches a $899 = $3,596.00 de tarifa.
    $folio = ($this->crearFolio)('Confirmada', 899.00);
    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    $empleado = ($this->crearEmpleado)('Lucía');

    expect($folio->totalNoches())->toBe(4.0)
        ->and($folio->tarifaHabitaciones())->toBe(3596.00)
        ->and($folio->totalConsumos())->toBe(3596.00)
        ->and($folio->saldoPendiente())->toBe(3596.00);

    Livewire::actingAs($this->admin)
        ->test(Cargos::class)
        ->set('reserva_id', (string) $folio->id)
        ->set('servicio_id', (string) $servicio->id)
        ->set('cantidad', '2')
        ->set('precio_aplicado', '350.00')
        ->set('empleado_id', (string) $empleado->id_empleado)
        ->call('guardar')
        ->assertHasNoErrors();

    $folio->refresh();

    expect($folio->subtotalServicios())->toBe(700.00)
        ->and($folio->totalConsumos())->toBe(4296.00)
        ->and($folio->saldoPendiente())->toBe(4296.00);
});

test('los abonos del huesped se descuentan del saldo del folio', function () {
    $folio = ($this->crearFolio)('Confirmada', 899.00);

    $folio->pagos()->create([
        'monto' => 1000.00,
        'metodo_pago' => 'Efectivo',
        'fecha_pago' => now()->toDateString(),
    ]);

    expect($folio->totalPagado())->toBe(1000.00)
        ->and($folio->saldoPendiente())->toBe(2596.00);
});

test('el resumen del formulario refleja el estado de cuenta del folio seleccionado', function () {
    Livewire::withoutLazyLoading();

    $folio = ($this->crearFolio)('Confirmada', 899.00);
    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    $empleado = ($this->crearEmpleado)('Lucía');

    ($this->cargarCargo)($folio, $servicio, $empleado, 2);

    $html = Livewire::actingAs($this->admin)
        ->test(Cargos::class, ['reserva_id' => (string) $folio->id])
        ->html();

    expect($html)
        ->toContain('Tarifa de habitación')
        ->toContain('$3,596.00')
        ->toContain('Consumos extras')
        ->toContain('$700.00')
        ->toContain('Total de check-out')
        ->toContain('$4,296.00')
        ->toContain('Saldo pendiente')
        ->toContain('Cargos del folio')
        ->toContain('Minibar premium')
        ->toContain('Ana López')
        ->toContain('Lucía');
});

test('el formulario de cargos rechaza un folio que no tiene huesped en el hotel', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Minibar premium', 350.00);
    $empleado = ($this->crearEmpleado)('Lucía');
    $fuera = ($this->crearFolio)('Finalizada');

    Livewire::actingAs($this->admin)
        ->test(Cargos::class)
        ->set('reserva_id', (string) $fuera->id)
        ->set('servicio_id', (string) $servicio->id)
        ->set('precio_aplicado', '350.00')
        ->set('empleado_id', (string) $empleado->id_empleado)
        ->call('guardar')
        ->assertHasErrors(['reserva_id']);

    expect(ReservaServicio::count())->toBe(0);
});

test('el formulario de cargos exige cantidad positiva y empleado responsable', function () {
    Livewire::withoutLazyLoading();

    $folio = ($this->crearFolio)();
    $servicio = ($this->crearServicio)('Minibar premium', 350.00);

    Livewire::actingAs($this->admin)
        ->test(Cargos::class)
        ->set('reserva_id', (string) $folio->id)
        ->set('servicio_id', (string) $servicio->id)
        ->set('cantidad', '0')
        ->set('precio_aplicado', '350.00')
        ->call('guardar')
        ->assertHasErrors(['cantidad', 'empleado_id']);

    expect(ReservaServicio::count())->toBe(0);
});

test('el formulario de cargos precarga al empleado de la cuenta con sesion', function () {
    Livewire::withoutLazyLoading();

    $empleado = ($this->crearEmpleado)('Lucía', 'Recepcionista', $this->admin->id);

    expect(Livewire::actingAs($this->admin)->test(Cargos::class)->get('empleado_id'))
        ->toBe((string) $empleado->id_empleado);
});

test('el contenedor cierra el modal de cargos y muestra el mensaje', function () {
    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->dispatch('cargo-registrado', mensaje: 'Consumo de Minibar premium cargado al folio correctamente.')
        ->assertDispatched('modal-close', name: 'servicio-cargo')
        ->assertSet('mensajeExito', 'Consumo de Minibar premium cargado al folio correctamente.');
});

test('la tabla de cargos muestra el folio el huesped y el empleado responsable', function () {
    $folio = ($this->crearFolio)();
    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    $empleado = ($this->crearEmpleado)('Lucía');

    ($this->cargarCargo)($folio, $servicio, $empleado, 2);

    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->assertSee('Cargos al folio')
        ->assertSee('Consumos extras de las habitaciones ocupadas. Se suman al total de check-out.')
        ->assertSee('Ana López')
        ->assertSee('Lucía')
        ->assertSee('$700.00');
});

test('la tabla de cargos avisa cuando todavia no hay ninguno', function () {
    Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->assertSee('Todavía no hay cargos');
});

test('sin permiso de cargos no se ve la seccion ni el dialogo', function () {
    $usuario = User::factory()->create();
    $usuario->givePermissionTo('servicios.ver');

    $html = Livewire::actingAs($usuario)->test(ServiciosIndex::class)->html();

    expect($html)->toContain('Nuevo servicio')
        ->not->toContain('Cargar consumo')
        ->not->toContain('Cargos al folio')
        ->not->toContain('servicio-cargo');
});

test('recepcionista alcanza el modulo y ve la seccion de cargos', function () {
    $recepcionista = User::factory()->create();
    $recepcionista->assignRole('recepcionista');

    expect($recepcionista->can('servicios.cargos'))->toBeTrue();

    $html = Livewire::actingAs($recepcionista)->test(ServiciosIndex::class)->html();

    expect($html)->toContain('Cargar consumo')
        ->toContain('Cargos al folio')
        ->toContain('servicio-cargo');
});

test('el estado de cuenta del huesped refleja el cargo registrado', function () {
    $folio = ($this->crearFolio)('Confirmada', 899.00);
    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    $empleado = ($this->crearEmpleado)('Lucía');

    ($this->cargarCargo)($folio, $servicio, $empleado, 2);

    $this->actingAs(User::findOrFail($folio->user_id))
        ->get(route('mis-reservaciones'))
        ->assertOk()
        ->assertSee('Minibar premium')
        ->assertSee('$700.00');
});

test('la pantalla de servicios carga el catalogo sin una consulta por fila', function () {
    $folio = ($this->crearFolio)();
    $servicio = ($this->crearServicio)('Minibar premium', 350.00, 'Minibar');
    $empleado = ($this->crearEmpleado)('Lucía');

    foreach (range(1, 8) as $indice) {
        ($this->cargarCargo)($folio, $servicio, $empleado, $indice);
    }

    $componente = Livewire::actingAs($this->admin)->test(ServiciosIndex::class);
    $consultas = [];

    DB::listen(function ($query) use (&$consultas): void {
        $consultas[] = $query->sql;
    });

    $componente->call('$refresh');

    // El cliente y la habitación del folio se cargan una sola vez para los ocho
    // cargos, en lugar de una consulta por fila.
    expect(collect($consultas)->filter(fn (string $sql) => str_contains($sql, '"clientes"')))
        ->toHaveCount(1)
        ->and(collect($consultas)->filter(fn (string $sql) => str_contains($sql, '"habitaciones"')))
        ->toHaveCount(1);
});

test('la pantalla de servicios responde al super admin con el catalogo completo', function () {
    $folio = ($this->crearFolio)();
    $servicio = ($this->crearServicio)('Spa relajante', 1200.00, 'Spa y Bienestar', 'Masaje de 60 minutos.');
    ($this->crearEmpleado)('Lucía');

    // Un consumo con precio aplicado para que el folio aparezca en la lista.
    $consumidor = ($this->crearEmpleado)('Mateo');

    ($this->cargarCargo)($folio, $servicio, $consumidor, 1, 350.00);

    $this->actingAs($this->admin)
        ->get(route('servicios'))
        ->assertOk()
        ->assertSee('Spa relajante')
        ->assertSee('Masaje de 60 minutos.')
        ->assertSee('Spa y Bienestar')
        ->assertSee('$1,200.00')
        ->assertSee('Cargar consumo')
        // El folio también aparece en la lista de cargos recientes.
        ->assertSee('Ana López')
        ->assertSee('$350.00');

    expect($folio->serviciosAsignados()->count())->toBe(1);
});

test('el desplegable marca como seleccionada la categoria que tiene guardada el servicio', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Lavado exprés', 120.50, 'Lavandería');

    $html = Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->call('editar', $servicio->id)
        ->assertSet('categoria', 'Lavandería')
        ->html();

    /*
     | El <select> oculto es el que alimenta a `wire:model`. Si ninguna de sus
     | <option> va marcada, el navegador se queda con la primera de la lista
     | ("Minibar") y ese es el valor que viaja al servidor y acaba en la base de
     | datos, por más que el botón muestre otra categoría.
     */
    expect($html)->toContain('<option value="Lavandería" selected>')
        ->and($html)->not->toContain('<option value="Minibar" selected');
});

test('el desplegable se reconstruye con el valor del servidor y no con el que quedo en el boton', function () {
    Livewire::withoutLazyLoading();

    $servicio = ($this->crearServicio)('Lavado exprés', 120.50, 'Lavandería');

    $componente = Livewire::actingAs($this->admin)
        ->test(FormModal::class)
        ->call('editar', $servicio->id);

    // El morph de Livewire conserva el estado de Alpine, así que el botón puede
    // quedarse con la etiqueta anterior mientras el <select> ya vale otra cosa.
    // La raíz del control lleva una clave que depende del valor: cuando este
    // cambia, Livewire reconstruye el desplegable entero.
    expect($componente->html())
        ->toContain('wire:key="categoria-Lavandería"')
        ->and($componente->html())->not->toContain('wire:key="categoria-Minibar"');

    $componente->set('categoria', 'Restaurante');

    expect($componente->html())
        ->toContain('wire:key="categoria-Restaurante"')
        ->toContain('<option value="Restaurante" selected>')
        ->and($componente->html())->not->toContain('<option value="Lavandería" selected>');

    // El botón del desplegable también se pone al día desde el <select> nativo.
    expect($componente->html())->toContain('adoptarValorNativo()');
});

test('el mensaje del contenedor se oculta solo con Alpine', function () {
    $html = Livewire::actingAs($this->admin)
        ->test(ServiciosIndex::class)
        ->dispatch('servicio-guardada', mensaje: 'Servicio creado correctamente.')
        ->assertSet('mensajeExito', 'Servicio creado correctamente.')
        ->html();

    expect($html)->toContain('x-data="{ visible: true }"')
        ->toContain('x-init="setTimeout(() => visible = false, 5000)"')
        ->toContain('x-show="visible"')
        ->toContain('x-transition.duration.300ms');
});
