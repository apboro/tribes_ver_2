<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->tinyInteger('sort')->nullable()->default(0);
            $table->string('ico')->nullable();
            $table->tinyInteger('friends')->nullable()->default(0);
            $table->text('gradient')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn('sort');
            $table->dropColumn('ico');
            $table->dropColumn('friends');
            $table->dropColumn('gradient');
        });
    }
}