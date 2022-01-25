<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourite', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->unsigned()->index()->comment('Refer users table');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('product_id')->unsigned()->index()->comment('Refer products table');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favourite');
    }
}
