<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramMessageReactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_message_reactions', function (Blueprint $table) {
            $table->unsignedBigInteger('message_id')->nullable();
            $table->unsignedBigInteger('reaction_id')->nullable();
            $table->unsignedBigInteger('telegram_user_id')->nullable();
            $table->string('datetime_record')->nullable();

            $table->foreign('message_id')->references('id')->on('telegram_messages')->onDelete('cascade');
            $table->foreign('reaction_id')->references('id')->on('telegram_dict_reactions')->onDelete('cascade');
            $table->foreign('telegram_user_id')->references('telegram_id')->on('telegram_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_message_reactions');
    }
}
