<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramUsersTarifVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_users_tarif_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('tarif_variants_id');
            $table->unsignedBigInteger('telegram_user_id');

            $table->foreign('tarif_variants_id')->references('id')->on('tarif_variants')->onDelete('cascade');
            $table->foreign('telegram_user_id')->references('id')->on('telegram_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_users_tarif_variants');
    }
}
