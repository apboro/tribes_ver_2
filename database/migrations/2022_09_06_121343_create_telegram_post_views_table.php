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
            $table->string('chat_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->bigInteger('views_count')->nullable();
            $table->string('datetime_record')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('telegram_post_views');
    }
}
