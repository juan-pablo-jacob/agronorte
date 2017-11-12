<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'tipo_producto_id', 'marca_id'
    ];

    /**
     * Filtrado por modelo
     * @param $query
     * @param $modelo
     */
    public function scopeModelo($query, $modelo) {
        if (trim($modelo) != "") {
            $query->where('modelo', 'LIKE', "%$modelo%");
        }
    }

    /**
     * Filtrado tipo producto
     * @param $query
     * @param $tipo_producto_id
     */
    public function scopeTipoProducto($query, $tipo_producto_id) {
        if (trim($tipo_producto_id) != "") {
            $query->where('tipo_producto_id', $tipo_producto_id);
        }
    }

    /**
     * Retorno del array de validación al guardar el producto
     * @return array
     */
    public static function getRules() {

        $array_rule = [
            'precio_lista' => "required",
            'bonificacion_basica' => "required",
            'costo_basico' => "required",
            'is_nuevo' => "required",
            'modelo' => "required",
            'descripcion' => "required",
            'tipo_producto_id' => "required"
        ];

        return $array_rule;
    }

}
