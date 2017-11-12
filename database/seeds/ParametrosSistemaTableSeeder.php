<?php

use Illuminate\Database\Seeder;

class ParametrosSistemaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('parametros_sistema')->insert(['bonificacion_basica' => 22]);
    }
}
