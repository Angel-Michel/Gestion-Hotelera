<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'check_in',
        'check_out',
        'estado',
        'monto_total',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'monto_total' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Cliente, $this>
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<ReservaHabitacion, $this>
     */
    public function habitacionesAsignadas(): HasMany
    {
        return $this->hasMany(ReservaHabitacion::class, 'reserva_id');
    }

    /**
     * @return BelongsToMany<Habitacion>
     */
    public function habitaciones(): BelongsToMany
    {
        return $this->belongsToMany(Habitacion::class, 'reserva_habitacion', 'reserva_id', 'habitacion_id')
            ->withPivot('precio_por_noche')
            ->withTimestamps();
    }

    /**
     * @return HasMany<ReservaServicio, $this>
     */
    public function serviciosAsignados(): HasMany
    {
        return $this->hasMany(ReservaServicio::class, 'reserva_id');
    }

    /**
     * @return HasMany<Pago, $this>
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'reserva_id');
    }
}
