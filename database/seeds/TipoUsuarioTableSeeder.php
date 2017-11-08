<?php

use Illuminate\Database\Seeder;

class TipoUsuarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tipo_usuario')->insert(['tipo_usuario' => 'Administrador']);
        \DB::table('tipo_usuario')->insert(['tipo_usuario' => 'Vendedor']);
        \DB::table('tipo_usuario')->insert(['tipo_usuario' => 'Gerente de ventas']);
        \DB::table('tipo_usuario')->insert(['tipo_usuario' => 'Jefe de ventas']);
    }
}
