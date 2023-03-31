<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateActionsDictionariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('actions_dictionary');

        Schema::create('actions_dictionary', function (Blueprint $table) {
            $table->id();
            $table->string('type');
        });

        Artisan::call('db:seed ActionsDictionarySeeder');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions_dictionary');
    }
}
