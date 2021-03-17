<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeverageWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leverage_wallets', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 16, 8)->default(0);
            $table->double('equivalent_amount', 16, 8)->default(0);
            $table->tinyInteger('type')->default(1)->comment('1=buy, 2=sell');
            $table->boolean('status')->default(true);
            $table->integer('leverage',8)->default(0)->comment('Leverage Percentage');
            $table->timestamps();
        });
        Schema::table('leverage_wallets', function (Blueprint $table) {
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
        Schema::dropIfExists('leverage_wallets');
    }
}
