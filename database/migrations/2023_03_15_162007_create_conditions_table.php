<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('group_id')->nullable();
            $table->string('parameter')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->on('conditions_types_dictionary')
                ->references('id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('user_id')->on('users')
                ->references('id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditions');
    }
}
