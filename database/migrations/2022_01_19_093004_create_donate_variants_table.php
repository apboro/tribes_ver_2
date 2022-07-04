<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donate_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donate_id');
            $table->boolean('isStatic')->default(true);
            $table->boolean('isActive')->default(true);
            $table->string('description')->nullable();
            $table->integer('index')->default(0);
            $table->integer('price')->nullable();
            $table->integer('min_price')->nullable();
            $table->integer('max_price')->nullable();
            $table->integer('currency')->default(0);
            $table->timestamps();

            $table->foreign('donate_id')->references('id')->on('donates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donate_variants');
    }
}
