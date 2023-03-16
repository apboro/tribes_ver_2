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
            $table->integer('condition_id')->nullable();
            $table->integer('action_id')->nullable();
            $table->integer('chat_id')->nullable();
            $table->integer('group_id')->nullable();
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
        Schema::dropIfExists('conditions_actions');
    }
}
