<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSemanticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('semantic_table', 'class_name')) {
            Schema::table('semantic_table', function (Blueprint $table) {
                $table->dropColumn(['class_name']);
            });
        }
        if (Schema::hasColumn('semantic_table', 'class_probability')) {
            Schema::table('semantic_table', function (Blueprint $table) {
                $table->dropColumn(['class_probability']);
                $table->unsignedBigInteger('class_id')->nullable();
            });
        }
        if (!Schema::hasColumn('semantic_table', 'class_id')) {
            Schema::table('semantic_table', function (Blueprint $table) {
                $table->unsignedBigInteger('class_id')->nullable();
            });
        }
    }

}
