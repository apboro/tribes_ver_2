<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->text('content');
            $table->unsignedInteger('max_violation_times');
            $table->text('warning');
            $table->string('warning_image_path')->nullable();
            $table->unsignedTinyInteger('action');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('community_rules');
    }
}
