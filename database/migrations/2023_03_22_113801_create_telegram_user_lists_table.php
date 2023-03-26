<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramUserListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_user_lists', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type');
            $table->bigInteger('telegram_id');
            $table->timestamps();

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
        Schema::dropIfExists('telegram_user_black_lists');
    }
}
