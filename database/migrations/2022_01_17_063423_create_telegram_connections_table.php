<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('telegram_user_id');
            $table->string('chat_id')->nullable();
            $table->string('chat_title')->nullable();
            $table->string('chat_type')->nullable();
            $table->boolean('isAdministrator')->default(false);
            $table->string('botStatus')->nullable();
            $table->boolean('isActive')->default(false);
            $table->boolean('isChannel')->default(false);
            $table->boolean('isGroup')->default(false);
            $table->string('hash')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_connections');
    }
}
