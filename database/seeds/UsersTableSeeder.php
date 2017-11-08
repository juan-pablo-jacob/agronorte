<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert(array(
            'name' => 'juanpablo',
            'nombre' => 'Juan Pablo',
            'apellido' => 'Jacob',
            'email' => 'juanpablojacob1@gmail.com',
            'password' => \Hash::make('juanpablo'),
            'tipo_usuario_id' => 1
        ));

        \DB::table('users')->insert(array(
            'name' => 'juanma',
            'nombre' => 'Juan Manuel',
            'apellido' => 'Lazzarini',
            'email' => 'juan.manuel.lazzarini@gmail.com',
            'password' => \Hash::make('juanma'),
            'tipo_usuario_id' => 1
        ));
    }
}
