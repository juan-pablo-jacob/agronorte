<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_producto', function (Blueprint $table) {
            $table->increments('id');
            $table->string("content_type");
            $table->string("path");
            $table->string("ext", 10);
            $table->string("nombre_archivo");
            $table->integer("producto_id")->unsigned();
            $table->foreign('producto_id')
                ->references('id')->on('producto')
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
        Schema::dropIfExists('archivo_producto');
    }
}
