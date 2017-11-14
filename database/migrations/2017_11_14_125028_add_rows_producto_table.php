<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowsProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->integer("anio")->nullable();
            $table->integer("horas_motor")->nullable();
            $table->integer("horas_trilla")->nullable();
            $table->double("precio_sin_canje")->nullable();
            $table->double("costo_usado")->nullable();
            $table->string("traccion")->nullable();
            $table->string("recolector")->nullable();
            $table->string("piloto_mapeo")->nullable();
            $table->string("ex_usuario")->nullable();
            $table->string("ubicacion")->nullable();
            $table->string("vendedor")->nullable();
            $table->string("estado")->nullable();
            $table->string("disponible")->nullable();

            $table->integer("usuario_vendedor_id")->unsigned()->nullable();
            $table->foreign('usuario_vendedor_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto', function (Blueprint $table) {
            //
        });
    }
}
