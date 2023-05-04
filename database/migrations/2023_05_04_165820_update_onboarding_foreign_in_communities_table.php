<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOnboardingForeignInCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->dropForeign(['onboarding_uuid']);
            $table->foreign('onboarding_uuid')
                ->references('uuid')
                ->on('onboardings')
                ->onDelete('set null');
        });
        Schema::table('communities', function (Blueprint $table) {
            $table->dropForeign(['if_then_uuid']);
            $table->foreign('if_then_uuid')
                ->references('uuid')
                ->on('if_then_rules')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communities', function (Blueprint $table) {
            //
        });
    }
}
