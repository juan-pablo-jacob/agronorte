<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incentivo extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'incentivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'porcentaje', 'fecha_caducidad', 'condicion_excluyente', 'active'
    ];

    /**
     * Método de retorno la validación para la Creación o Update
     * @return array
     */
    public static function getRules()
    {
        $validation = [
            'porcentaje' => 'required|numeric|min:0|max:100',
            'fecha_caducidad' => 'required|date'
        ];

        return $validation;
    }


    public function scopeNocaducados($query, $no_caducados)
    {
        if ($no_caducados != "" && (int)$no_caducados == 2) {
            //Lista los caducados
            $query->where('fecha_caducidad', '<=', date("Y-m-d"));
        } elseif ($no_caducados != "" && (int)$no_caducados == 1) {
            //Lista los no caducados
            $query->where('fecha_caducidad', '>', date("Y-m-d"));
        }
    }
}
