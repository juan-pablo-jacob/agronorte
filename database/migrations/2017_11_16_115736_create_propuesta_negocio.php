<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropuestaNegocio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propuesta_negocio', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->tinyInteger('estados')->default(1);
            $table->double('precio_total')->nullable();
            $table->tinyInteger('active')->default(1);

            $table->integer("cliente_id")->unsigned();
            $table->foreign('cliente_id')
                ->references('id')->on('cliente')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer("users_id")->unsigned();
            $table->foreign('users_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer("tipo_propuesta_negocio_id")->unsigned();
            $table->foreign('tipo_propuesta_negocio_id')
                ->references('id')->on('tipo_propuesta_negocio')
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
        Schema::dropIfExists('propuesta_negocio');
    }
}
