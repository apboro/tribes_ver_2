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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_id')->constrained('knowledge');
            $table->enum('status', ['draft','draft_auto','published']);
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('overlap', ['full','part'])->default('part');
            $table->text('context');
            $table->foreignId('answer_id')->constrained('answers')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('uri_hash')->unique();
            $table->integer('c_enquiry');
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
