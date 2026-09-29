<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    protected $table = 'servicios';

    /**
     * Áreas del hotel a las que se clasifica un servicio. El orden es el que
     * usan los desplegables del formulario y las tarjetas de filtro.
     *
     * @var list<string>
     */
    public const CATEGORIAS = [
        'Minibar',
        'Restaurante',
        'Room Service',
        'Lavandería',
        'Spa y Bienestar',
        'Transporte',
        'Otros',
    ];

    /**
     * Categoría con la que se rellena el alta cuando no se elige otra.
     */
    public const CATEGORIA_POR_DEFECTO = 'Minibar';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
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
     * Precio de catálogo con el formato de moneda en pesos mexicanos que usa
     * todo el panel.
     */
    public function precioEnPesos(): string
    {
        return '$'.number_format((float) $this->precio, 2);
    }

    /**
     * @return HasMany<ReservaServicio, $this>
     */
    public function reservasServicio(): HasMany
    {
        return $this->hasMany(ReservaServicio::class, 'servicio_id');
    }
}
