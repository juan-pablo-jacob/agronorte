<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncentivoProducto extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'incentivo_producto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'costo_real', 'incentivo_id', 'producto_id'
    ];

    /**
     * Método de retorno la validación para la Creación o Update
     * @return array
     */
    public static function getRules()
    {
        $validation = [
            'costo_real' => 'required|numeric',
            'incentivo_id' => 'required',
            'producto_id' => 'required'
        ];

        return $validation;
    }
}
