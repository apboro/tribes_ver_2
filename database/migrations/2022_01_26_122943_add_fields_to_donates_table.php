<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToDonatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donates', function (Blueprint $table) {
            $table->text('prompt_description')->nullable()->after('success_description');
            $table->unsignedBigInteger('prompt_image_id')->default(0)->after('success_image_id');
            $table->boolean('isAutoPrompt')->default('false')->after('isSendToCommunity');
            $table->string('prompt_at_hours')->default(0)->after('isSendToCommunity');
            $table->string('prompt_at_minutes')->default(0)->after('isSendToCommunity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donates', function (Blueprint $table) {
            $table->dropColumn([
                'prompt_description',
                'prompt_image_id',
                'isAutoPrompt',
                'prompt_at_hours',
                'prompt_at_minutes',
                ]);
        });
    }
}
