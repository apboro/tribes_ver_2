<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldModuleVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_video', function (Blueprint $table) {
            if (Schema::hasColumn('module_video', 'video_id'))
            {
                Schema::table('module_video', function (Blueprint $table)
                {
                    $table->dropColumn('video_id');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_video', function (Blueprint $table) {
            $table->unsignedBigInteger('video_id');
        });
    }
}
