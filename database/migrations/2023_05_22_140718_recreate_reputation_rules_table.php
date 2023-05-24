<?php

use App\Models\CommunityReputationRules;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('communities', 'reputation_rules_uuid')) {
            Schema::table('communities', function (Blueprint $table) {
                $table->dropConstrainedForeignId('reputation_rules_id');
                $table->uuid('reputation_rules_uuid')->nullable();
            });
        } else {
            Schema::table('communities', function (Blueprint $table) {
                $table->dropConstrainedForeignId('reputation_rules_uuid');
            });
        }
        Schema::dropIfExists('reputation_keywords');
        Schema::dropIfExists('community_reputation_rules');
        Schema::create('community_reputation_rules', function (Blueprint $table) {
            $table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('title', 120);
            $table->enum('who_can_rate', ['all', 'owner', 'owner_and_admin'])->nullable()->default('all');
            $table->unsignedInteger('restrict_rate_member_period')->nullable();

            $table->unsignedInteger('delay_start_rules_seconds')->nullable();
            $table->smallInteger('delay_start_rules_total_messages')->nullable();

            $table->boolean('show_rating_tables')->default(false);
            $table->enum('show_rating_tables_period', ['first_day_of_year', 'first_day_of_month', 'first_day_of_week'])->nullable()->default('first_day_of_year');
            $table->string('show_rating_tables_time')->nullable();
            $table->smallInteger('show_rating_tables_number_of_users')->nullable();
            $table->string('show_rating_tables_image')->nullable();
            $table->text('show_rating_tables_message')->nullable();

            $table->boolean('notify_about_rate_change')->default(false);
            $table->smallInteger('notify_about_rate_change_points')->nullable();
            $table->string('notify_about_rate_change_image')->nullable();
            $table->text('notify_about_rate_change_message')->nullable();

            $table->boolean('restrict_accumulate_rate')->default(false);
            $table->enum('restrict_accumulate_rate_period', ['first_day_of_year', 'first_day_of_month', 'first_day_of_week'])->nullable()->default('first_day_of_year');
            $table->string('restrict_accumulate_rate_time')->nullable();
            $table->string('restrict_accumulate_rate_image')->nullable();
            $table->text('restrict_accumulate_rate_message')->nullable();

            $table->timestamps();
        });

        if (!Schema::hasColumn('communities', 'reputation_rules_uuid')) {
            Schema::table('communities', function (Blueprint $table) {
                $table->uuid('reputation_rules_uuid')->nullable();
            });
        }
        Schema::table('communities', function (Blueprint $table) {
            $table->foreign('reputation_rules_uuid')->references('uuid')->on('community_reputation_rules')->onDelete('cascade');
        });

        Schema::create('reputation_keywords', function (Blueprint $table) {
            $table->id();
            $table->uuid('community_reputation_rules_uuid');
            $table->foreign('community_reputation_rules_uuid')
                ->on('community_reputation_rules')
                ->references('uuid')
                ->onDelete('cascade');
            $table->tinyInteger('direction')->default(1);
            $table->string('word');
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
        Schema::table('communities', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['reputation_rules_uuid']);

            // Drop the new column and recreate the old one
            $table->dropColumn('reputation_rules_uuid');
            $table->foreignId('reputation_rules_id')->constrained()->onDelete('cascade');
        });

        Schema::dropIfExists('community_reputation_rules');

        Schema::create('reputation_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->timestamps();
        });
    }
};
