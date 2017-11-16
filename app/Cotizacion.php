<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'fecha_entrega', 'precio_venta', 'observacion', 'rentabilidad_precio_lista', 'rentabilidad_precio_venta',
        'descuento', 'descripcion_descuento', 'precio_lista_producto', 'bonificacion_basica_producto', 'costo_real_producto',
        'costo_basico_producto', 'incentivo_producto', 'is_toma', 'producto_id', 'propuesta_negocio_id', 'incentivo_id'
    ];


}
