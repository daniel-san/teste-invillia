<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipOrderAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_order_addresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ship_order_id')->index();
            $table->foreign('ship_order_id')->references('id')->on('ship_orders');

            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('country');

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
        Schema::dropIfExists('ship_order_addresses');
    }
}
