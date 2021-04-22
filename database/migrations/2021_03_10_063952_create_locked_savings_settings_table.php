<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockedsavingssettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locked_savings_settings', function (Blueprint $table) {
            $table->id();
            $table->double('lot_size', 16, 8);
            $table->double('rate_1', 16, 8);
            $table->double('rate_2', 16, 8);
            $table->double('rate_3', 16, 8);
            $table->double('rate_4', 16, 8);
            $table->integer('duration_1');
            $table->integer('duration_2');
            $table->integer('duration_3');
            $table->integer('duration_4');
//            $table->double('interest_per_lot', 11, 5);
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
        Schema::dropIfExists('lockedsavingssettings');
    }
}
