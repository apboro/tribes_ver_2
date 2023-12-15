<?php

use App\Models\Webinar;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramChatThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_chat_themes', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id')->nullable();           
            $table->text('theme')->nullable();
            $table->integer('message_quantity')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_chat_themes');
    }
}
