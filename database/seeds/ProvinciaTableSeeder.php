<?php

use Illuminate\Database\Seeder;

class ProvinciaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('provincia')->insert(['provincia' => 'SALTA']);
        \DB::table('provincia')->insert(['provincia' => 'BUENOS AIRES']);
        \DB::table('provincia')->insert(['provincia' => 'SAN LUIS']);
        \DB::table('provincia')->insert(['provincia' => 'ENTRE RÍOS']);
        \DB::table('provincia')->insert(['provincia' => 'LA RIOJA']);
        \DB::table('provincia')->insert(['provincia' => 'SANTIAGO DEL ESTERO']);
        \DB::table('provincia')->insert(['provincia' => 'CHACO']);
        \DB::table('provincia')->insert(['provincia' => 'SAN JUAN']);
        \DB::table('provincia')->insert(['provincia' => 'CATAMARCA']);
        \DB::table('provincia')->insert(['provincia' => 'LA PAMPA']);
        \DB::table('provincia')->insert(['provincia' => 'MENDOZA']);
        \DB::table('provincia')->insert(['provincia' => 'MISIONES']);
        \DB::table('provincia')->insert(['provincia' => 'FORMOSA']);
        \DB::table('provincia')->insert(['provincia' => 'NEUQUÉN']);
        \DB::table('provincia')->insert(['provincia' => 'RÍO NEGRO']);
        \DB::table('provincia')->insert(['provincia' => 'SANTA FE']);
        \DB::table('provincia')->insert(['provincia' => 'TUCUMÁN']);
        \DB::table('provincia')->insert(['provincia' => 'CHUBUT']);
        \DB::table('provincia')->insert(['provincia' => 'TIERRA DEL FUEGO']);
        \DB::table('provincia')->insert(['provincia' => 'CORRIENTES']);
        \DB::table('provincia')->insert(['provincia' => 'CÓRDOBA']);
        \DB::table('provincia')->insert(['provincia' => 'JUJUY']);
        \DB::table('provincia')->insert(['provincia' => 'SANTA CRUZ']);
    }
}
