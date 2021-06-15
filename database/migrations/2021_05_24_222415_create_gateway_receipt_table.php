<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_receipt', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('code');
            $table->string('trading_id');
            $table->string('hc');
            $table->double('amount');
            $table->string('currency');
            $table->string('custom');
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
        Schema::dropIfExists('gateway_receipt');
    }
}
