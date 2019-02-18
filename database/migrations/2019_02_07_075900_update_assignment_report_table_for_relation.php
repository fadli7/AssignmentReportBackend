<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssignmentReportTableForRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignment_report', function (Blueprint $table) {
            $table->foreign('assignment_user_id')->references('id')->on('assignment_user');
            $table->foreign('time_record_id')->references('id')->on('time_record');
            $table->foreign('customer_info_id')->references('id')->on('customer_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
