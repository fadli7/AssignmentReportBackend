<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'name'  => 'Manager',
        ]);

        DB::table('role')->insert([
            'name'  => 'SPV',
        ]);

        DB::table('role')->insert([
            'name'  => 'PLT',
        ]);

        DB::table('role')->insert([
            'name'  => 'Field Engineer',
        ]);
    }
}
