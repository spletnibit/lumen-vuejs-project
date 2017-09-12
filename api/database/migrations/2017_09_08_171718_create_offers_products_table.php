<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('offer_id')->unsigned();
            $table->integer('qty')->unsigned();
            $table->integer('discount')->unsigned();
            $table->integer('total')->unsigned();
        });

        Schema::table('offer_product', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('offer_id')->references('id')->on('offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_product');
    }
}
