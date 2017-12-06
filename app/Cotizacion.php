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
        'precio_venta', 'observacion', 'rentabilidad_vs_costo_real', 'rentabilidad_vs_precio_venta',
        'descuento', 'descripcion_descuento', 'precio_lista_producto', 'bonificacion_basica_producto', 'costo_real_producto',
        'costo_basico_producto', 'costo_usado_producto', 'incentivo_producto', 'is_toma', 'producto_id', 'propuesta_negocio_id', 'incentivo_id', 'active',
        'precio_toma', 'precio_venta_iva', 'precio_toma_iva'
    ];


    /**
     * Método de retorno la validación para la Creación o Update
     * @param Request $request
     * @return array
     */
    public static function getRules(Request $request)
    {
        $validation = [
            'producto_id' => 'required',
            'propuesta_negocio_id' => 'required'
        ];

        if (((int)$request->get("tipo_propuesta_negocio_id") == 3 || (int)$request->get("tipo_propuesta_negocio_id") == 4) && (int)$request->get("is_toma") == 1) {
            $validation["precio_toma"] = "required|numeric";
        } else {
            $validation["precio_lista_producto"] = "required|numeric";
            $validation["precio_venta"] = "required|numeric";
        }

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
