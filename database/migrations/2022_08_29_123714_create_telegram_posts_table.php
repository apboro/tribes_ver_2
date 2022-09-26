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
        Schema::table('telegram_connections', function (Blueprint $table) {
            $table->unique('chat_id');
        });
        Schema::create('telegram_posts', function (Blueprint $table) {
            $table->id();
            $table->string('channel_id');
            $table->bigInteger('post_id');
            $table->text('text')->nullable();
            $table->integer('datetime_record_reaction')->nullable();
            $table->bigInteger('comments')->default(0);
            $table->bigInteger('utility')->default(0);
            $table->boolean('flag_observation')->default(true);
            $table->bigInteger('post_date')->nullable();
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
        Schema::dropIfExists('telegram_posts');
    }
}
