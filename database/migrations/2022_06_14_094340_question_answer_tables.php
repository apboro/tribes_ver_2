<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class QuestionAnswerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('community_id')
                ->references('id')->on('communities');
            $table->foreignId('author_id')
                ->constrained('users');
            $table->string('analog_uuid', 32)
                ->nullable()->default(null)
                ->comment('Зарезервировано уникальный идентификатор аналогичности вопроса');
            $table->string('uri_hash', 32)
                ->default(null)
                ->comment(' уникальный идентификатор хеш для ссылки');
            $table->tinyInteger('is_draft')
                ->default(0)
                ->comment('черновик 0-черновик 1-не черновик');
            $table->tinyInteger('is_public')
                ->default(0)
                ->comment('статус публикации 0-не опубликовано 1-опубликовано');
            $table->integer('c_enquiry')
                ->comment('количество обращений к этому вопросу');
            $table->mediumText('context')->nullable();
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreign('community_id')->references('id')
                ->on('communities');
            $table->tinyInteger('is_draft')->default(0)
                ->comment('черновик 0-черновик 1-не черновик, вопрос без ответа');
            $table->mediumText('context')->nullable();
            $table->unique(['id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('answers');
//        Schema::dropIfExists('questions');
        echo 'there is no rollback';
    }
}
