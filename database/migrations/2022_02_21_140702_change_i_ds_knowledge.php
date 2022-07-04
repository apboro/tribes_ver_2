<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIDsKnowledge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::connection('knowledge')->table('answers', function (Blueprint $table) {
        //     $table->dropColumn('question_id');
        // });
        // Schema::connection('knowledge')->table('questions', function (Blueprint $table) {
        //     $table->unsignedBigInteger('answer_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::connection('knowledge')->table('answers', function (Blueprint $table) {
        //     $table->unsignedBigInteger('question_id');
        // });
        // Schema::connection('knowledge')->table('questions', function (Blueprint $table) {
        //     $table->dropColumn('answer_id');
        // });
    }
}
