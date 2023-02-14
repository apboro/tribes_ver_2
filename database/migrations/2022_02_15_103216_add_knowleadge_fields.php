<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKnowleadgeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('answers', function (Blueprint $table) {
             $table->unsignedBigInteger('community_id')->nullable();
             $table->unsignedBigInteger('question_id')->nullable();
             $table->string('title')->nullable();
             $table->json('tags')->nullable();
         });
         Schema::table('questions', function (Blueprint $table) {
             $table->unsignedBigInteger('community_id')->nullable();
             $table->string('title')->nullable();
             $table->json('tags')->nullable();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('answers', function (Blueprint $table) {
             $table->dropColumn('community_id');
             $table->dropColumn('question_id');
             $table->dropColumn('title');
             $table->dropColumn('tags');
         });
         Schema::table('questions', function (Blueprint $table) {
             $table->dropColumn('title');
             $table->dropColumn('community_id');
             $table->dropColumn('tags');
         });
    }
}
