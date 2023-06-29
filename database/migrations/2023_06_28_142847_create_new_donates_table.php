<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewDonatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_donates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ID of creator')->constrained();
            $table->string('title');
            $table->string('command')->comment('Command in telegram')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('donate_is_active')->default(false);
            $table->boolean('random_sum_is_active')->default(false);
            $table->smallInteger('random_sum_min')->nullable();
            $table->smallInteger('random_sum_max')->nullable();
            $table->boolean('fix_sum_1_is_active')->default(false);
            $table->boolean('fix_sum_2_is_active')->default(false);
            $table->boolean('fix_sum_3_is_active')->default(false);
            $table->smallInteger('fix_sum_1')->nullable();
            $table->smallInteger('fix_sum_2')->nullable();
            $table->smallInteger('fix_sum_3')->nullable();
            $table->string('fix_sum_1_button')->nullable();
            $table->string('fix_sum_2_button')->nullable();
            $table->string('fix_sum_3_button')->nullable();
            $table->smallInteger('payments_count')->nullable();
            $table->integer('payments_sum')->nullable();
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
        Schema::dropIfExists('new_donates');
    }
}
