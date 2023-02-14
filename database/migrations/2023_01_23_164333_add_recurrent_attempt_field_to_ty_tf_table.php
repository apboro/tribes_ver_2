<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecurrentAttemptFieldToTyTfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_users_tarif_variants', function (Blueprint $table) {
            $table->integer('recurrent_attempt')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_users_tarif_variants', function (Blueprint $table) {
            $table->dropColumn('recurrent_attempt');
        });
    }
}
