<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemanticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semantic_table', function (Blueprint $table){
           $table->id();
           $table->bigInteger('chat_id');
           $table->dateTime('messages_from_datetime')->nullable()->comment('начальный период сообщений');
           $table->dateTime('messages_to_datetime')->nullable()->comment('конечный период сообщений');
           $table->string('llm_answer')->nullable()->comment('строка, в которой хранится ответ от llm');
           $table->float('sentiment')->nullable()->comment('эмоциональная окраска текста');
           $table->string('sentiment_label')->nullable()->comment('название для sentiment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
