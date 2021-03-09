<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDmgCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmg_coins', function (Blueprint $table) {
            $table->id();
            $table->double('start_price')->default('0.032875');
            $table->string('name')->default('DMGCoin');
            $table->date('start_date')->default('2018-10-12');
            $table->date('end_date')->default('2021-01-01');
            $table->float('price_update')->default(2666.9);
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
        Schema::dropIfExists('dmg_coins');
    }
}
