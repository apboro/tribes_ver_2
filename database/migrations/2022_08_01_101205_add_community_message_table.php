<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommunityMessageTable extends Migration
{

    public function up()
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->bigInteger('message_id',false)->primary();
            $table->bigInteger('telegram_user_id')->nullable(false);
            $table->string('chat_id',255);
            $table->integer('telegram_date',false,true)->default(0);
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('main')->dropIfExists('telegram_messages');
    }
}
