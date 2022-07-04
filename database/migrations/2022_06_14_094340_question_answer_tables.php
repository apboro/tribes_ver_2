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
        Schema::connection('knowledge')->create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained('public.communities');
            $table->foreignId('author_id')->constrained('public.users');
            $table->string('analog_uuid', 32)->nullable()->default(null)
                ->comment('Зарезервировано уникальный идентификатор аналогичности вопроса');
            $table->string('uri_hash', 32)->default(null)
                ->comment(' уникальный идентификатор хеш для ссылки');
            $table->tinyInteger('is_draft')->default(0)
                ->comment('черновик 0-черновик 1-не черновик');
            $table->tinyInteger('is_public')->default(0)
                ->comment('статус публикации 0-не опубликовано 1-опубликовано');
            $table->integer('c_enquiry')
                ->comment('количество обращений к этому вопросу');
            $table->mediumText('context')->nullable();
            $table->timestamps();
        });

        Schema::connection('knowledge')->create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')
                ->on('questions')->onDelete('cascade');
            $table->foreignId('community_id')->constrained('public.communities');
            $table->tinyInteger('is_draft')->default(0)
                ->comment('черновик 0-черновик 1-не черновик, вопрос без ответа');
            $table->mediumText('context')->nullable();
            $table->timestamps();
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
        Schema::connection('knowledge')->dropIfExists('answers');
        Schema::connection('knowledge')->dropIfExists('questions');

    }
}
