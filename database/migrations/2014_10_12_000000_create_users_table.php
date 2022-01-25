<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('profile_picture');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->tinyInteger('gender')->default('1')->comment('1 - Male, 2 - Female');
            $table->date('dob')->default(null);
            // $table->integer('department')->default(null)->comment('refer department table');
            $table->string('department')->default("");
            $table->integer('school')->default(null)->comment('refer school table');
            $table->string('grade')->default("");
            $table->string('class')->default("");
            $table->timestamps();
            $table->softDeletes();
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
