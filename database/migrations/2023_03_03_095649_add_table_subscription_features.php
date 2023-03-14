<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableSubscriptionFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_features', function (Blueprint $table){
           $table->id();
           $table->unsignedBigInteger('subscription_id');
           $table->string('permission');

           $table->foreign('subscription_id')->references('id')->on('subscriptions')
               ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_features');
    }
}
