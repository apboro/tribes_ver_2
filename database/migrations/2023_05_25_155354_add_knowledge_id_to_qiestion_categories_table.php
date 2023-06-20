<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKnowledgeIdToQiestionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('question_categories', 'knowledge_id')) {
            Schema::table('question_categories', function (Blueprint $table) {
                $table->foreignId('knowledge_id')->nullable()->constrained('knowledge')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('question_categories', 'knowledge_id')) {
            Schema::table('question_categories', function (Blueprint $table) {
                $table->dropConstrainedForeignId('knowledge_id');
            });
        }
    }
}
