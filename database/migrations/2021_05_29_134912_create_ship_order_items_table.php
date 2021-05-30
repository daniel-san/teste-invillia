<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ship_order_id')->index();
            $table->foreign('ship_order_id')->references('id')->on('ship_orders');

            $table->string('title');
            $table->string('note');
            $table->integer('quantity');
            $table->unsignedFloat('price');
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
        Schema::dropIfExists('ship_order_items');
    }
}
