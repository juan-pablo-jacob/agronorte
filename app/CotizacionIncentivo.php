<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CotizacionIncentivo extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'cotizacion_incentivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'incentivo_id', 'cotizacion_id'
    ];

    /**
     * Método de retorno la validación para la Creación o Update
     * @return array
     */
    public static function getRules()
    {
        $validation = [
            'incentivo_id' => 'required',
            'cotizacion_id' => 'required'
        ];

        return $validation;
    }

}
