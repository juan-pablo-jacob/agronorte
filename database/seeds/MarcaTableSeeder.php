<?php

use Illuminate\Database\Seeder;

class MarcaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('marca')->insert(['marca' => 'JOHN DEERE']);
        \DB::table('marca')->insert(['marca' => 'ZANELLO']);
    }
}
