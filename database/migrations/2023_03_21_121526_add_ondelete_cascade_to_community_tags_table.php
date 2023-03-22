<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteCascadeToCommunityTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_tag', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['community_id']);
            $table->foreign('tag_id')->references('id')->on('tags')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('community_id')->references('id')->on('communities')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
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
            $table->dropForeign(['tag_id', 'community_id']);
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('community_id')->references('id')->on('communities');
        });
    }
}
