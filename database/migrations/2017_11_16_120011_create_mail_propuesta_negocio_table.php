<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailPropuestaNegocioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_propuesta_negocio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mail_cliente')->nullable();
            $table->string('mail_vendedores')->nullable();

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
        Schema::dropIfExists('mail_propuesta_negocio');
    }
}
