<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDerivativeSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('derivative_sells', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 16, 8)->default(0);
            $table->double('equivalent_amount', 16, 8)->default(0);
            $table->tinyInteger('type')->default(1)->comment('1=buy, 2=sell');
            $table->boolean('status')->default(true);
            $table->integer('leverage',8)->default(0)->comment('Leverage Percentage');
            $table->double('priceAtSell',16,8)->default(0);
            $table->double('profit',16,8)->default(0);
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
        Schema::dropIfExists('derivative_sells');
    }
}
