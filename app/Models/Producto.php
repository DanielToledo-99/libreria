<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'marca',
        'type',
        'precio_venta',
        'precio_compra',
        'ganancia',
        'categoria',
        'stock',
        'fecha_vencimiento'
        // Agrega aquí los otros campos que tenga tu tabla 'productos'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'precio_venta' => 'float',
        'precio_compra' => 'float',
        'stock' => 'integer',
        // Agrega aquí los otros campos que necesiten ser casteados
    ];

    // Puedes agregar aquí otras relaciones y métodos adicionales según tus necesidades
}