<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'marca';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'marca', 'active'
    ];
}
