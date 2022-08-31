<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChatIdOnTelegramPostReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_post_reactions', function (Blueprint $table) {
            $table->string('chat_id')->nullable();

            $table->foreign('chat_id')->references('chat_id')->on('telegram_connections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_post_reactions', function (Blueprint $table) {
            //
        });
    }
}
