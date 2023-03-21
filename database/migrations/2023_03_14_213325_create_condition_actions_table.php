<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions_actions', function (Blueprint $table) {
            $table->id();
            $table->string('group_uuid')->index();
            $table->unsignedBigInteger('community_id');
            $table->timestamps();

            $table->foreign('community_id')->on('communities')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditions_actions');
    }
}
