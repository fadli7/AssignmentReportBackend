<?php

use Illuminate\Database\Seeder;

class UtilizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('utilization')->insert([
            'work_load'             => 0,
            'user_id'               => 1,
            'work_quality'          => 0,
            'sppd'                  => 0,
            'complete_assignment'   => 0
        ]);

        DB::table('utilization')->insert([
            'work_load'             => 0,
            'user_id'               => 3,
            'work_quality'          => 0,
            'sppd'                  => 0,
            'complete_assignment'   => 0
        ]);

        DB::table('utilization')->insert([
            'work_load'             => 0,
            'user_id'               => 4,
            'work_quality'          => 0,
            'sppd'                  => 0,
            'complete_assignment'   => 0
        ]);

        DB::table('utilization')->insert([
            'work_load'             => 0,
            'user_id'               => 5,
            'work_quality'          => 0,
            'sppd'                  => 0,
            'complete_assignment'   => 0
        ]);

        DB::table('utilization')->insert([
            'work_load'             => 0,
            'user_id'               => 6,
            'work_quality'          => 0,
            'sppd'                  => 0,
            'complete_assignment'   => 0
        ]);

        DB::table('utilization')->insert([
            'work_load'             => 0,
            'user_id'               => 7,
            'work_quality'          => 0,
            'sppd'                  => 0,
            'complete_assignment'   => 0
        ]);
    }
}
