<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPropuestaNegocio extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'tipo_propuesta_negocio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_propuesta_negocio'
    ];
}
