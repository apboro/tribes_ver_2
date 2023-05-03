<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnKnowledgeIdInCommunitiesTableIfNotExists extends Migration
{

    public function up()
    {
        if (!Schema::hasColumn('communities', 'knowledge_id')) {
            Schema::table('communities', function (Blueprint $table) {
                $table->integer('knowledge_id')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('communities', 'knowledge_id')) {
            Schema::table('communities', function (Blueprint $table) {
                $table->dropColumn('knowledge_id');
            });
        }
    }
}
