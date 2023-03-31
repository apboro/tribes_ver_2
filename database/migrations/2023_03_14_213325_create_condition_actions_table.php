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
            $table->unsignedBigInteger('condition_id');
            $table->string('group_uuid')->index();
            $table->unsignedBigInteger('community_id');
            $table->string('group_prefix')->nullable();
            $table->integer('parent_group_id')->nullable()->default(null);

            $table->foreign('community_id')->on('communities')->references('id');
            $table->foreign('condition_id')->on('conditions')->references('id');
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
