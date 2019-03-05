<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email'         => 'user@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'    => 'User Aman',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('user@example.com'),
            'role_id'       => 1,
        ]);

        DB::table('users')->insert([
            'email'         => 'manager@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'    => 'Manager',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('manager@example.com'),
            'role_id'       => 1,
        ]);

        DB::table('users')->insert([
            'email'         => 'spv@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'SPV',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('spv@example.com'),
            'role_id'       => 2,
        ]);

        DB::table('users')->insert([
            'email'         => 'ptl@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'PTL',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('plt@example.com'),
            'role_id'       => 3,
        ]);

        DB::table('users')->insert([
            'email'         => 'engineer@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'Engineer',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('engineer@example.com'),
            'role_id'       => 4,
        ]);

        DB::table('users')->insert([
            'email'         => 'engineer2@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'Engineer 2',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('engineer2@example.com'),
            'role_id'       => 4,
        ]);

        DB::table('users')->insert([
            'email'         => 'engineer3@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'Engineer 3',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('engineer3@example.com'),
            'role_id'       => 4,
        ]);
    }
}
