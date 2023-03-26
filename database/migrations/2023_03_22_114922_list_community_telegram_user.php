<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ListCommunityTelegramUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_community_telegram_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telegram_id');
            $table->bigInteger('community_id');

            $table->unique(['telegram_id', 'community_id']);

            $table->foreign('community_id')->on('communities')->references('id')->onDelete('cascade');
            $table->foreign('telegram_id')->on('telegram_users')->references('telegram_id')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('black_list_community_telegram_user', function (Blueprint $table) {
            //
        });
    }
}
