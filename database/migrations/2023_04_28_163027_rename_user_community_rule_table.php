<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUserCommunityRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_community_rules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('community_id');
        });
        Schema::rename('user_community_rules', 'if_then_rules');

        Schema::table('communities', function (Blueprint $table) {
            $table->uuid('if_then_uuid')->nullable();
            $table->foreign('if_then_uuid')->references('uuid')->on('if_then_rules')->onDelete('cascade');
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
