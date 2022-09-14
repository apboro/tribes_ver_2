<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $comms = \App\Models\Community::all();
        foreach ($comms as $comm){
            if(!$comm->connection()->count()){
                $comm->delete();
            }
        }

        Schema::table('communities', function (Blueprint $table) {
            $table->foreign('connection_id')->references('id')->on('telegram_connections')->onDelete('cascade');
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
