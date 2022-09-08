<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramPostReactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_post_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('reaction_id');
            $table->string('count')->nullable();
            $table->string('datetime_record');
            $table->timestamps();

            $table->foreign('reaction_id')->references('id')->on('telegram_dict_reactions')->onDelete('cascade');
            $table->foreign('chat_id')->references('chat_id')->on('telegram_connections')->onDelete('cascade');
            
            $table->unique(['chat_id', 'post_id', 'reaction_id', 'datetime_record']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_post_reactions');
    }
}
