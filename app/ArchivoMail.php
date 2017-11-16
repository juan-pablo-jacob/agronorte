<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoMail extends Model
{
    /**
     *Nombre de la tabla
     * @var type
     */
    protected $table = 'archivo_mail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content_type', 'path', 'ext', 'nombre_archivo', 'mail_propuesta_negocio_id'
    ];



    /**
     * Método de retorno la validación para la Creación o Update
     * @return array
     */
    public static function getRules()
    {
        $validation = [
            'content_type' => 'required',
            'mail_propuesta_negocio_id' => 'required',
            'path' => 'required',
            'nombre_archivo' => 'required',
            'ext' => 'required',
        ];

        return $validation;
    }


    /**
     * Retorna si la extensión se encuentra en el array
     * @param $ext
     * @return bool
     */
    public static function validateFileExtension($ext){
        return in_array(strtolower($ext), ["docx", "doc", "pdf", "jpg", "png", "xls", "xlsx"]);
    }
}
