<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyIdToUuidInCommunityRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('community_rules', 'moderation_rules');

        Schema::table('moderation_rules', function (Blueprint $table) {
            $table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'))->after('id');
        });

        Schema::table('communities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('community_rule_id');
        });
        Schema::table('communities', function (Blueprint $table) {
            $table->uuid('moderation_rule_uuid')->nullable();
        });

        Schema::table('restricted_words', function (Blueprint $table) {
            $table->dropConstrainedForeignId('community_rule_id');
        });
        Schema::table('restricted_words', function (Blueprint $table) {
            $table->uuid('moderation_rule_uuid')->nullable();
        });

        Schema::table('moderation_rules', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary('uuid');
        });

        Schema::table('communities', function (Blueprint $table) {
            $table->foreign('moderation_rule_uuid')->references('uuid')->on('moderation_rules')->onDelete('cascade');
        });
        Schema::table('restricted_words', function (Blueprint $table) {
            $table->foreign('moderation_rule_uuid')->references('uuid')->on('moderation_rules')->onDelete('cascade');
        });

}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public
function down()
{
    Schema::table('uuid_in_community_rules', function (Blueprint $table) {
        //
    });
}
}
