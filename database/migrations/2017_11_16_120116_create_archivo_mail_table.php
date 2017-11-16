<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_mail', function (Blueprint $table) {
            $table->increments('id');
            $table->string("content_type");
            $table->string("path");
            $table->string("ext", 10);
            $table->string("nombre_archivo");

            $table->integer("mail_propuesta_negocio_id")->unsigned();
            $table->foreign('mail_propuesta_negocio_id')
                ->references('id')->on('mail_propuesta_negocio')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivo_mail');
    }
}
