<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communities', function (Blueprint $table){
           $table->dropConstrainedForeignId('if_then_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communities', function (Blueprint $table){
            $table->uuid('if_then_uuid')->nullable();
            $table->foreign('if_then_uuid')->references('uuid')->on('if_then_rules')->onDelete('cascade');
        });
    }
};
