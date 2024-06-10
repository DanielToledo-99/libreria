<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
class VentaProducto extends Model
{
    protected $table = 'venta_producto'; 

    protected $fillable = [
        'id_venta', 'id_producto', 'cantidad', 'subtotal'
    ];

    use HasFactory; // Make sure you import HasFactory at the top.
}