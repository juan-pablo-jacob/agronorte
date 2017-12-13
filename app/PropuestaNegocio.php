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


    public function scopeTipoPropuestaNegocio($query, $tipo_propuesta_negocio_id)
    {
        if (trim($tipo_propuesta_negocio_id) != "") {
            $query->where('tipo_propuesta_negocio_id', $tipo_propuesta_negocio_id);
        }
    }

    public function scopeModelo($query, $modelo)
    {
        if (trim($modelo) != "") {
            $query->where('producto.modelo', 'like', "%$modelo%");
        }
    }

    public function scopeVendedor($query, $users_id)
    {
        if (trim($users_id) != "") {
            $query->where('users_id', $users_id);
        }
    }

    public function scopeFechadesde($query, $fecha_desde)
    {
        if ($fecha_desde != "") {
            $date = \DateTime::createFromFormat("d/m/Y", $fecha_desde);
            $dateFormated = $date->format("Y-m-d");
            $query->whereDate('propuesta_negocio.fecha', ">=", $dateFormated);
        }
    }

    public function scopeFechahasta($query, $fecha_hasta)
    {
        if ($fecha_hasta != "") {
            $date = \DateTime::createFromFormat("d/m/Y", $fecha_hasta);
            $dateFormated = $date->format("Y-m-d");
            $query->whereDate('propuesta_negocio.fecha', "<=", $dateFormated);
        }
    }

    public function scopeEstados($query, $estados)
    {
        if (trim($estados) != "") {
            $query->where('propuesta_negocio.estados', $estados);
        }
    }
}
