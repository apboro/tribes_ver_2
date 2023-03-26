<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TelegramUserListParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_user_list_parameters', function (Blueprint $table) {
            $table->bigInteger('list_parameter_id');
            $table->bigInteger('telegram_id');
            $table->foreign('list_parameter_id')->on('list_parameters')->references('id')->onDelete('cascade');
            $table->foreign('telegram_id')->on('telegram_users')->references('telegram_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_user_list_parameters', function (Blueprint $table) {
            //
        });
    }
}
