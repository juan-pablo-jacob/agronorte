<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Producto extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'producto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'precio_lista', 'bonificacion_basica', 'costo_basico', 'incentivo_actual', 'is_nuevo', 'modelo', 'descripcion',
        'tipo_producto_id', 'marca_id', 'anio', 'horas_motor', 'horas_trilla', 'precio_sin_canje', 'costo_usado',
        'traccion', 'recolector', 'piloto_mapeo', 'ex_usuario', 'ubicacion', 'vendedor', 'estado',
        'disponible', 'usuario_vendedor_id', 'linea'
    ];

    /**
     * Filtrado por modelo
     * @param $query
     * @param $modelo
     */
    public function scopeModelo($query, $modelo)
    {
        if (trim($modelo) != "") {
            $query->where('modelo', 'LIKE', "%$modelo%");
        }
    }


    /**
     * Filtrado por marca
     * @param $query
     * @param $marca_id
     */
    public function scopeMarca($query, $marca_id)
    {
        if (trim($marca_id) != "") {
            $query->where('marca_id', $marca_id);
        }
    }

    /**
     * Filtrado tipo producto
     * @param $query
     * @param $tipo_producto_id
     */
    public function scopeTipoProducto($query, $tipo_producto_id)
    {
        if (trim($tipo_producto_id) != "") {
            $query->where('tipo_producto_id', $tipo_producto_id);
        }
    }

    /**
     * Retorno del array de validación al guardar el producto
     * @return array
     */
    public static function getRules(Request $request)
    {

        $array_rule = [
//            'precio_lista' => "required|numeric",
//            'descripcion' => "required",
            'bonificacion_basica' => "min:0|max:100|numeric",
            'costo_basico' => "numeric",
            'is_nuevo' => "required",
            'modelo' => "required",
            'tipo_producto_id' => "required"
        ];

        if (!is_null($request->get("is_toma")) && (int)$request->get("is_toma") == 1) {
            $array_rule["precio_toma"] = "required";
        } else {
            $array_rule["precio_lista"] = "required";
        }

        return $array_rule;
    }

}
