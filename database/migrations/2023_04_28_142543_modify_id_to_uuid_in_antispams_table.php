<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyIdToUuidInAntispamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antispams', function (Blueprint $table){
            $table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'))->after('id');
        });

        Schema::table('communities', function(Blueprint $table){
            $table->dropConstrainedForeignId('antispam_id');
        });
        Schema::table('communities', function(Blueprint $table){
            $table->uuid('antispam_uuid')->nullable();
        });

        Schema::table('antispams', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary('uuid');
        });

        Schema::table('communities', function (Blueprint $table){
            $table->foreign('antispam_uuid')->references('uuid')->on('antispams')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uuid_in_antispams', function (Blueprint $table) {
            //
        });
    }
}
