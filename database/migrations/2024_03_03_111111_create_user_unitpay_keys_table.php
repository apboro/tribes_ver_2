<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserUnitpayKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_unitpay_keys', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('project_id')->nullable();
            $table->string('secretKey', '64')->nullable();
            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_unitpay_keys');
    }
}
