<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParametrosSistema extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'parametros_sistema';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bonificacion_basica', 'precio_dolar'
    ];
}
