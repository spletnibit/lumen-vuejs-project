<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->decimal('subtotal');
            $table->decimal('subtotal_discount');
            $table->decimal('subtotal_vat');
            $table->decimal('total');
            $table->dateTime('pdf_generated_at')->nullable();
            $table->timestamps();
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
