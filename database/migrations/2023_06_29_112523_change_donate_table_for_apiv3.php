<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDonateTableForApiv3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropColumns('donates', ['main_image_id','success_image_id',
            'prompt_image_id', 'success_description', 'isSendToCommunity', 'index', 'prompt_description',
            'isAutoPrompt', 'prompt_at_hours', 'prompt_at_minutes']);
        Schema::table('donates', function (Blueprint $table) {
           $table->dropConstrainedForeignId('community_id');

            $table->foreignId('user_id')->default(4)->constrained();
            $table->string('image')->nullable();
            $table->boolean('donate_is_active')->default(false);
            $table->text('description')->nullable()->change();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
