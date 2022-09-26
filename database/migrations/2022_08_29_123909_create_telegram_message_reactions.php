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
            $table->id();
            $table->string('group_chat_id');
            $table->unsignedBigInteger('telegram_user_id');
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('reaction_id')->nullable();
            $table->string('datetime_record')->nullable();
            $table->timestamps();

            $table->foreign('group_chat_id')->references('chat_id')->on('telegram_connections')->onDelete('cascade');
            $table->foreign('telegram_user_id')->references('telegram_id')->on('telegram_users')->onDelete('cascade');
            $table->foreign('reaction_id')->references('id')->on('telegram_dict_reactions')->onDelete('cascade');

            //$table->unique(['telegram_user_id', 'message_id']);
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
