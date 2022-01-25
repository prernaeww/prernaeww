<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Schools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('canteen_id')->unsigned()->index()->comment('Refer users table');
            // $table->foreign('canteen_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('schools');
    }
}
