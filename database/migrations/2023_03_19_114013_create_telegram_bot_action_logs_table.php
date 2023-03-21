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
            $table->string('type')->nullable();
            $table->bigInteger('chat_id')->nullable();
            $table->bigInteger('telegram_id')->nullable();
            $table->text('action_done')->nullable();
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
        Schema::dropIfExists('telegram_bot_action_logs');
    }
}
