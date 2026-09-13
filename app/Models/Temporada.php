<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
    protected $table = 'temporadas';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'multiplicador_precio',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'multiplicador_precio' => 'decimal:2',
        ];
    }
}
