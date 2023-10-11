<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addPhoneCodeToSmsConfirmation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_confirmation', function (Blueprint $table) {
            $table->integer('phone_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_confirmation', function (Blueprint $table) {
            $table->dropColumn('comission');
        });
    }
}
