<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherProducts extends Model
{
    protected $table = 'otherproducts';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
        'type',
    ];
}