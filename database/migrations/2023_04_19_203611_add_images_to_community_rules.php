<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToCommunityRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_rules', function (Blueprint $table) {
            $table->string('content_image_path')->nullable();
            $table->string('user_complaint_image_path')->nullable();
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
            $table->dropColumn('content_image_path');
            $table->dropColumn('user_complaint_image_path');
        });
    }
}
