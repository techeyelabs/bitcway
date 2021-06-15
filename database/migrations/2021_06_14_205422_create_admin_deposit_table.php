<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('admindeposit', function (Blueprint $table) {
            $table->id();
            $table->string('user_email');
            $table->string('user_name');
            $table->double('directdepositamount');
            $table->integer('user_id');
            $table->timestamps();
        });
        Schema::table('admindeposit', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AdminDeposit');
    }
}
