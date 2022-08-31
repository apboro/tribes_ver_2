<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessHashOnTelegramConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_connections', function (Blueprint $table) {
            $table->string('access_hash')->nullable();
            $table->string('comment_chat_id',255)->nullable();
            $table->string('comment_chat_hash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_connections', function (Blueprint $table) {
            //
        });
    }
}
