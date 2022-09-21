<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditUniqueKeyMessageReaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_message_reactions', function (Blueprint $table) {
            $table->dropUnique(['telegram_user_id', 'message_id']);
            $table->unique(['telegram_user_id', 'message_id', 'group_chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_message_reactions', function (Blueprint $table) {
            //
        });
    }
}
