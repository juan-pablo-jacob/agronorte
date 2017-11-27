<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cotizacion extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'cotizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_entrega', 'precio_venta', 'observacion', 'rentabilidad_vs_costo_real', 'rentabilidad_vs_precio_venta',
        'descuento', 'descripcion_descuento', 'precio_lista_producto', 'bonificacion_basica_producto', 'costo_real_producto',
        'costo_basico_producto', 'incentivo_producto', 'is_toma', 'producto_id', 'propuesta_negocio_id', 'incentivo_id', 'active'
    ];


    /**
     * Método de retorno la validación para la Creación o Update
     * @return array
     */
    public static function getRules()
    {
        $validation = [
            'fecha_entrega' => 'required|date',
            'producto_id' => 'required',
            'propuesta_negocio_id' => 'required'
        ];

        return $validation;
    }

    /**
     * Método que retorna el precio de venta de la cotización en base a los parámetros del request
     * @param Request $request
     * @return bool|float|int
     */
    public static function getPrecioVenta(Request $request)
    {
        $precio_lista = (float)$request->get("precio_lista_producto");
        if ($precio_lista <= 0) {
            return false;
        }

        $descuento = (float)$request->get("descuento");
        $precio_venta = (100 - $descuento) * $precio_lista / 100;
        $costo_basico = (float)$request->get("costo_basico_producto");

        $array_incentivos = $request->get("incentivos_id");
        if (!is_null($array_incentivos) && count($array_incentivos) > 0) {
            foreach ($array_incentivos as $incentivo_id) {
                $porcentaje_incentivo = (float)Incentivo::find($incentivo_id)->first()->porcentaje;
                $precio_venta -= ($porcentaje_incentivo) * $costo_basico / 100;
            }
        }
        return $precio_venta;
    }
}
