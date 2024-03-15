<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUserIdColumnInUserUnitpayKeysTable extends Migration
{
    public function up()
    {
        Schema::table('user_unitpay_keys', function (Blueprint $table) {
            $table->renameColumn('user_id', 'shop_id');
        });
        Schema::rename('user_unitpay_keys', 'unitpay_keys');
    }

    public function down()
    {
        Schema::rename('unitpay_keys', 'user_unitpay_keys');
        Schema::table('user_unitpay_keys', function (Blueprint $table) {
            $table->renameColumn('shop_id', 'user_id');
        });
    }
}