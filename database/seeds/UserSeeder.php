<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => 1,
            'branch_id' => 1,
            'name' => 'Roberto Morales E',
            'email' => 'rme.plan.b@gmail.com',
            'password' => Hash::make('123456'),
            'status' => 'A'
        ]);
        DB::table('users')->insert([
            'role_id' => 1,
            'branch_id' => 1,
            'name' => 'Jose Luis Yacelli',
            'email' => 'master.yac@gmail.com',
            'password' => Hash::make('123456'),
            'status' => 'A'
        ]);
    }
}
