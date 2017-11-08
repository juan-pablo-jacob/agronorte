<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_usuario', function (Blueprint $table) {
            $table->increments('id');
            $table->string("tipo_usuario");
            $table->timestamps();
        });

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer("tipo_usuario_id")->unsigned()->nullable();
                $table->foreign('tipo_usuario_id')
                    ->references('id')->on('tipo_usuario')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_usuario');
    }
}
