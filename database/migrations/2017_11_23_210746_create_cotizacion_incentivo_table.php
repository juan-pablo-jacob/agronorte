<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionIncentivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion_incentivo', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("incentivo_id")->unsigned();
            $table->foreign('incentivo_id')
                ->references('id')->on('incentivo')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer("cotizacion_id")->unsigned();
            $table->foreign('cotizacion_id')
                ->references('id')->on('cotizacion')
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
        Schema::dropIfExists('cotizacion_incentivo');
    }
}
