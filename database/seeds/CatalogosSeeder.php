<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rmx_vyx_catalogos')->insert([
            'cat_codigo' => '0',
            'cat_descripcion' => 'Raíz',
            'cat_padre_id' => '0',
            'cat_usr_id' => '1'
        ]);
    }
}
