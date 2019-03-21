<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->primary('id');
            $table->integer('ptl_id')->unsigned();
            $table->string('project_number');
            $table->string('io_number');
            $table->string('assignment_class');
            $table->string('assignment_tittle');
            $table->text('assignment_desc');
            $table->integer('difficulty_level');
            $table->string('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('ptl_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment');
    }
}
