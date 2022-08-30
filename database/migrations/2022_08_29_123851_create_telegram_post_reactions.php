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
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('reaction_id')->nullable();
            $table->string('count')->nullable();
            $table->string('datetime_record')->nullable();

            $table->foreign('post_id')->references('id')->on('telegram_posts')->onDelete('cascade');
            $table->foreign('reaction_id')->references('id')->on('telegram_dict_reactions')->onDelete('cascade');
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
