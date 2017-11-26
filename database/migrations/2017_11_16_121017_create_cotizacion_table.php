<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->date("fecha_entrega");
            $table->double("precio_venta")->nullable();
            $table->text("observacion")->nullable();
            $table->double("rentabilidad_vs_costo_real")->nullable();
            $table->double("rentabilidad_vs_precio_venta")->nullable();
            $table->double("descuento")->nullable();
            $table->text("descripcion_descuento")->nullable();
            $table->double("precio_lista_producto")->nullable();
            $table->double("bonificacion_basica_producto")->nullable();
            $table->double("costo_real_producto")->nullable();
            $table->double("costo_basico_producto")->nullable();
            $table->double("incentivo_producto")->nullable();
            $table->tinyInteger("is_toma")->default(0);
            $table->double("precio_toma")->nullable();

            $table->integer("producto_id")->unsigned();
            $table->foreign('producto_id')
                ->references('id')->on('producto')
                ->onDelete('restrict')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('cotizacion');
    }
}
