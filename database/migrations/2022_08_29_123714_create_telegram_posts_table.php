<?php

use App\Models\TelegramConnection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_posts', function (Blueprint $table) {
            $table->id();
            $table->string('channel_id')->nullable();
            $table->bigInteger('post_id')->nullable();
            $table->text('text')->nullable();
            $table->integer('datetime_record_reaction')->nullable();
            $table->timestamps();

            $table->foreign('channel_id')->references('chat_id')->on('telegram_connections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_posts');
    }
}
