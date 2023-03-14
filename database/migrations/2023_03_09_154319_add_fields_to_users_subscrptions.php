<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersSubscrptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users_subscriptions', function(Blueprint $table) {
          $table->boolean('isActive')->default(true);
          $table->date('expiration_date');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('users_subscriptions', ['isActive', 'expiration_date']);
    }
}
