<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarningCountFieldInTelegramUsersCommunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_users_community', function (Blueprint $table) {
            $table->unsignedSmallInteger('warnings_count')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_users_community', function (Blueprint $table) {
            $table->dropColumn('warnings_count');
        });
    }
}
