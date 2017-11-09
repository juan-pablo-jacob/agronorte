<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondicionIVA extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'condicion_iva';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'condicion_iva'
    ];
}
