<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'tipo_producto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_producto', 'active'
    ];
}
