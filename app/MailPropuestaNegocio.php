<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailPropuestaNegocio extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'mail_propuesta_negocio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail_cliente', 'mail_vendedores', 'propuesta_negocio_id', 'is_iva_incluido'
    ];

    /**
     * Método de retorno la validación para la Creación o Update
     * @return array
     */
    public static function getRules()
    {
        $validation = [
            'propuesta_negocio_id' => 'required'
        ];

        return $validation;
    }
}
