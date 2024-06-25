<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTrackingToDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_deliveries', function (Blueprint $table) {
            $table->string('track_id', 32)->nullable();
            $table->string('delivery_sum', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_deliveries', function (Blueprint $table) {
            $table->dropColumn('track_id');
            $table->dropColumn('delivery_sum');
        });
    }
}
