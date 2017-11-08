<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email');
            $table->string('password');
            $table->string('cel_numero')->nullable();
            $table->string('calle')->nullable();
            $table->string('numero_calle')->nullable();
            $table->string('localidad')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->tinyInteger("is_activo")->default(1)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
