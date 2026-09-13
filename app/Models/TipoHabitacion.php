<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoHabitacion extends Model
{
    protected $table = 'tipos_habitacion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_base',
        'capacidad',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio_base' => 'decimal:2',
            'capacidad' => 'integer',
        ];
    }

    /**
     * @return HasMany<Habitacion, $this>
     */
    public function habitaciones(): HasMany
    {
        return $this->hasMany(Habitacion::class, 'tipo_habitacion_id');
    }
}
