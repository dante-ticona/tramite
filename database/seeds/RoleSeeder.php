<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'code' => 'ADM',
            'description' => 'Administrador',
        ]);
        DB::table('roles')->insert([
            'code' => 'OPE',
            'description' => 'Operador',
        ]);
        DB::table('roles')->insert([
            'code' => 'DES',
            'description' => 'Despachador',
        ]);
    }
}
