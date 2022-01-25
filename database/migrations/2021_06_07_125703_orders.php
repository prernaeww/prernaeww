<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('customer_id')->unsigned()->index()->comment('Refer users table');
            // $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('canteen_id')->unsigned()->index()->comment('Refer users table');
            // $table->foreign('canteen_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('meal_id')->unsigned()->index()->comment('Refer Meals table');

            $table->string('price')->comment('Per Day Price');
            $table->string('sub_total');
            $table->string('total');
            $table->string('tax');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('transaction_id');
            $table->integer('status')->default(0)->comment(' 0 Pending 1- Success 2-Failed 3 Failed from order');
            $table->string('reason');
            $table->text('special_instruction');
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
        Schema::dropIfExists('orders');
    }
}
