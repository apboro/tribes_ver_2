<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveOnbordingRulesToCommunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('communities_onboardings');

        Schema::table('communities', function (Blueprint $table){
           $table->uuid('onboarding_uuid')->nullable();
           $table->foreign('onboarding_uuid')->references('uuid')->on('onboardings')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community', function (Blueprint $table) {
            //
        });
    }
}
