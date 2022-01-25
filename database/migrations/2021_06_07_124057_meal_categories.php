<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MealCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_categories', function (Blueprint $table) {

            $table->bigIncrements('id');
            
            $table->integer('meal_id')->unsigned()->index()->comment('Refer meals table');
            // $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');

            $table->integer('category_id')->unsigned()->index()->comment('Refer category table');
            // $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');

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
        Schema::dropIfExists('meal_categories');
    }
}
