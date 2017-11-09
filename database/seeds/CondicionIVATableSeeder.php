<?php

use Illuminate\Database\Seeder;

class CondicionIVATableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('condicion_iva')->insert(['condicion_iva' => 'RESPONSABLE INSCRIPTO']);
        \DB::table('condicion_iva')->insert(['condicion_iva' => 'RESPONSABLE NO INSCRIPTO']);
        \DB::table('condicion_iva')->insert(['condicion_iva' => 'RESPONSABLE MONOTRIBUTO']);
        \DB::table('condicion_iva')->insert(['condicion_iva' => 'CONSUMIDOR FINAL']);
        \DB::table('condicion_iva')->insert(['condicion_iva' => 'IVA SUJETO EXENTO']);
    }
}
