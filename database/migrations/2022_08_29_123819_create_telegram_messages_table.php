<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->string('group_chat_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('telegram_user_id')->nullable();
            $table->bigInteger('message_id');
            $table->bigInteger('comment_chat_id')->nullable();
            $table->text('text')->nullable();
            $table->string('chat_type');
            $table->bigInteger('parrent_message_id')->nullable();
            $table->bigInteger('answers')->default(0);
            $table->bigInteger('utility')->default(0);
            $table->integer('datetime_record_reaction')->nullable();
            $table->boolean('flag_observation')->default(true);
            $table->bigInteger('message_date');
            $table->timestamps();

            $table->foreign('group_chat_id')->references('chat_id')->on('telegram_connections')->onDelete('cascade');
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
        Schema::dropIfExists('telegram_messages');
    }
}
