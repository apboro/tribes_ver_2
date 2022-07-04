<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_id');
            $table->integer('test_period')->unsigned()->comment('Тестовый период в днях (кол-во)');

            $table->string('title')->nullable();
            $table->text('main_description')->nullable();
            $table->unsignedBigInteger('main_image_id')->nullable();

            $table->text('welcome_description')->nullable();
            $table->unsignedBigInteger('welcome_image_id')->nullable();

            $table->text('reminder_description')->nullable();
            $table->unsignedBigInteger('reminder_image_id')->nullable();

            $table->text('thanks_description')->nullable();
            $table->unsignedBigInteger('thanks_image_id')->nullable();

            $table->timestamps();

            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
}
