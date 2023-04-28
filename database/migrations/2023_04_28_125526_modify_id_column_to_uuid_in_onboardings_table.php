<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class ModifyIdColumnToUuidInOnboardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('onboardings', function (Blueprint $table){
            $table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'))->after('id');
        });

        Schema::table('communities_onboardings', function(Blueprint $table){
           $table->dropConstrainedForeignId('onboarding_id');
        });
        Schema::table('communities_onboardings', function(Blueprint $table){
            $table->uuid('onboarding_uuid')->nullable();
        });

        Schema::table('onboardings', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary('uuid');
        });

        Schema::table('communities_onboardings', function (Blueprint $table){
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

    }
}
