<?php

use Illuminate\Database\Seeder;

class TipoProductoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'TRACTOR']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'COSECHADORA']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'PULVERIZADORA']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'COSECHADORA DE FORRAJE']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'ROTOENFARDADORA']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'MEGAENFARDADORA']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'JARDÍN']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'PLATAFORMAS']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'SEGADORAS ACONDICIONADORAS']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'CARGADORES FRONTALES']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'AMS']);
        \DB::table('tipo_producto')->insert(['tipo_producto' => 'HENO Y FORRAJE']);
    }
}
