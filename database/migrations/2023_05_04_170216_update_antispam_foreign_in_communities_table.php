<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAntispamForeignInCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->dropForeign(['antispam_uuid']);
            $table->foreign('antispam_uuid')
                ->references('uuid')
                ->on('antispams')
                ->onDelete('set null');
        });
        Schema::table('communities', function (Blueprint $table) {
            $table->dropForeign(['moderation_rule_uuid']);
            $table->foreign('moderation_rule_uuid')
                ->references('uuid')
                ->on('moderation_rules')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communities', function (Blueprint $table) {
            //
        });
    }
}
