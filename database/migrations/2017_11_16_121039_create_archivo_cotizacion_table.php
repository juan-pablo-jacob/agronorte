<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_cotizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string("content_type");
            $table->string("path");
            $table->string("ext", 10);
            $table->string("nombre_archivo");

            $table->integer("cotizacion_id")->unsigned();
            $table->foreign('cotizacion_id')
                ->references('id')->on('cotizacion')
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
        Schema::dropIfExists('archivo_cotizacion');
    }
}
