<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsAi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_ai', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('status')->default(1);
            $table->text('context');
            $table->unsignedBigInteger('community_id')->nullable();
            $table->unsignedBigInteger('questions_id')->nullable();
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
        Schema::dropIfExists('questions_ai');
    }
}
