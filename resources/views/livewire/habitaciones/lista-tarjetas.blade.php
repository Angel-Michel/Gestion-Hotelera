{{-- ======================================================
     ITERACIÓN DEL INVENTARIO

     parcial anónimo: no tiene componente Livewire propio.
     El contenedor `habitaciones.index` lo incluye con @include y le pasa las
     variables que necesita, de modo que la rejilla no crece dentro de la vista
     del contenedor. La relación `tipoHabitacion` ya viene cargada por Eager
     Loading en Index::habitaciones(), así que ninguna tarjeta dispara una
     consulta extra por habitación.
     --}}

@foreach ($habitaciones as $habitacion)
    <livewire:habitaciones.habitacion-card
        :habitacion="$habitacion"
        :variante="$variante"
        :wire:key="$claveTarjeta($habitacion)"
    />
@endforeach
