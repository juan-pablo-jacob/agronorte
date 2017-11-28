<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoPropuestaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_propuesta', function (Blueprint $table) {
            $table->increments('id');
            $table->string("content_type");
            $table->string("path");
            $table->string("ext", 10);
            $table->string("nombre_archivo");
            $table->integer("propuesta_negocio_id")->unsigned();
            $table->foreign('propuesta_negocio_id')
                ->references('id')->on('propuesta_negocio')
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
        Schema::dropIfExists('archivo_propuesta');
    }
}
