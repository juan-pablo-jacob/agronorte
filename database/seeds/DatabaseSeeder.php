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
    }
}
