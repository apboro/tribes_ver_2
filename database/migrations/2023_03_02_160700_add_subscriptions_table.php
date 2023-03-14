<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('price')->default('0');
            $table->integer('period_days')->default(30);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('file_id')->nullable();
            $table->integer('commission')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
