<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','nombre','apellido','cel_numero','calle','numero_calle','localidad','codigo_postal','is_activo', 'provincia_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function scopeName($query, $name) {

        if (trim($name) != "") {
            $query->where('name', 'LIKE', "%$name%");
        }
    }

    public function scopeEmail($query, $email) {
        if (trim($email) != "") {
            $query->where('email', 'LIKE', "%$email%");
        }
    }

    /**
     * Retorno del array de validación al guardar la moto
     * @param type $request
     */
    public static function getRulesSTORE(Request $request) {

        $array_rule = [
            'name' => "required|unique:users",
            'nombre' => "required",
            'apellido' => "required",
            'email' => "required|email|unique:users"
        ];

        return $array_rule;
    }

    /**
     * Retorno del array de validación al realizar el UPDATE de la moto
     * @param type $id
     * @return type
     */
    public static function getRulesPUT($id,Request $request) {
        $array_rule = [
            'name' => "required|unique:users,name," . $id,
            'nombre' => "required",
            'apellido' => "required",
            'email' => 'required|email|unique:users,email,' . $id
        ];

        return $array_rule;
    }
}
