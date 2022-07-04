<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('files', function (Blueprint $table) {
             $table->boolean('isVideo')->default(false);
             $table->boolean('isAudio')->default(false);
             $table->text('remoteFrame')->nullable();
             $table->bigInteger('webcaster_event_id')->nullable();
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
