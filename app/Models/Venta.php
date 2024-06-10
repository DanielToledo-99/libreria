<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Don't forget to import HasFactory if you're using it.

class Venta extends Model
{
    protected $table = 'ventas'; 

    protected $fillable = [
        'fecha', 'total', 'metodo_pago', 'cliente_deuda', 'estado'
    ];

    use HasFactory; // Make sure you import HasFactory at the top.
}