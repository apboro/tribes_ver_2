<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBuyableToTrueAtShops extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->boolean('buyable')->default(false)->change();
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->boolean('buyable')->default(true)->change();
        });
    }
}