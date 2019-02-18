<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_user_id')->unsigned();
            $table->string('assignment_type');
            $table->integer('time_record_id')->unsigned();
            $table->integer('customer_info_id')->unsigned();
            $table->boolean('sppd_status')->default(0);
            $table->integer('day_number');
            $table->string('brief_work');
            $table->text('bai');
            $table->text('tnc');
            $table->text('photos');
            $table->text('other');
            $table->text('result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_report');
    }
}
