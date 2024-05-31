<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYookassaKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yookassa_keys', function (Blueprint $table) {
            $table->bigInteger('shop_id');
            $table->string('oauth', '512')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->primary('shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yookassa_keys');
    }
}