<?php

use Illuminate\Database\Seeder;

class TipoPropuestaNegocioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tipo_propuesta_negocio')->insert(['tipo_propuesta_negocio' => 'VENTA NUEVO']);
        \DB::table('tipo_propuesta_negocio')->insert(['tipo_propuesta_negocio' => 'VENTA USADO']);
        \DB::table('tipo_propuesta_negocio')->insert(['tipo_propuesta_negocio' => 'VENTA NUEVO / TOMA USADO']);
        \DB::table('tipo_propuesta_negocio')->insert(['tipo_propuesta_negocio' => 'VENTA USADO / TOMA USADO']);
    }
}
