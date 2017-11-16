<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncentivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incentivo', function (Blueprint $table) {
            $table->increments('id');
            $table->double("porcentaje");
            $table->date("fecha_caducidad");
            $table->tinyInteger("active")->default(1);
            $table->string("condicion_excluyente");
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
        Schema::dropIfExists('incentivo');
    }
}
