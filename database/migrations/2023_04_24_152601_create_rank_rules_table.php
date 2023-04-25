<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->jsonb('rank_ids');
            $table->string('name', 120);
            $table->dateTime('period_until_reset')->default(null)->nullable();
            $table->boolean('rank_change_in_chat')->default(false);
            $table->text('rank_change_message')->nullable();
            $table->boolean('first_rank_in_chat')->default(false);
            $table->text('first_rank_message')->nullable();
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
        Schema::dropIfExists('rank_rules');
    }
}
