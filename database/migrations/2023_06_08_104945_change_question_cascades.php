<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQuestionCascades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['answer_id']);
            $table->dropForeign(['knowledge_id']);
            $table->foreign('answer_id')
                ->on('answers')
                ->references('id')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('knowledge_id')
                ->on('knowledge')
                ->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
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
