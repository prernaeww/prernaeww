<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('user_id')->unsigned()->index()->comment('Refer users table');
            $table->integer('group_id')->unsigned()->index()->comment('Refer groups table');

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

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
        Schema::dropIfExists('users_group');
    }
}
