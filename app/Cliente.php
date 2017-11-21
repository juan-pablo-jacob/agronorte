<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cliente extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'cliente';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'razon_social', 'condicion_iva', 'CUIT', 'email', 'telefono', 'fax',
        'celular', 'provincia_id', 'localidad', 'domicilio', 'codigo_postal', 'provincia_comercial_id', 'localidad_comercial',
        'domicilio_comercial', 'codigo_postal_comercial', 'active'
    ];


    public static function getValidationStore()
    {
        $validation = [
            'email' => 'unique:cliente|email',
            'razon_social' => 'required:unique:cliente'
        ];

        return $validation;
    }

    public function scopeNombre($query, $nombre) {

        if (trim($nombre) != "") {
            $query->where('razon_social', 'LIKE', "%$nombre%");
        }
    }


    public function scopeCUIT($query, $CUIT) {
        if (trim($CUIT) != "") {
            $query->where('CUIT', 'LIKE', "%$CUIT%");
        }
    }

    public static function getValidationPUT($id, Request $request)
    {

        $validation = [
            'razon_social' => 'required:unique:cliente,razon_social,' . $id
        ];

        if($request->get("email") != ""){
            $validation["email"] = 'email|unique:cliente,email,' . $id;
        }
        return $validation;
    }
}
