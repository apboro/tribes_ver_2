<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramPostViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_post_views', function (Blueprint $table) {
            $table->id();
            $table->string('channel_id');
            $table->unsignedBigInteger('post_id');
            $table->bigInteger('views_count')->nullable();
            $table->string('datetime_record')->nullable();
            $table->timestamps();

            $table->foreign('channel_id')->references('chat_id')->on('telegram_connections')->onDelete('cascade');

            $table->unique(['channel_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_post_views');
    }
}
