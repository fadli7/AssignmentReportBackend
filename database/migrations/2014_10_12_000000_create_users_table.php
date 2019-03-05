<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('full_name');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('place_birth');
            $table->date('date_birth');
            $table->string('motto')->nullable();
            $table->string('api_token');
            $table->integer('role_id')->unsigned();
            $table->text('picture')->nullable();
            $table->timestamps();

//            $table->foreign('role_id')->references('id')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
