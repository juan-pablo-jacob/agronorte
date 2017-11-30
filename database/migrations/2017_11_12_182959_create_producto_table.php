<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->increments('id');
            $table->double("precio_lista");
            $table->double("bonificacion_basica")->nullable();
            $table->double("costo_basico")->nullable();
            $table->double("incentivo_actual")->nullable();
            $table->tinyInteger("is_nuevo")->default(1);
            $table->string("modelo");
            $table->text("descripcion");
            $table->tinyInteger("active")->default(1);

            //tipo Producto
            $table->integer("tipo_producto_id")->unsigned()->nullable();
            $table->foreign('tipo_producto_id')
                ->references('id')->on('tipo_producto')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            //Marca
            $table->integer("marca_id")->unsigned()->nullable();
            $table->foreign('marca_id')
                ->references('id')->on('marca')
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
        Schema::dropIfExists('producto');
    }
}
