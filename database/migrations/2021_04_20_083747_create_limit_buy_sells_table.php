<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLimitBuySellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limitbuysell', function (Blueprint $table) {
            $table->id();
            $table->integer('derivative',10);
            $table->tinyInteger('limitType')->comment('1: Buy 2:Sell');
            $table->double('priceLimit', 16, 8);
            $table->double('currencyAmount', 16, 8);
            $table->tinyInteger('transactionStatus')->default(1)->comment('1: Processing 2: Transaction Completed');
            $table->timestamps();
        });
        Schema::table('derivative_sells', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('currency_id')->constrained('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('limitbuysell');
    }
}
