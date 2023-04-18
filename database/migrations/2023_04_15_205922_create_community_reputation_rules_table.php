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
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('name', 120);

            $table->enum('who_can_rate', ['all', 'owner', 'owner_and_admin']);

            $table->unsignedInteger('rate_period')->default(0);
            $table->unsignedInteger('rate_member_period')->default(0);
            $table->unsignedInteger('rate_reset_period')->default(0);

            $table->boolean('notify_about_rate_change')->default(false);
            $table->enum('notify_type', ['common', 'all'])->nullable();
            $table->unsignedInteger('notify_period')->nullable();
            $table->text('notify_content_chat')->nullable();
            $table->text('notify_content_user')->nullable();

            $table->boolean('public_rate_in_chat')->default(false);
            $table->unsignedInteger('type_public_rate_in_chat')->nullable();
            $table->unsignedInteger('rows_public_rate_in_chat')->nullable();
            $table->text('text_public_rate_in_chat')->nullable();
            $table->unsignedInteger('period_public_rate_in_chat')->nullable();

            $table->boolean('count_for_new')->default(true);
            $table->unsignedInteger('start_count_for_new')->nullable();
            $table->boolean('count_reaction')->default(false);
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
