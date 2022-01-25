<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_dates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('order_id')->unsigned()->index()->comment('Refer orders table');
            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->date('date');
            $table->string('day');
            $table->tinyInteger('status')->default(0)->comment('0 - pending, 1 - Delivered, 2 - Reported , 3 - Expired');
            $table->text('description');
            $table->integer('issue_id');
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
        Schema::dropIfExists('order_dates');
    }
}
