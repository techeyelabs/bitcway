<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminWithdrawMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_withdraw_messages', function (Blueprint $table) {
            $table->id();
            $table->text('message')->comment('Withdraw Notification Message');
            $table->display_message('display_message')->comment('1.Checked, 2.Unchecked');
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
        Schema::dropIfExists('admin_withdraw_messages');
    }
}
