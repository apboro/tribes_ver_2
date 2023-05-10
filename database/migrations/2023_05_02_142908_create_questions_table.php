<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('questions');
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['draft','draft_auto','published']);
            $table->enum('overlap', ['full','part'])->default('part');
            $table->text('context');
            $table->integer('c_enquiry');
            $table->foreignId('answer_id')->constrained('answers')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('question_categories')->cascadeOnDelete();
            $table->foreignId('knowledge_id')->constrained('knowledge');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
