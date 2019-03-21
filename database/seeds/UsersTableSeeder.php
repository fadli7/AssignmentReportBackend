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
        DB::table('users')->insert([ // 1
            'email'             => 'user@example.com',
            'password'          => bcrypt('sandiaman'),
            'full_name'         => 'User Aman',
            'place_birth'       => 'Semarang',
            'date_birth'        => '1998-12-12',
            'api_token'         => bcrypt('user@example.com'),
            'role_id'           => 4,
            'start_date'        => '2019-03-01'
        ]);

        DB::table('users')->insert([ // 2
            'email'         => 'manager@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'    => 'Manager',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('manager@example.com'),
            'role_id'       => 1,
            'start_date'        => '2019-03-01'
        ]);

        DB::table('users')->insert([ // 3
            'email'         => 'spv@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'SPV',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('spv@example.com'),
            'role_id'       => 2,
            'start_date'        => '2019-03-01'
        ]);

        DB::table('users')->insert([ // 4
            'email'         => 'ptl@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'PTL',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('plt@example.com'),
            'role_id'       => 3,
            'start_date'        => '2019-03-01'
        ]);

        DB::table('users')->insert([ // 5
            'email'         => 'engineer@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'Engineer',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('engineer@example.com'),
            'role_id'       => 4,
            'start_date'        => '2019-03-01'
        ]);

        DB::table('users')->insert([ // 6
            'email'         => 'engineer2@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'Engineer 2',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('engineer2@example.com'),
            'role_id'       => 4,
            'start_date'        => '2019-03-01'
        ]);

        DB::table('users')->insert([ // 7
            'email'         => 'engineer3@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'Engineer 3',
            'place_birth'   => 'Semarang',
            'date_birth'    => '1998-12-12',
            'api_token'     => bcrypt('engineer3@example.com'),
            'role_id'       => 4,
            'start_date'        => '2019-03-01'
        ]);
    }
}
