<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramUserRankRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_user_rank_rule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('telegram_user_id');
            $table->unsignedBigInteger('community_id')->nullable();
            $table->unsignedBigInteger('rank_rule_id')->nullable();
            $table->timestamps();

            $table->foreign('rank_rule_id')
                ->references('id')->on('rank_rules');
            $table->foreign('community_id')
                ->references('id')->on('communities');
            $table->foreign('telegram_user_id')
                ->references('id')->on('telegram_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_user_rank_rule');
    }
}
