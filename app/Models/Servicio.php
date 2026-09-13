<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'precio',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
        ];
    }

    /**
     * @return HasMany<ReservaServicio, $this>
     */
    public function reservasServicio(): HasMany
    {
        return $this->hasMany(ReservaServicio::class, 'servicio_id');
    }
}
