<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLegalInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_legal_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('name')->comment('Наименование плательщика');
            $table->string('inn', '16')->comment('ИНН плательщика');
            $table->string('kpp', '16')->nullable()->comment('КПП плательщика');
            $table->string('email', '124')->comment('Электронная почта');
            $table->string('phone', '16')->nullable()->comment('Номер мобильного');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_legal_info');
    }
}
