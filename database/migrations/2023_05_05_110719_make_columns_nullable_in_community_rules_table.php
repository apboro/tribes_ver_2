<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableInCommunityRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moderation_rules', function (Blueprint $table) {
            $table->text('content')->nullable()->change();
            $table->text('warning')->nullable()->change();
            $table->text('action')->nullable()->change();
            $table->text('max_violation_times')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moderation_rules', function (Blueprint $table) {
            //
        });
    }
}
