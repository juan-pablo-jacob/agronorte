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
        'disponible', 'usuario_vendedor_id'
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
    public static function getRules()
    {

        $array_rule = [
            'precio_lista' => "required|numeric",
            'bonificacion_basica' => "required|min:0|max:100|numeric",
            'costo_basico' => "required|numeric",
            'is_nuevo' => "required",
            'modelo' => "required",
            'descripcion' => "required",
            'tipo_producto_id' => "required"
        ];

        return $array_rule;
    }

    /**
     * Método que retorna el costo real de un producto NUEVO
     * @param Request $request
     * @return float
     */
    public static function getCostoRealNuevo(Request $request)
    {
        //$costo_real_producto = (float)$request->get("precio_lista_producto") - (float)$request->get("bonificacion_basica_producto");
        $costo_basico_producto = (float)$request->get("costo_basico_producto");
        $array_incentivos = $request->get("incentivos_id");
        foreach ($array_incentivos as $incentivo_id) {
            $porcentaje_incentivo = (float)Incentivo::find($incentivo_id)->first()->porcentaje;
            $costo_basico_producto -= ($porcentaje_incentivo) * $costo_basico_producto / 100;
        }
        return $costo_basico_producto;
    }

}
