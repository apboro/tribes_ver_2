<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyIdToUuidInIfthenRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_community_rules', function (Blueprint $table){
            $table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'))->after('id');
        });

        Schema::table('user_community_rules', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uuid_in_ifthen_rules', function (Blueprint $table) {
            //
        });
    }
}
