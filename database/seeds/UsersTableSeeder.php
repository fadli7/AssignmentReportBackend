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
            'username'      => 'user',
            'email'         => 'user@example.com',
            'password'      => bcrypt('sandiaman'),
            'full_name'     => 'User Aman',
            'token_api'     => bcrypt('user@example.com'),
            'role_id'       => 1,
        ]);
    }
}
