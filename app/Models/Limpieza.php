<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Limpieza extends Model
{
    protected $table = 'limpieza';

    protected $fillable = [
        'habitacion_id',
        'user_id',
        'estado',
        'notas',
    ];

    /**
     * @return BelongsTo<Habitacion, $this>
     */
    public function habitacion(): BelongsTo
    {
        return $this->belongsTo(Habitacion::class, 'habitacion_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
