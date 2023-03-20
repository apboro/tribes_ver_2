<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotActionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_action_logs', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('action_type_id');
            $table->bigInteger('community_id')->unsigned();
            $table->bigInteger('telegram_user_id')->unsigned();
            $table->string('action_done');
            $table->timestamps();

            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            $table->foreign('action_type_id')->references('id')->on('telegram_bot_action_types')->onDelete('cascade');
            $table->foreign('telegram_user_id')->references('id')->on('telegram_users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bot_action_logs');
    }
}
