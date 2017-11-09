<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
            'email' => 'required:unique:cliente|email',
            'razon_social' => 'required:unique:cliente'
        ];

        return $validation;
    }

    public function scopeNombre($query, $nombre) {

        if (trim($nombre) != "") {
            $query->where('razon_social', 'LIKE', "%$nombre%");
        }
    }


    public function scopeEmail($query, $email) {
        if (trim($email) != "") {
            $query->where('email', 'LIKE', "%$email%");
        }
    }

    public static function getValidationPUT($id)
    {
        $validation = [
            'razon_social' => 'required:unique:cliente,razon_social,' . $id,
            'email' => 'email|required|unique:cliente,email,' . $id
        ];

        return $validation;
    }
}
