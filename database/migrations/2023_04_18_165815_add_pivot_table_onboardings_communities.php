<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPivotTableOnboardingsCommunities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities_onboardings', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('onboarding_id')->nullable();
            $table->unsignedBigInteger('community_id')->nullable();
            $table->foreign('onboarding_id')->references('id')->on('onboardings')->onDelete('cascade');
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('communities_onboardings');
    }
}
