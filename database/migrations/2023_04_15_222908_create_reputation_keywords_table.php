<?php

use App\Models\CommunityReputationRules;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReputationKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reputation_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_reputation_rules_id')->constrained()
                ->on('community_reputation_rules')
                ->references('id')
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
        Schema::dropIfExists('reputation_keywords');
    }
}
