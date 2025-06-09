<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rmx_vyx_nodos')->insert([
            'nodo_codigo' => '0',
            'nodo_descripcion' => 'Raíz',
            'nodo_padre_id' => '0',
            'nodo_usr_id' => '1'
        ]);
    }
}
