<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeReputationTableColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_reputation_rules', function (Blueprint $table) {
            $table->unsignedInteger('rate_period')->nullable()->change();
            $table->unsignedInteger('rate_member_period')->nullable()->change();
            $table->unsignedInteger('rate_reset_period')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
