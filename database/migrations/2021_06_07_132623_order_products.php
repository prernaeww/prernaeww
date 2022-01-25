<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('order_id')->unsigned()->index()->comment('Refer orders table');
            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->integer('date_id')->unsigned()->index()->comment('Refer order_dates table');
            // $table->foreign('date_id')->references('id')->on('order_dates')->onDelete('cascade');

            $table->integer('category_id')->unsigned()->index()->comment('Refer category table');
            // $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');

            $table->integer('product_id')->unsigned()->index()->comment('Refer products table');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

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
        Schema::dropIfExists('order_products');
    }
}
