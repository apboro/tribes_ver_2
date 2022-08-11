<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberButtonTariffVariant extends Migration
{
    public function up()
    {
        Schema::table('tarif_variants', function (Blueprint $table) {
            $table->integer('number_button')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tarif_variants', function (Blueprint $table) {
            $table->dropColumn('number_button');
        });
    }
}
