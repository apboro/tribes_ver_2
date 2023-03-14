<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_tag', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('community_id');
            $table->foreign('community_id')->references('id')->on('communities');

            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_tag', function (Blueprint $table) {
            //
        });
    }
}
