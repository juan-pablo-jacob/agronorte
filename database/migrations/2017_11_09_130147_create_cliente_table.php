<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->increments('id');
            $table->string("razon_social");
            $table->string("condicion_iva")->nullable();
            $table->string("CUIT")->nullable();
            $table->string("email")->nullable();

            $table->string("telefono")->nullable();
            $table->string("fax")->nullable();
            $table->string("celular")->nullable();

            $table->integer("provincia_id")->unsigned()->nullable();
            $table->foreign('provincia_id')
                ->references('id')->on('provincia')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->string("localidad")->nullable();
            $table->string("domicilio")->nullable();
            $table->string("codigo_postal")->nullable();


            $table->integer("provincia_comercial_id")->unsigned()->nullable();
            $table->foreign('provincia_comercial_id')
                ->references('id')->on('provincia')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->string("localidad_comercial")->nullable();
            $table->string("domicilio_comercial")->nullable();
            $table->string("codigo_postal_comercial")->nullable();


            $table->tinyInteger("active")->default(1);
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
        Schema::dropIfExists('cliente');
    }
}
