<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeOnUpdateForTelegramMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_messages', function (Blueprint $table) {
            $table->dropForeign(['group_chat_id']);
            $table->foreign('group_chat_id')
                ->references('chat_id')
                ->on('telegram_connections')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_messages', function (Blueprint $table) {
            $table->dropForeign(['group_chat_id']);

            // Add the original foreign key definition without onUpdate('cascade')
            $table->foreign('group_chat_id')
                ->references('chat_id')
                ->on('telegram_connections')
                ->onDelete('cascade');
        });
    }
}
