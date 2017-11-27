<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProvinciaTableSeeder::class);
        $this->call(TipoUsuarioTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CondicionIVATableSeeder::class);
        $this->call(TipoProductoTableSeeder::class);
        $this->call(MarcaTableSeeder::class);
        $this->call(ParametrosSistemaTableSeeder::class);
        $this->call(TipoPropuestaNegocioTableSeeder::class);
    }
}
