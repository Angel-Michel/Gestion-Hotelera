<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habitacion extends Model
{
    protected $table = 'habitaciones';

    protected $fillable = [
        'numero_habitacion',
        'tipo_habitacion_id',
        'estado',
        'piso',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'piso' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<TipoHabitacion, $this>
     */
    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoHabitacion::class, 'tipo_habitacion_id');
    }

    /**
     * @return HasMany<ReservaHabitacion, $this>
     */
    public function reservasHabitacion(): HasMany
    {
        return $this->hasMany(ReservaHabitacion::class, 'habitacion_id');
    }

    /**
     * @return BelongsToMany<Reserva>
     */
    public function reservas(): BelongsToMany
    {
        return $this->belongsToMany(Reserva::class, 'reserva_habitacion', 'habitacion_id', 'reserva_id')
            ->withPivot('precio_por_noche')
            ->withTimestamps();
    }

    /**
     * @return HasMany<Limpieza, $this>
     */
    public function tareasLimpieza(): HasMany
    {
        return $this->hasMany(Limpieza::class, 'habitacion_id');
    }
}
