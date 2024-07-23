<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePriceToDecimalInProductsEtcTables extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->change();
        });
        Schema::table('shop_order_product_list', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('price')->change();
        });
        Schema::table('shop_order_product_list', function (Blueprint $table) {
            $table->integer('price')->change();
        });
    }
}