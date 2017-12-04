<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropuestaNegocio extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'propuesta_negocio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha', 'estados', 'precio_total', 'active', 'cliente_id', 'users_id', 'tipo_propuesta_negocio_id', 'comentario'
    ];

    /**
     * Método de retorno la validación para la Creación o Update
     * @return array
     */
    public static function getRules()
    {
        $validation = [
            'fecha' => 'required',
            'cliente_id' => 'required',
            'users_id' => 'required',
            'tipo_propuesta_negocio_id' => 'required',
        ];

        return $validation;
    }


    public function scopeTipoPropuestaNegocio($query, $tipo_propuesta_negocio_id) {
        if (trim($tipo_propuesta_negocio_id) != "") {
            $query->where('tipo_propuesta_negocio_id', $tipo_propuesta_negocio_id);
        }
    }

    public function scopeModelo($query, $modelo) {
        if (trim($modelo) != "") {
            $query->where('producto.modelo', 'like', "%$modelo%");
        }
    }

    public function scopeVendedor($query, $users_id) {
        if (trim($users_id) != "") {
            $query->where('users_id', $users_id);
        }
    }
}
