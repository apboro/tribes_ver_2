<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateConditionsDictionariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('conditions_types_dictionary');
        Schema::create('conditions_types_dictionary', function (Blueprint $table) {
            $table->id();
            $table->string('entity');
            $table->string('to_check')->nullable();
            $table->string('detail')->nullable();
        });

        Artisan::call('db:seed ConditionsDictionarySeeder');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditions_types_dictionary');
    }
}
