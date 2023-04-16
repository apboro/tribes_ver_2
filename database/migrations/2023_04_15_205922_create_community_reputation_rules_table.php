<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityReputationRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_reputation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->string('name', 120);
            $table->tinyInteger('who_can_rate');
            $table->unsignedInteger('rate_period')->nullable();
            $table->unsignedInteger('rate_member_period')->nullable();
            $table->unsignedInteger('rate_reset_period')->nullable();

            $table->unsignedInteger('notify_about_rate_change')->nullable();
            $table->unsignedInteger('notify_type')->nullable();
            $table->unsignedInteger('notify_period')->nullable();
            $table->text('notify_content_chat')->nullable();
            $table->text('notify_content_user')->nullable();

            $table->unsignedInteger('public_rate_in_chat')->nullable();
            $table->unsignedInteger('type_public_rate_in_chat')->nullable();
            $table->unsignedInteger('rows_public_rate_in_chat')->nullable();
            $table->text('text_public_rate_in_chat')->nullable();
            $table->unsignedInteger('period_public_rate_in_chat')->nullable();

            $table->unsignedInteger('count_for_new')->nullable();
            $table->unsignedInteger('start_count_for_new')->nullable();
            $table->unsignedInteger('count_reaction')->nullable();
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
        Schema::dropIfExists('community_reputation_rules');
    }
}
