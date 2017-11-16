<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncentivoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incentivo_producto', function (Blueprint $table) {
            $table->increments('id');
            $table->double("costo_real");

            $table->integer("incentivo_id")->unsigned();
            $table->foreign('incentivo_id')
                ->references('id')->on('incentivo')
                ->onDelete('restrict')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('incentivo_producto');
    }
}
