{{-- ======================================================
     VISTA: EDICIÓN DE SERVICIO

     Modal de edición. `FormModal::render()` elige esta vista
     cuando hay un servicio cargado en el formulario.
     ====================================================== --}}

@include('servicios.partials.form', ['modo' => 'editar'])
