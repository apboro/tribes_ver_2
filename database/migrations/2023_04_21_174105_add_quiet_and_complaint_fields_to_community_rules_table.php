<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuietAndComplaintFieldsToCommunityRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_rules', function (Blueprint $table) {
            $table->text('complaint_text')->nullable();
            $table->boolean('quiet_on_restricted_words')->default(true);
            $table->boolean('quiet_on_complaint')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_rules', function (Blueprint $table) {
            $table->dropColumn('complaint_text');
            $table->dropColumn('quiet_on_restricted_words');
            $table->dropColumn('quiet_on_complaint');
        });
    }
}
